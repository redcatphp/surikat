<?php
namespace RedBase;
class Facade{
	protected static $redbase;
	protected static $redbaseCurrent;
	static $useUnitDi = true;
	static function _initialiaze(){
		if(!isset(self::$redbase)){
			if(class_exists('Unit\Di')&&self::$useUnitDi){
				self::$redbase = \Unit\Di::getInstance()->create('RedBase\RedBase');
				if(isset(self::$redbase[0]))
					self::selectDatabase(0);
			}
			else{
				self::$redbase = new RedBase();
			}
		}
	}
	static function setup($dsn = null, $user = null, $password = null, $config = []){
		if(is_null($dsn))
			$dsn = 'sqlite:/'.sys_get_temp_dir().'/redbase.db';
		self::addDatabase(0, $dsn, $username, $password, $config);
		self::selectDatabase(0);
		return self::$redbase;
	}
	static function addDatabase($key,$dsn,$user=null,$password=null,$config=[]){
		self::$redbase[$key] = [
			'dsn'=>$dsn,
			'user'=>$user,
			'password'=>$password,
		]+$config;
		if(!isset(self::$redbaseCurrent))
			self::selectDatabase($key);
	}
	static function selectDatabase($key){
		return self::$redbaseCurrent = self::$redbase[$key];
	}
	static function __callStatic($f,$args){
		self::_initialiaze();
		if(!isset(self::$redbaseCurrent))
			throw new Exception('Use '.__CLASS__.'::setup() first');
		return call_user_func_array([self::$redbaseCurrent,$f],$args);
	}
	
	static function create($type,$obj){
		self::$redbaseCurrent[$type][] = $obj;
	}
	static function read($type,$id){
		return self::$redbaseCurrent[$type][$id];
	}
	static function update($type,$id,$obj){
		self::$redbaseCurrent[$type][$id] = $obj;
	}
	static function delete($type,$id){
		unset(self::$redbaseCurrent[$type][$id]);
	}
	
	static function setEntityClassPrefix($entityClassPrefix='Model\\'){
		return self::$redbase->setEntityClassPrefix($entityClassPrefix);
	}
	static function appendEntityClassPrefix($entityClassPrefix){
		return self::$redbase->appendEntityClassPrefix($entityClassPrefix);
	}
	static function prependEntityClassPrefix($entityClassPrefix){
		return self::$redbase->prependEntityClassPrefix($entityClassPrefix);
	}
	static function setEntityClassDefault($entityClassDefault='stdClass'){
		return self::$redbase->setEntityClassDefault($entityClassDefault);
	}
	static function setPrimaryKeyDefault($primaryKeyDefault='id'){
		return self::$redbase->setPrimaryKeyDefault($primaryKeyDefault);
	}
	static function setUniqTextKeyDefault($uniqTextKeyDefault='uniq'){
		return self::$redbase->setUniqTextKeyDefault($uniqTextKeyDefault);
	}
}
Facade::_initialiaze();