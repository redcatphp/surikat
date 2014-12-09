<?php namespace Surikat\Presenter;
use Surikat\Core\ArrayObject;
use Surikat\View\FILE;
use Surikat\View\TML;
use Surikat\Controller\Application;
use Surikat\Core\Domain;
class Basic extends ArrayObject{
	static function load(TML $tml){
		if(!$tml->TeMpLate)
			return;
		$c = get_called_class();
		$o = new $c();
		$o->merge([
			'templatePath'		=> $tml->TeMpLate?$tml->TeMpLate->path:'',
			'presentAttributes'	=> $tml->getAttributes(),
			'presentNamespaces'	=> $tml->_namespaces,
		]);
		$o->setView($tml->TeMpLate);
		$o->BASE_HREF = Domain::getBaseHref();
		$o->timeCompiled = time();
		$o->assign();
		$head = '<?php if(isset($THIS))$_THIS=$THIS;$THIS=new '.$c.'('.var_export($o->getArray(),true).');';
		$head .= '$THIS->setView($this);';
		$head .= '$THIS->execute();';
		$head .= 'extract((array)$THIS,EXTR_OVERWRITE|EXTR_PREFIX_INVALID,\'i\');?>';
		$tml->head($head);
		if(!empty($tml->childNodes))
			$tml->foot('<?php if(isset($_THIS));extract((array)($THIS=$_THIS),EXTR_OVERWRITE|EXTR_PREFIX_INVALID,\'i\'); ?>');
		$tml->TeMpLate->present = $o;
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
			(new Application())->error(404);
		$this->time = time();
		$this->dynamic();
	}
	function dynamic(){}
}