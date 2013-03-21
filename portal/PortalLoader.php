<?php namespace psm;
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com


// defines for use in index.php
//define('psm\DEBUG',          TRUE);
//define('psm\DEFAULT_MODULE', 'mysite');
//define('psm\DEFAULT_PAGE',   'home');
//define('psm\MODULE',         'mysite');
//define('psm\PAGE',           'home');


//**************************************************
// DO NOT CHANGE ANYTHING BELOW THIS LINE


// static defines
// ==============
if(defined('psm\\INDEX_FILE'))
	\psm\Portal::Error('Portal.php already included?');
define('psm\\INDEX_FILE', TRUE);
define('DIR_SEP', DIRECTORY_SEPARATOR);
define('NEWLINE', "\n"); // new line
define('TAB',     "\t"); // tab
// runtime defines - created after first portal instance is initialized.
// ===============
// \psm\MODULE - The module name currently being displayed.
// \psm\PAGE   - The page name currently being displayed.


// path handler
include(__DIR__.DIR_SEP.'Paths.class.php');
// class loader
include(__DIR__.DIR_SEP.'ClassLoader.php');
ClassLoader::registerClassPath('psm', \psm\Paths::getLocal('portal classes'));


// debug mode
ini_set('log_errors', 'On');
ini_set('error_log', 'php_error.log');
if(defined('psm\\DEBUG') && \psm\DEBUG === TRUE) {
	// log to display
	ini_set('display_errors', 'On');
	ini_set('html_errors',    'On');
	error_reporting(E_ALL | E_STRICT);
	// Kint backtracer
	if(file_exists(\psm\Paths::getLocal('portal').DIR_SEP.'kint.php')) {
		include(\psm\Paths::getLocal('portal').DIR_SEP.'kint.php');
	}
	// php_error
	if(file_exists(\psm\Paths::getLocal('portal').DIR_SEP.'php_error.php')) {
		include(\psm\Paths::getLocal('portal').DIR_SEP.'php_error.php');
	}
} else {
	// log to display
	ini_set('display_errors', 'Off');
}





//echo '<pre>';print_r($_SERVER);exit();





// portal loader
final class PortalLoader {
	private function __construct() {}

	// portal instance
	private static $portal = null;


	public static function factory() {
// World Groups
//$a = array(
//	'Survival' => array('world', 'pvp'),
//	'Creative' => array('clean'),
//);
//$data = json_encode($a);
//$orig = $data;
//echo $orig.'<br />';
//
//$data = str_replace(
//	array('{', '}', ':', ',' ),
//	array('' , '' , ' ', ', '),
//	$data);
//
//echo $data.'<br />';
//
//$data = str_replace(
//	array(', ', ' '),
//	array(',' , ':'),
//	$data);
//$data = '{'.$data.'}';
//
//echo $data.'<br />';
//echo '<br />'.($data == $orig);
//exit();

		if(self::$portal != NULL)
			return self::$portal;
		// no page caching
		\psm\Utils\Utils::NoPageCache();
		// set timezone
		try {
			if(!@date_default_timezone_get())
				@date_default_timezone_set('America/New_York');
		} catch(\Exception $ignore) {}
		// parse portal args
		if(empty($args)) $args = array();
		if(!is_array($args)) $args = array($args);
		foreach($args as $key => $value) {
			$key = str_replace('_', ' ', $key);
			// default module
			if($key == 'default module' && !defined('psm\\DEFAULT_MODULE'))
				define('psm\\DEFAULT_MODULE', (string) $value);
			else
			// module to load
			if($key == 'module' && !defined('psm\\MODULE'))
				define('psm\\MODULE', (string) $value);
			// unknown argument
			else
				echo '<p>Unknown argument! '.$key.' - '.$value.'</p>';
		}
		// load mods.txt
		\psm\Portal::_LoadModules();
		\psm\Portal::getModName();
		\psm\Portal::getPage();

		// load portal instance
		self::$portal = new \psm\Portal();
	}
	public static function Unload() {
		self::$portal = null;
		exit();
	}


	/**
	 * Gets the main portal instance
	 *
	 * @return Portal
	 */
	public static function getPortal() {
		return self::$portal;
	}


	/**
	 * Gets the main template engine instance, creating a new one if needed.
	 *
	 * @return html_Engine
	 */
	public static function getEngine() {
		return \psm\html\Engine::getEngine();
	}


}
?>