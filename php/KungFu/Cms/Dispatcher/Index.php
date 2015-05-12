<?php namespace KungFu\Cms\Dispatcher;
use Unit\Dispatcher;
use Unit\Route\Extension;
use KungFu\Cms\Route\ByTml;
use KungFu\Cms\Route\L10n as Route_L10n;
use KungFu\Cms\Controller\L10n as Controller_L10n;
use KungFu\Cms\Controller\Templix;
use KungFu\Cms\Service\Service;
class Index extends Dispatcher{
	protected $Templix;
	public $i18nConvention;
	public $backoffice = 'backend/';
	function __construct($config=[]){
		foreach($config as $k=>$v){
			$this->$k = $v;
		}
		$this->append('service/',new Service());
		$this->append(new Extension('css|js|png|jpg|jpeg|gif'),function(){
			return new Synaptic();
		});
		if($this->i18nConvention)
			$this->append(new Route_L10n($this),function(){
				return new L10n();
			});
		$this->append(new ByTml('plugin'),$this);
		$this->append(new ByTml(),$this);
		if($this->backoffice)
			$this->prepend($this->backoffice,function(){
				return new Backoffice();
			});
	}
	function Templix(){
		return $this->Templix?:$this->Templix = new Templix();
	}
	function __invoke(){
		return $this->Templix();
	}
	function run($path){
		if(!parent::run($path)){
			$this->Templix()->error(404);
			exit;
		}
	}
}