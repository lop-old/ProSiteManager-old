<?php namespace psm\Portal;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';} else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class Module {


	public abstract function getVersion();


	public function __construct() {
	}


	protected function registerClassPath() {
		\psm\ClassLoader::registerClassPath($this->getName(), __DIR__.DIR_SEP.'classes');
	}








//	// local file paths
//	public static function getLocalPath($name, $arg='') {
//		return self::getDefaultLocalPath($name, $arg);
//	}
//	private static function getDefaultLocalPath($name, $arg='') {
//		if(empty($name)) return NULL;
//		// website root path
//		if($name == 'root')
//			return \realpath(__DIR__.DIR_SEP.'..'.DIR_SEP);
//		// portal path
//		if($name == 'portal')
//			return __DIR__;
//		// module path
//		if($name == 'module' || $name == 'mod')
//			if(!empty($this->module))
//			return self::getLocalPath('root').DIR_SEP.$arg;
//		return NULL;
//	}
//	// web url paths
//	public static function getWebPath($name, $arg='') {
//		return self::getDefaultWebPath($name, $arg);
//	}
//	private static function getDefaultWebPath($name, $arg='') {
//		if(empty($name)) return NULL;
//		// images
//		if($name == 'images' || $name == 'img')
//			return '/images/';
//		return NULL;
//	}










}
?>