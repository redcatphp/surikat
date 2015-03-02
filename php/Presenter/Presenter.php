<?php namespace Surikat\Presenter;
use Surikat\Vars\ArrayObject;
use Surikat\Templator\FILE;
use Surikat\Templator\TML;
use Dispatcher\Index;
class Presenter extends ArrayObject{
	static function load(TML $tml){
		if(!$tml->View)
			return;
		$c = get_called_class();
		$o = new $c();
		$o->merge([
			'templatePath'		=> $tml->View?$tml->View->path:'',
			'presentAttributes'	=> $tml->getAttributes(),
			'presentNamespaces'	=> $tml->_namespaces,
		]);
		$o->setView($tml->View);
		$o->timeCompiled = time();
		$o->assign();
		$head = '<?php if(isset($THIS))$_THIS=$THIS;$THIS=new '.$c.'('.var_export($o->getArray(),true).');';
		$head .= '$THIS->setView($this);';
		$head .= '$THIS->execute();';
		$head .= 'extract((array)$THIS,EXTR_OVERWRITE|EXTR_PREFIX_INVALID,\'i\');?>';
		$tml->head($head);
		if(!empty($tml->childNodes))
			$tml->foot('<?php if(isset($_THIS));extract((array)($THIS=$_THIS),EXTR_OVERWRITE|EXTR_PREFIX_INVALID,\'i\'); ?>');
		$tml->View->present = $o;
	}
	protected $View;
	function setView($View){
		$this->View = $View;
	}
	function getView(){
		return $this->View;
	}
	function assign(){}
	function execute(){	
		if(
			isset($this->presentAttributes->uri)
			&&$this->presentAttributes->uri=='static'
			&&(
				!empty($_GET)||
				(
					($r = $this->getView())
					&&($r = $r->getController())
					&&($r = $r->getRouter())
					&&(method_exists($r,'getParams'))
					&&count($r->getParams())>1
				)
			)
		)
			(new Index())->getController()->error(404);
		$this->time = time();
		$this->BASE_HREF = $this->HTTP_Domain()->getBaseHref();
		$this->dynamic();
	}
	function dynamic(){}
}