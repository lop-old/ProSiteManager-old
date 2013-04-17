<?php namespace {
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com
define('psm\\VERSION', '3.0.5');

// static defines
if(defined('psm\\INDEX_FILE')) {
	\psm\Portal::Error('Portal.php already included?'); exit();}
define('psm\\INDEX_FILE', TRUE);
define('DIR_SEP', DIRECTORY_SEPARATOR);
define('NEWLINE', "\n"); // new line
define('TAB',     "\t"); // tab

// class loader hook
function __autoload($classname) {
	\psm\Loader::AutoLoad($classname);
}

// load debuggers
ini_set('log_errors', 'On');
ini_set('error_log', 'php_error.log');
if(defined('psm\\DEBUG') && \psm\DEBUG === TRUE) {
	// log to display
	ini_set('display_errors', 'On');
	ini_set('html_errors',    'On');
	error_reporting(E_ALL | E_STRICT);
	// Kint backtracer
	$kintPath = \psm\Paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'kint'.DIR_SEP.'Kint.class.php';
	if(file_exists($kintPath))
		include($kintPath);
	// php_error
	$phpErrorPath = \psm\Paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'php_error.php';
	if(file_exists($phpErrorPath))
		include($phpErrorPath);
	if(function_exists('php_error\\reportErrors')) {
		$reportErrors = '\\php_error\\reportErrors';
		$reportErrors(array(
			'catch_ajax_errors'      => TRUE,
			'catch_supressed_errors' => FALSE,
			'catch_class_not_found'  => FALSE,
			'snippet_num_lines'      => 11,
			'application_root'       => __DIR__,
			'background_text'        => 'PSM',
		));
	}
} else {
	// log to display
	ini_set('display_errors', 'Off');
}
function dump($var) {
	\var_dump($var);
}


// portal namespace
} namespace psm {


// class count
global $ClassCount; $ClassCount = 1;
function getClassCount() {
	global $ClassCount;
	return $ClassCount;
}
// class loader
final class Loader {
	private function __construct() {}

	// class paths array
	private static $paths = array();


	/**
	 * Adds a path used when searching for a class to load.
	 *
	 * @param string $name Namespace to search for.
	 * @param string $path Path to the classes.
	 */
	public static function registerClassPath($name, $path) {
		self::$paths[((string) $name)] = ((string) $path);
	}


	/**
	 * Pass onto this function from __AutoLoad().
	 *
	 * @param string $class_name Argument passed on from __autoload().
	 * @return boolean True if a class was found.
	 */
	public static function AutoLoad($classname) {
		$classname = (string) $classname;
		if(\count(self::$paths) == 0)
			self::registerClassPath('psm', __DIR__);
		$parts = \explode('\\', $classname);
		if(\count($parts) < 2) {
			echo '<p>Unknown class: '.$classname.'</p>';
			return;
		}
		// protected files
		if($parts[0] == 'static') {
			\psm\Portal::Error('static namespace is protected!');
			return FALSE;
		}
		// get namespace\class
		$classname = \array_pop($parts);
		$namespace = \implode('\\', $parts);
		$root_namespace = \array_shift($parts);
		$classpath = \implode(DIR_SEP, $parts);
		if(!empty($classpath))
			$classpath .= DIR_SEP;
		// namespace path
		if(\array_key_exists($root_namespace, self::$paths)) {
			// class file
			$filepath = self::$paths[$root_namespace].DIR_SEP.$classpath.$classname.'.class.php';
			if(\file_exists($filepath)) {
				try {
					include($filepath);
					return TRUE;
				} catch (\Exception $ignore) {}
			}
		}
		//echo '<p style="color: red;">Unknown class: '.(empty($namespace)?'':$namespace.'\\').$classname.'</p>';
		\psm\Portal::Error('Unknown class: '.
			( empty($namespace) ? '?' : $namespace.'\\').$classname );
		return FALSE;
	}


	// auto init module
	public static function AutoLoad_Module() {
		// load page
		\psm\Portal::LoadPage();
		\psm\Portal::LoadAction();
		// display page
		\psm\Portal::getEngine()->Display();
	}


	/**
	 * Loads a page class.
	 *     default path - <mod>/pages/<page>.page.php
	 * @param string $page Name of the page to load.
	 * @return page Returns the page class instance, which can be rendered.
	 */
	public static function AutoLoad_Page($modName, $page) {
		$page = \psm\Utils\DirsFiles::SanFilename($page);
		// find page file
		$paths = \psm\Paths::getLocal('pages', $modName);
		$filepath = \psm\Utils\DirsFiles::FindFile($page.'.page.php', $paths);
		// page not found
		//TODO:
		if(empty($filepath)) {
			\psm\Portal::Error('Page not found!! '.$page);
			return;
		}
		// load file
		$result = include($filepath);
		// file failed to load
		//TODO:
		if($result === FALSE) {
			\psm\Portal::Error('Failed to load page!! '.$page);
			return;
		}
		// module name
		$modName = \psm\Portal::getModName();
		// load page class
		$clss = $modName.'\\Pages\\page_'.$page;
		if(\class_exists($clss))
			return new $clss();
		// string result
		return (string) $result;
	}


}
}
?>