<?php namespace psm\Portal;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
final class ModuleLoader {
	private function __construct() {}

	// selected module;
	private static $moduleName = NULL;
	// module instances
	private static $modules = array();


	// load mods.txt modules list
	public static function &LoadModulesTxt($modsFile) {
		\psm\Utils\Strings::forceEndsWith($modsFile, '.txt');
		// mods.txt file not found
		if(!\file_exists($modsFile))
			\psm\Portal::Error('Modules list file not found!');
		$data = \file_get_contents($modsFile);
		$array = \explode("\n", $data);
		foreach($array as $line) {
			$line = \trim($line);
			if(empty($line)) continue;
			// already loaded
			if(isset(self::$modules[$line])) continue;
			// load module
			$mod = self::LoadModule($line);
			// failed to load
			if($mod == null) continue;
			// mod loaded successfully
			self::$modules[$line] = $mod;
		}
		return self::$modules;
	}
	// load a module
	private static function LoadModule($name) {
		// file mod/mod.php
		$file = \psm\Paths::getLocal('module', $name).DIR_SEP.$name.'.php';
		// module file not found
		if(!\file_exists($file)) {
//TODO:
			echo '<p>Module file not found! '.$name.'</p>';
			return;
		}
		include $file;
		// class \mod\module_mod
		$clss = $name.'\\module_'.$name;
		// class not found
		if(!\class_exists($clss)) {
//TODO:
			echo '<p>Module class not found! '.$clss.'</p>';
			return;
		}
		return new $clss();
	}


	// selected module name
	public static function getModuleName() {
		// already set
		if(!empty(self::$moduleName))
			return self::$moduleName;
		// mod from url  ?mod=
		if(self::setModule(\psm\Utils\Vars::getVar('mod', 'str')))
			return self::$moduleName;
//TODO: this needs to check against an array of domains
		// mod from hostname  mod.domain.com
		foreach(self::$modules as $name => $mod) {
echo '<p>'.$_SERVER['SERVER_NAME'].' - '.$name.'</p>';
			if(\psm\Utils\Strings::StartsWith($_SERVER['SERVER_NAME'], $name, TRUE))
				if(self::setModule($name))
					return self::$moduleName;
		}
		// mod from define  \psm\MODULE
		if(defined('psm\\MODULE'))
			if(self::setModule(\psm\MODULE))
				return self::$moduleName;
		// default mod
		if(defined('psm\\DEFAULT_MODULE'))
			if(self::setModule(\psm\DEFAULT_MODULE))
				return self::$moduleName;
		// first available
		if(count(self::$modules) > 0)
			if(self::setModule(\reset(self::$modules)))
				return self::$moduleName;
		// every last hope has failed
		return NULL;
	}
	private static function setModule($name) {
		if(empty($name))
			return FALSE;
		self::$moduleName = $name;
		return TRUE;
	}


	// selected module instance
	public static function getModule() {
		$name = self::getModuleName();
		if(isset(self::$modules[$name]))
			return self::$modules[$name];
		return NULL;
	}


}
?>