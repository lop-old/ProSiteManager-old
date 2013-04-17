<?php namespace psm;
global $ClassCount; $ClassCount++;
final class Paths {
	private function __construct() {}

	// paths cache
	private static $localPaths = array();
	private static $webPaths   = array();


	// get local path
	public static function getLocal($name, $arg='') {
		$name = \trim(\str_replace('_', ' ', $name));
		if(empty($name))
			return NULL;
		return self::_getPath('local', $name, $arg);
	}
	// get web path
	public static function getWeb($name, $arg='') {
		$name = \trim(\str_replace('_', ' ', $name));
		if(empty($name))
			return NULL;
		return self::_getPath('web', $name, $arg);
	}
	// get cached
	private static function _getPath($type, $name, $arg='') {
		if($type == 'local')
			$cache = &self::$localPaths;
		else
		if($type == 'web')
			$cache = &self::$webPaths;
		else
			return NULL;
		// no arg
		if(empty($arg)) {
			// get cached
			if(isset($cache[$name]))
				return $cache[$name];
			// get path
			$path = self::_generate($type, $name);
			// save in cache
			$cache[$name] = $path;
			return $path;
		// has arg
		} else {
			// get cached
			if(isset($cache[$name][$arg]))
				return $cache[$name][$arg];
			// get path
			$path = self::_generate($type, $name, $arg);
			// save in cache
			$cache[$name][$arg] = $path;
			return $path;
		}
	}


	// generate path
	private static function _generate($type, $name, $arg='') {
		if($type == 'local')
			return self::_generateLocal($name, $arg);
		else
		if($type == 'web')
			return self::_generateWeb($name, $arg);
		return NULL;
	}


	// generate local path
	private static function _generateLocal($name, $arg='') {

		// website root path
		if($name == 'root')
			return \realpath(__DIR__.DIR_SEP.'..'.DIR_SEP);

		// portal
		if($name == 'portal')
			return __DIR__;
		// portal classes
		if($name == 'portal classes')
			return self::_getPath('local', 'portal').DIR_SEP.'classes';
		// portal pages
		if($name == 'portal pages')
			return self::_getPath('local', 'portal').DIR_SEP.'pages';
		// portal html
		if($name == 'portal html')
			return self::_getPath('local', 'portal').DIR_SEP.'html';

		// module
		if($name == 'module') {
			if(empty($arg))
				\psm\Portal::Error('Missing argument!');
			return self::_getPath('local', 'root').DIR_SEP.$arg;
		}
		// module classes
		if($name == 'module classes') {
			if(empty($arg))
				\psm\Portal::Error('Missing argument!');
			return self::_getPath('local', 'module', $arg).DIR_SEP.'classes';
		}
		// module pages
		if($name == 'module pages') {
			if(empty($arg))
				\psm\Portal::Error('Missing argument!');
			return self::_getPath('local', 'module', $arg).DIR_SEP.'pages';
		}
		// module html
		if($name == 'module html') {
			if(empty($arg))
				\psm\Portal::Error('Missing argument!');
			return self::_getPath('local', 'module', $arg).DIR_SEP.'html';
		}

		// pages array
		if($name == 'pages') {
			if(empty($arg))
				\psm\Portal::Error('Missing argument!');
			return array(
				self::_getPath('local', 'portal pages'),
				self::_getPath('local', 'module pages', $arg),
			);
		}
		// html array
		if($name == 'html') {
			if(empty($arg))
				\psm\Portal::Error('Missing argument!');
			return array(
				self::_getPath('local', 'portal html'),
				self::_getPath('local', 'module html', $arg),
			);
		}

		return NULL;
	}


	// generate web path
	private static function _generateWeb($name, $arg='') {
		// portal base url
//TODO: this can't be set static
		if($name == 'base')
			return '/mcdev/';
		// images
		if($name == 'images' || $name == 'img')
			return '/images/';
		return NULL;
	}


}
?>