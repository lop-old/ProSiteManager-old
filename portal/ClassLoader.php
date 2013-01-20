<?php
// class auto loader


// global namespace
namespace {
	// class loader
	function __autoload($classname) {
		\psm\ClassLoader::autoload($classname);
	}
}


// portal namespace
namespace psm {
// class loader handler
class ClassLoader {

	// class paths array
	private static $paths = array();


	/**
	 * Adds a path used when searching for a class to load.
	 *
	 * @param string $name Namespace to search for.
	 * @param string $path Path to the classes.
	 */
	public static function registerClassPath($name, $path) {
		self::$paths[$name] = $path;
	}


	/**
	 * Pass onto this function from __autoload().
	 *
	 * @param string $class_name Argument passed on from __autoload().
	 * @return boolean True if a class was found.
	 */
	public static function autoload($classname) {
		$parts = explode('\\', $classname);
		$namespace = '';
		if(count($parts) > 1) {
			$namespace = $parts[0];
			$classname = end($parts);
		}
		// namespace path
		if(array_key_exists($namespace, self::$paths)) {
			// class file
			$file = self::$paths[$namespace].DIR_SEP.$classname.'.class.php';
			if(file_exists($file)) {
				try {
					include($file);
					return TRUE;
				} catch (\Exception $ignore) {}
			}
		}
		echo '<p style="color: red;">Unknown class: '.(empty($namespace)?'':$namespace.'\\').$classname.'</p>';
		return FALSE;
	}


}
}


?>