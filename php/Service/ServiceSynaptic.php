<?php namespace Surikat\Service;
use Surikat\Core\Dev;
use Surikat\Core\SCSSCServer;
use Surikat\Core\SCSSC;
use Surikat\Core\HTTP;
use Surikat\Core\FS;
use Surikat\Tool\Min\JS;
use Surikat\Tool\Min\CSS;
class ServiceSynaptic {
	static function method(){
		if(isset($_GET['file']))
			self::load($_GET['file']);
	}
	protected static $expires = 2592000;
	protected static $allowedExtensions = ['css','js','jpg','jpeg','png','gif'];
	protected static function load($k,$from=null){
		$extension = strtolower(pathinfo($k,PATHINFO_EXTENSION));
		if(!in_array($extension,self::$allowedExtensions)){
			HTTP::code(403);
			exit;
		}
		switch($extension){
			case 'js':
				if(is_file($k)){
					header('Expires: '.gmdate('D, d M Y H:i:s', time()+static::$expires).'GMT');
					header('Content-Type: application/javascript; charset:utf-8');
					readfile($k);
				}
				elseif(substr($k,-7,-3)=='.min'){
					$kv = (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'?'https':'http').'://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']&&(int)$_SERVER['SERVER_PORT']!=80?':'.$_SERVER['SERVER_PORT']:'').'/'.substr($k,0,-7).'.js';
					self::minifyJS($kv,$k);
				}
				else{
					HTTP::code(404);
					throw new Exception('404');
				}
			break;
			case 'css':
				if(is_file($k)){
					header('Expires: '.gmdate('D, d M Y H:i:s', time()+static::$expires).'GMT');
					header('Content-Type: text/css; charset:utf-8');
					if(!$from||strpos($k,'://')!==false||($dir1=dirname($k))==($dir2=dirname($from)))
						readfile($k);
					else{
						$xd1 = explode('/',$dir1);
						$xd2 = explode('/',$dir2);
						$c = count($xd1);
						for($i=0;$i<$c;$i++)
							if(!(isset($xd2[$i])&&$xd1[$i]==$xd2[$i]))
								break;
						$dir1 = '';
						for($i=$i;$i<$c;$i++)
							$dir1 .= $xd1[$i];
						$relativity = rtrim(str_repeat('../',substr_count($k,'/')-$i).$dir1,'/').'/';
						echo "/* SynapticURI $k => $from: $relativity\r\n */";
						echo preg_replace('#url\((?!\s*[\'"]?(?:https?:)?//)\s*([\'"])?#',"url($1{$relativity}",file_get_contents($k));
					}
				}
				elseif(substr($k,-8,-4)=='.min')
					self::minifyCSS(substr($k,0,-8).'.css');
				elseif(
					is_file(dirname($k).'/'.pathinfo($k,PATHINFO_FILENAME).'.scss')
					||(($k=basename(SURIKAT_SPATH).'/'.$k)&&is_file(dirname($k).'/'.pathinfo($k,PATHINFO_FILENAME).'.scss'))
				){
					if(self::scss($k)===false){
						HTTP::code(404);
						throw new Exception('404');
					}
				}
				else{
					HTTP::code(404);
					throw new Exception('404');
				}
			break;
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
				if(isset($_GET['code'])&&($_GET['code']=='404'||$_GET['code']=='403'))
					HTTP::code((int)$_GET['code']);
				header('Content-Type:image/'.$extension.'; charset=utf-8');
				if(is_file(SURIKAT_PATH.$k))
					$file = SURIKAT_PATH.$k;
				elseif(is_file(SURIKAT_SPATH.$k))
					$file = SURIKAT_SPATH.$k;
				else
					HTTP::code(404);
				readfile($file);
			break;
		}
	}
	static function cleanMini($ext=null){
		$f = SURIKAT_PATH.'.tmp/synaptic/min-registry.txt';
		if(!is_file($f))
			return;
		foreach(file($f) as $file){
			$file = trim($file);
			if(empty($file))
				continue;
			if($ext&&$ext!=pathinfo($file,PATHINFO_EXTENSION))
				continue;
			$file = realpath($file);
			if($file)
				unlink($file);
		}
		unlink($f);
	}
	protected static function registerMini($min){
		$f = SURIKAT_PATH.'.tmp/synaptic/min-registry.txt';
		FS::mkdir($f,true);
		file_put_contents($f,$min."\n",FILE_APPEND|LOCK_EX);
	}
	protected static function minifyJS($f,$min){
		if(strpos($f,'://')===false&&!is_file($f))
			return false;
		set_time_limit(0);
		$c = JS::minify(file_get_contents($f));
		if(!Dev::has(Dev::JS)){
			FS::mkdir($min,true);
			self::registerMini($min);
			file_put_contents($min,$c,LOCK_EX);
		}
		if(!headers_sent())
			header('Content-Type:application/javascript; charset=utf-8');
		echo $c;
	}
	protected static function minifyCSS($f){
		if(!is_file($f)
			&&!is_file($f=dirname($f).'/'.pathinfo($f,PATHINFO_FILENAME).'.scss')
			&&!is_file($f=SURIKAT_SPATH.dirname($f).'/'.pathinfo($f,PATHINFO_FILENAME).'.scss')
			&&!is_file($f=SURIKAT_SPATH.dirname($f).'/'.basename($f))
		)
			return false;
		$e = pathinfo($f,PATHINFO_EXTENSION);
		if($e=='scss'){
			ob_start();
			self::scss($f);
			$c = ob_get_clean();
		}
		else
			$c = file_get_contents($f);
		$c = CSS::minify($c);
		if(!Dev::has(Dev::CSS)){
			$min = dirname($f).'/'.pathinfo($f,PATHINFO_FILENAME).'.min.css';
			FS::mkdir($min,true);
			self::registerMini($min);
			file_put_contents($min,$c,LOCK_EX);
		}
		if(!headers_sent())
			header('Content-Type:text/css; charset=utf-8');
		echo $c;
	}
	protected static function scss($path) {
		set_time_limit(0);
		SCSSC::$allowImportCSS = true;
		SCSSC::$allowImportRemote = true;
		$server = new SCSSCServer(dirname($path));
		$server->serve(pathinfo($path,PATHINFO_FILENAME).'.scss');
	}
}