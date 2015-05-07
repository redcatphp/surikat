<?php namespace Unit\Dispatcher;
use ReflectionClass;
use ObjexLoader\MutatorMagicTrait;
use Unit\Route\Route;
class Uri {
	use MutatorMagicTrait;
	protected $routes = [];
	protected $questionMark;
	function append($pattern,$callback,$index=0){
		return $this->route($pattern,$callback,$index);
	}
	function prepend($pattern,$callback,$index=0){
		return $this->route($pattern,$callback,$index,true);
	}
	function route($route,$callback,$index=0,$prepend=false){
		if(is_string($route)){
			if(strpos($route,'/^')===0&&strrpos($route,'$/')-strlen($route)===-2){
				$route = ['Unit_Route_Regex',$route];
			}
			else{
				$route = ['Unit_Route_Prefix',$route];
			}
		}
		$route = [$route,$callback];
		if(!isset($this->routes[$index]))
			$this->routes[$index] = [];
		if($prepend)
			array_unshift($this->routes[$index],$route);
		else
			array_push($this->routes[$index],$route);
		return $this;
	}
	function __invoke(){
		return $this->run(func_get_arg(0));
	}
	function run($uri){
		$uri = ltrim($uri,'/');
		ksort($this->routes);
		foreach($this->routes as $group){
			foreach($group as $router){
				list($route,$callback) = $router;
				$this->objectify($route);
				$params = call_user_func_array($route,[&$uri]);
				if($params===true){
					return true;
				}
				elseif($params!==null&&$params!==false){
					$this->objectify($callback);
					while(is_callable($callback)){
						$callback =	call_user_func($callback,$params,$uri,$route);
					}
					return true;
				}
			}
		}
		return false;
	}
	function runFromGlobals(){
		//$path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
		$s = strlen($_SERVER['CWD'])-1;
		$p = strpos($_SERVER['REQUEST_URI'],'?');
		if($p===false)
			$path = substr($_SERVER['REQUEST_URI'],$s);
		else
			$path = substr($_SERVER['REQUEST_URI'],$s,$p-$s);
		$this->questionMark = !!$p;
		$this->run($path);
	}
	function haveParameters(){
		return $this->questionMark||count($_GET);
	}
	private function objectify(&$a){
		if(is_array($a)&&isset($a[0])&&is_string($a[0])){
			if($a[0]=='new'){
				array_shift($a);
				$c = array_shift($a);
				if(empty($a))
					$a = new $c();
				else
					$a = $this->ReflectionClass($c)->newInstanceArgs($a);
			}
			else{
				$a = $this->getDependency(array_shift($a),$a);
			}
		}
		if($a instanceof Route){
			$a->setDependency('Unit_Dispatcher_Uri',$this);
		}
	}
}