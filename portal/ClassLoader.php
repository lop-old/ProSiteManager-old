<?php namespace {
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
// class auto loader


// class loader
function __autoload($classname) {
	\psm\ClassLoader::autoload($classname);
}


// portal namespace
} namespace psm {
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
		if(count($parts) < 2) {
			echo '<p>Unknown class: '.$classname.'</p>';
			return;
		}
		$classname = array_pop($parts);
		$namespace = implode('\\', $parts);
		$root_namespace = array_shift($parts);
		$classpath = implode(DIR_SEP,  $parts);
		if(!empty($classpath))
			$classpath .= DIR_SEP;
		// namespace path
		if(array_key_exists($root_namespace, self::$paths)) {
			// class file
			$filepath = self::$paths[$root_namespace].DIR_SEP.$classpath.$classname.'.class.php';
			if(file_exists($filepath)) {
				try {
					include($filepath);
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