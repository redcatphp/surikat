<?php
namespace RedCat\Plugin\PHPConfig;
use Pharborist\Parser;
use Pharborist\Namespaces\NamespaceNode;
use Pharborist\Filter;

use Pharborist\Types\ArrayNode;
use Pharborist\Types\ArrayPairNode;
use Pharborist\WhitespaceNode;

class TokenTree implements \ArrayAccess{
	private $data = [];
	private $tree;
	function __construct($filename){
		$this->tree = Parser::parseFile($filename);
		$collectArrayR = function(ArrayNode $node,&$a=null)use(&$collectArrayR){
			$keys = [];
			$vals = [];
			$i = 0;
			foreach($node->getElements() as $el){
				if($el instanceof ArrayPairNode){
					$k = (string)$el->getKey();
					$k = trim($k,'"\'');
					$v = $el->getValue();
					if($v instanceof ArrayNode){
						$v = $collectArrayR($v);
					}
					else{
						$v = (string)$v;
					}
					$keys[] = $k;
					$vals[] = $v;
				}
				elseif($el instanceof ArrayNode){
					$keys[] = $i;
					$vals[] = $collectArrayR($el);
					$i++;
				}
				else{
					$keys[] = $i;
					$vals[] = (string)$el;
					$i++;
				}
			}
			$a = array_combine($keys,$vals);
			return $a;
			
		};
		$found = false;
		$this->tree->walk(function($node)use(&$found,&$collectArrayR,&$a){
			if($found)
				return;
			if($node instanceof ArrayNode){
				$collectArrayR($node,$a);
				$found = true;
			}
		});
		$this->data = $a;
	}
	function offsetSet($k,$v){
		$this->data[$k] = $v;
	}
	function offsetExists($k){
		return isset($this->data[$k]);
	}
	function &offsetGet($k){
		return $this->data[$k];
	}
	function offsetUnset($k){
		unset($this->data[$k]);
	}
	private static function var_export($var, $indent=0){
		switch(gettype($var)){
			case 'string':
				return "'".addcslashes($var, '\'')."'";
			case 'array':
				$indexed = array_keys($var) === range(0, count($var) - 1);
				$r = [];
				foreach($var as $key => $value){
					$r[] = str_repeat("\t",$indent+1)
						 .($indexed?'':self::var_export($key).' => ')
						 .self::var_export($value, $indent+1);
				}
				return "[\n" . implode(",\n", $r) . "\n" . str_repeat("\t",$indent) . "]";
			case 'boolean':
				return $var?'true':'false';
			default:
				if(is_float($var))
					return (string)$var;
				return var_export($var, true);
		}
	}
	static function var_codify($var, $indent=0){
		if(is_array($var)){
			$indexed = array_keys($var) === range(0, count($var) - 1);
			$r = [];
			foreach($var as $key => $value){
				$r[] = str_repeat("\t",$indent+1)
					 .($indexed?'':"'".addcslashes($key, '\'')."'".' => ')
					 .self::var_codify($value, $indent+1);
			}
			return "[\n" . implode(",\n", $r) . "\n" . str_repeat("\t",$indent) . "]";
		}
		else{
			return (string)$var;
		}
	}
	
	function update(){
		$collectRefR = function(ArrayNode $node,$data)use(&$collectRefR){
			$i = 0;
			foreach($node->getElements() as $el){
				if($el instanceof ArrayPairNode){
					$k = (string)$el->getKey();
					$k = trim($k,'"\'');
					$v = $el->getValue();
					if(!isset($data[$k])){
						$next = $el->next();
						if($next&&$next->getType()===',')
							$next->remove();
						while(($next=$el->next()) instanceof WhitespaceNode){
							$next->remove();
						}
						$el->remove();
					}
					else{
						if($v instanceof ArrayNode){
							$v = $collectRefR($v,$data[$k]);
							unset($data[$k]);
						}
						else{
							$v->replaceWith(Parser::parseExpression($data[$k]));
							unset($data[$k]);
						}
					}
				}
				elseif($el instanceof ArrayNode){
					if(!isset($data[$i])){
						$el->remove();
					}
					else{
						$collectRefR($el,$data[$i]);
						unset($data[$i]);
						$i++;
					}
				}
				else{
					if(!isset($data[$i])){
						$el->remove();
					}
					else{
						$el->replaceWith(Parser::parseExpression($data[$i]));
						unset($data[$i]);
						$i++;
					}
				}
			}
			
			foreach($data as $key=>$val){
				if(is_integer($key)){
					if(is_array($val)){
						$collectRefR($node,$val);
					}
					else{
						$node->append(Parser::parseExpression($val));
					}
				}
				else{
					if(is_array($val)){
						$i = 0;
						foreach($node->getElements() as $el){
							if($el instanceof ArrayPairNode){
								$k = (string)$el->getKey();
								$k = trim($k,'"\'');
								$v = $el->getValue();
								$v->replaceWith(Parser::parseExpression($data[$k]));
							}
							elseif($el instanceof ArrayNode){
								$el->replaceWith(Node::fromValue($data[$i]));
								$i++;
							}
							else{
								$el->replaceWith(Parser::parseExpression($data[$i]));
								$i++;
							}
						}
					}
					else{
						$pair = ArrayPairNode::create($key,$val);
						$node->append($pair);
					}
				}
			}
		};
		
		$found = false;
		$this->tree->walk(function($node)use(&$found,&$collectRefR){
			if($found) return;
			if($node instanceof ArrayNode){
				$collectRefR($node,$this->data);
				$found = true;
			}
		});
		
	}
	
	static function dotOffset($dotKey,&$config,$value=null){
		$dotKey = explode('.',$dotKey);
		$k = array_shift($dotKey);
		if(!isset($config[$k]))
			return;
		$v = &$config[$k];
		while($k = array_shift($dotKey)){
			if(!isset($v[$k]))
				return;
			$v = &$v[$k];
		}
		if(func_num_args()>2)
			$v = $value;
		return $v;
	}
	
	function __toString(){
		$this->update();
		$str = (string)$this->tree;
		return $str;
	}
}