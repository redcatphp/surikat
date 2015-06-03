<?php
namespace KungFu\TemplixPlugin;
use Unit\Url;
use InterNative\Translator;
use Unit\Di;
class TemplixL10n extends Templix{
	protected $Templix;
	protected $Translator;
	protected $Url;
	function __construct($file=null,$vars=null,Di $di
						,Url $Url=null,Translator $Translator=null, $server=null
	){
		parent::__construct($file,$vars,$di);
		$this->di = $di;
		if(!$server)
			$server = &$_SERVER;
		$this->Url = $Url;
		$this->Translator = $Translator;
		$this->server = $server;
	}
	function __invoke($file){
		list($lang,$langMap,$file) = (array)$file;
		if(is_array($file)){
			list($hook,$file) = (array)$file;
			$this->setDirCwd([$hook.'/','Surikat/'.$hook.'/']);
		}
		
		$this->Translator->set($lang);
		$this->setDirCompileSuffix('.'.$lang.'/');
		$this->onCompile(function($TML)use($lang,$file,$langMap){
			$this->i18nGettext($TML);
			$this->i18nRel($TML,$lang,$file,$langMap);
			if($langMap){
				foreach($TML('a[href]') as $a){
					if(strpos($a->href,'://')===false&&strpos($a->href,'javascript:')!==0&&strpos($a->href,'#')!==0){
						if(($k=array_search($a->href,$langMap))!==false)
							$a->href = $k;
					}
				}
			}
		});
		
		
		return $this->query($file);
	}
	function i18nGettext($Tml,$cache=true){
		$Tml->prepend('<?php include SURIKAT.\'php/InterNative/__.php\'; ?>');
		$Tml('html')->attr('lang',$this->Translator->getLangCode());
		$Tml('*[ni18n] TEXT:hasnt(PHP)')->data('i18n',false);
		$Tml('*[i18n] TEXT:hasnt(PHP)')->each(function($el)use($cache){
			$rw = "$el";
			$l = strlen($rw);
			$left = $l-strlen(ltrim($rw));
			$right = $l-strlen(rtrim($rw));
			if($left)
				$left = substr($rw,0,$left);
			else
				$left = '';
			if($right)
				$right = substr($rw,-1*$right);
			else
				$right = '';
			$rw = trim($rw);
			if(!$rw)
				return;
			if($el->data('i18n')!==false){
				if($cache){
					$rw = $this->Translator->__($rw);
				}
				else{
					$rw = str_replace("'","\'",$rw);
					$rw = '<?php echo __(\''.$rw.'\');?>';
				}
				$el->write($left.$rw.$right);
			}
		});
		$Tml('*')->each(function($Tml){
			foreach($Tml->attributes as $k=>$v){
				if(strpos($k,'i18n-')===0){
					$Tml->removeAttr($k);
					$Tml->attr(substr($k,5),$this->Translator()->__($v));
				}
			}
		});
		$Tml('*[i18n]')->removeAttr('i18n');
	}
	function i18nRel($Tml,$lang,$path,$langMap=null){
		$head = $Tml->find('head',0);
		if(!$head)
			return;
		
		
		if(!isset($langMap)&&file_exists($langFile='langs/'.$lang.'.ini')){
			$langMap = parse_ini_file($langFile);
		}
		$xPath = $path;
		if($langMap&&isset($langMap[$path])){
			$xPath = $langMap[$path];
		}
		$head->append('<link rel="alternate" href="'.$this->getSubdomainHref().$xPath.'" hreflang="x-default" />');
		foreach(glob('langs/*.ini') as $langFile){
			$lg = pathinfo($langFile,PATHINFO_FILENAME);
			$langMap = parse_ini_file($langFile);
			$lcPath = ($k=array_search($xPath,$langMap))?$k:$xPath;
			$head->append('<link rel="alternate" href="'.$this->getSubdomainHref($lg).$lcPath.'" hreflang="'.$lg.'" />');
		}
	}
	
	function setBaseHref($href){
		$this->baseHref = $href;
	}
	function getProtocolHref(){
		return 'http'.(@$this->server["HTTPS"]=="on"?'s':'').'://';
	}
	function getServerHref(){
		return $this->server['SERVER_NAME'];
	}
	function getPortHref(){
		$ssl = @$this->server['HTTPS']==='on';
		return @$this->server['SERVER_PORT']&&((!$ssl&&(int)$this->server['SERVER_PORT']!=80)||($ssl&&(int)$this->server['SERVER_PORT']!=443))?':'.$this->server['SERVER_PORT']:'';
	}
	function getBaseHref(){
		if(!isset($this->baseHref)){
			$this->setBaseHref($this->getProtocolHref().$this->getServerHref().$this->getPortHref().'/');
		}
		return $this->baseHref.$this->getSuffixHref();
	}
	function setSuffixHref($href){
		$this->suffixHref = $href;
	}
	function getSuffixHref(){
		if(!isset($this->suffixHref)){
			if(isset($this->server['SURIKAT_URI'])){
				$this->suffixHref = ltrim($this->server['SURIKAT_URI'],'/');				
			}
			else{
				$docRoot = $this->server['DOCUMENT_ROOT'].'/';
				//$docRoot = dirname($this->server['SCRIPT_FILENAME']).'/';
				if(defined('SURIKAT_CWD'))
					$cwd = SURIKAT_CWD;
				else
					$cwd = getcwd();
				if($docRoot!=$cwd&&strpos($cwd,$docRoot)===0)
					$this->suffixHref = substr($cwd,strlen($docRoot));
			}
		}
		return $this->suffixHref;
	}
	function getSubdomainHref($sub=''){
		$lg = $this->getSubdomainLang();
		$server = $this->getServerHref();
		if($lg)
			$server = substr($server,strlen($lg)+1);
		if($sub)
			$sub .= '.';
		return $this->getProtocolHref().$sub.$server.$this->getPortHref().'/'.$this->getSuffixHref();
	}
	function getSubdomainLang($domain=null){
		if(!isset($domain))
			$domain = $this->getServerHref();
		$urlParts = explode('.', $domain);
		if(count($urlParts)>2&&strlen($urlParts[0])==2)
			return $urlParts[0];
		else
			return null;
	}
	function getLocation(){
		return $this->getBaseHref().ltrim($this->server['REQUEST_URI'],'/');
	}
}