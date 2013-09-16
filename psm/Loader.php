<?php namespace {
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com
define('psm\\VERSION', '3.0.11');

// static defines
if(defined('psm\\INDEX_FILE')) {
	\psm\Portal::Error('Portal.php already included?'); exit();}
define('psm\\INDEX_FILE', TRUE);
define('DIR_SEP', DIRECTORY_SEPARATOR);
define('NEWLINE', "\n"); // new line
define('TAB',     "\t"); // tab

// class loader hook
function __autoload($classname) {
	\psm\Loader::AutoLoadClass($classname);
}

// load debuggers
\ini_set('log_errors', 'On');
\ini_set('error_log',  'php_error.log');
if(defined('psm\\DEBUG') && \psm\DEBUG === TRUE) {
	// log to display
	\ini_set('display_errors', 'On');
	\ini_set('html_errors',    'On');
	error_reporting(E_ALL | E_STRICT);
	// Kint backtracer
	$kintPath = \psm\Paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'kint'.DIR_SEP.'Kint.class.php';
	if(file_exists($kintPath)) {
//global $GLOBALS;
//if(!@is_array(@$GLOBALS)) $GLOBALS = array();
//$_kintSettings = &$GLOBALS['_kint_settings'];
//$_kintSettings['traceCleanupCallback'] = function($traceStep) {
//echo '<pre>';print_r($traceStep);exit();
//	if(isset($traceStep['class']) && $traceStep['class'] === 'Kint')
//		return null;
//	if(isset($traceStep['function']) && \strtolower($traceStep['function']) === '__tostring')
//		$traceStep['function'] = '[object converted to string]';
//	return $traceStep;
//};
//echo '<pre>';print_r($_kintSettings);exit();
		include($kintPath);
	}
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
	\ini_set('display_errors', 'Off');
}
function dump($var) {
	d($var);
}
if(!function_exists('d')) {
	function d($var) {
		\var_dump($var);
	}
}
if(!function_exists('dd')) {
	function dd($var) {
		d($var);
		die();
	}
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
class Loader {
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
	public static function AutoLoadClass($classname) {
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


}
}
?>