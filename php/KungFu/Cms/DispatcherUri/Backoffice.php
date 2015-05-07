<?php namespace KungFu\Cms\DispatcherUri;
use Unit\Dispatcher\Uri as Dispatcher_Uri;
class Backoffice extends Dispatcher_Uri{
	public $pathFS = 'plugin/backoffice';
	function __construct(){
		$this->Authentic_Session->setName('surikat_backoffice');
		$this
			->append(['Unit_Route_Extension','css|js|png|jpg|jpeg|gif'],
						['KungFu_Cms_DispatcherUri_Synaptic',$this->pathFS])
			->append(['Unit_Route_ByTml','',$this->pathFS],function(){
				$this->Authentic_Auth->lockServer($this->Authentic_Auth->constant('RIGHT_MANAGE'));
				return $this->Unit_Mvc_Controller();
			})
			->append(['Unit_Route_ByPhpX','',$this->pathFS],function($paths){
				$this->Authentic_Auth->lockServer($this->Authentic_Auth->constant('RIGHT_MANAGE'));
				list($dir,$file,$adir,$afile) = $paths;
				chdir($adir);
				include $file;
			})
		;
	}
	function __invoke(){
		\ObjexLoader\Container::get()
			->Unit_AutoloadPsr4
			->addNamespace('',SURIKAT.$this->pathFS.'/php')
		;
		return $this->run(func_get_arg(0));
	}
}