<?php namespace psm;
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com


//defines for use in index.php
//define('psm\DEBUG',          TRUE);
//define('psm\DEFAULT_MODULE', 'mysite');
//define('psm\DEFAULT_PAGE',   'home');
//define('psm\MODULE',         'mysite');
//define('psm\PAGE',           'home');


//**************************************************
// DO NOT CHANGE ANYTHING BELOW THIS LINE


// static defines
// ==============
define('psm\INDEX_FILE', TRUE);
define('DIR_SEP', DIRECTORY_SEPARATOR);
define('NEWLINE', "\n"); // new line
define('TAB',     "\t"); // tab
// runtime defines - created after first portal instance is initialized.
// ===============
// PATH_ROOT     - The root path of the website.
// PATH_PORTAL   - The portal framework path. This folder contains portal.php


//TODO: add these
// PORTAL_MODULE - The module name currently being displayed.
// PORTAL_PAGE   - The page name currently being displayed.


// class loader
include(__DIR__.DIR_SEP.'ClassLoader.php');
ClassLoader::registerClassPath('psm', Portal::getLocalPath('portal classes'));

// debug mode
if(defined('psm\DEBUG') && \psm\DEBUG == TRUE) {
	// log to file
	ini_set('log_errors', 'On');
	ini_set('error_log', 'php_errors.log');
	if(file_exists(__DIR__.'/php_error.php')) {
		// php_error library
		require('php_error.php');
		$reportErrors = '\php_error\reportErrors';
		$reportErrors(array(
			'catch_ajax_errors'      => TRUE,
			'catch_supressed_errors' => FALSE,
			'catch_class_not_found'  => FALSE,
			'snippet_num_lines'      => 11,
			'application_root'       => __DIR__,
			'background_text'        => 'PSM',
		));
		unset($reportErrors);
	} else {
		// log to display
		ini_set('display_errors', 'On');
		ini_set('html_errors',    'On');
		error_reporting(E_ALL | E_STRICT);
	}
}


// portal core
class Portal {

	// portal instance
	private static $portal = null;
	// module instances
	private static $modules = array();
	// template engine
	private $engine = NULL;

	// paths
	private static $lPaths = array();
	private static $wPaths = array();

	// page
	private static $pageObj = NULL;
	private static $defaultPage = 'home';
	// action
	private $action = NULL;


	public static function factory($args=array()) {
		if(self::$portal != NULL)
			return self::$portal;
		// no page caching
		\psm\Utils\Utils::NoPageCache();
		// set timezone
		try {
			if(!@date_default_timezone_get())
				@date_default_timezone_set('America/New_York');
		} catch(\Exception $ignore) {}
		// parse args
		if(empty($args)) $args = array();
		if(!is_array($args)) $args = array($args);
		foreach($args as $key => $value) {
			$key = str_replace('_', ' ', $key);
			// default module
			if($key == 'default module' && !defined('psm\DEFAULT_MODULE'))
				define('psm\DEFAULT_MODULE', (string) $value);
			else
			// module to load
			if($key == 'module' && !defined('psm\MODULE'))
				define('psm\MODULE', (string) $value);
			// unknown argument
			else
				echo '<p>Unknown argument! '.$key.' - '.$value.'</p>';
		}

		// load mods.txt
		self::_LoadModules();
		// no modules loaded
		if(count(self::$modules) == 0)
			die('<p>No modules/plugins loaded!</p>');

		self::getModName();
		self::getPage();

		// load portal instance
		$portal = new self();
	}


	// new portal instance
	public function __construct() {
		// portal instance
		if(self::$portal != NULL)
			die('<p>Portal already loaded!</p>');
		self::$portal = $this;
	}


	// destruct portal
	public function __destruct() {
		// render if not already done
		if(!\psm\html\Engine::hasDisplayed())
			self::getEngine()->Display();
		// unload modules
		self::$modules = NULL;
		self::$modules = array();
//		$array = array_keys(self::$modules);
//		foreach($array as $key)
//			unset($modules[$key]);
		// unload engine
		$this->engine = NULL;
		// unload db
		\psm\DB\DB::CloseAll();
		\ob_end_flush();
	}


	// load modules
	protected static function _LoadModules() {
		if(count(self::$modules) == 0)
			self::$modules = \psm\Portal\Module_Loader::LoadModulesTxt(
				self::getLocalPath('root').DIR_SEP.'mods.txt'
			);
		if(count(self::$modules) == 0)
			die('No modules/plugins loaded!');
	}


	// local file paths
	public static function getLocalPath($name, $arg='') {
		return self::getDefaultLocalPath($name, $arg);
	}
	private static function getDefaultLocalPath($name, $arg='') {
		$name = trim(str_replace('_', ' ', $name));
		if(empty($name)) return NULL;
		// website root path
		if($name == 'root')
			return \realpath(__DIR__.DIR_SEP.'..'.DIR_SEP);
		// portal path
		if($name == 'portal')
			return __DIR__;
		// portal classes path
		if($name == 'portal classes')
			return __DIR__.DIR_SEP.'classes';
		// module path
		if($name == 'module' || $name == 'mod')
			return self::getLocalPath('root').DIR_SEP.$arg;
		// module classes path
		if($name == 'module classes' || $name == 'mod class')
			return self::getLocalPath('module', $arg).DIR_SEP.'classes';
		// module pages path
		if($name == 'module pages' || $name == 'mod pages')
			return self::getLocalPath('module', $arg).DIR_SEP.'pages';
		return NULL;
	}


	// web url paths
	public static function getWebPath($name, $arg='') {
		return self::getDefaultWebPath($name, $arg);
	}
	private static function getDefaultWebPath($name, $arg='') {
		$name = trim(str_replace('_', ' ', $name));
		if(empty($name)) return NULL;
		// images
		if($name == 'images' || $name == 'img')
			return '/images/';
		return NULL;
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


	/**
	 * Sets the website title.
	 *
	 *
	 */
	public function setTitle($title) {
//TODO:
	}
	/**
	 * Gets the website title.
	 *
	 * @return string
	 */
	public function getTitle() {
//TODO:
	}


//TODO:
//	/**
//	 * Sets a local or web path.
//	 *
//	 *
//	 */
//	protected function setPath($name, $path) {
//		if(isset($this->paths[$name]))
//			die('Path already set! '.$name.' - '.$path);
//		$this->paths[$name] = $path;
//		define('psm\PATH_'.strtoupper($name), $path);
//	}


//		// new instance if needed
//		if($this->engine == NULL)
//			$this->engine = new html_Engine();
//		return $this->engine;
//	}
//	/**
//	 * Gets the main template engine instance, creating a new one if needed.
//	 *
//	 * @return html_Engine
//	 */
//	public static function getEngine() {
//		$portal = self::getPortal();
//		if(!($portal instanceof Portal))
//			die('<p>Unable to get Portal object!</p>');
//		$engine = $portal->_getEngine();
//		if(!($engine instanceof html_Engine))
//			die('<p>Unable to get Engine object!</p>');
//		return $engine;
//	}


	// get module name
	public static function getModName() {
		// module already defined
		if(defined('psm\MODULE')) return \psm\MODULE;
		// get module from url
		self::setModName(
				\psm\Utils\Vars::getVar('mod', 'str')
		);
		if(defined('psm\MODULE')) return \psm\MODULE;
		// default module define
		if(defined('psm\DEFAULT_MODULE'))
			self::setModName(\psm\DEFAULT_MODULE);
		if(defined('psm\MODULE')) return \psm\MODULE;
		// first listed mod
		if(count(self::$modules) > 0) {
			$mod = reset(self::$modules);
			\psm\Utils\Utils::Validate('psm\Portal\Module', $mod);
			self::setModName(
				$mod->getModName()
			);
		}
		// unknown module
		return NULL;
	}
	private static function setModName($modName) {
		if(empty($modName)) return;
		if(defined('psm\MODULE')) return;
		define(
			'psm\MODULE',
			\psm\Utils\Utils_Files::SanFilename(
				$modName
			)
		);
	}
//	// default module
//	public static function setDefaultModName($modName) {
//		if(empty($modName)) return;
//		if(defined('psm\DEFAULT_MODULE')) return;
//		define(
//			'psm\DEFAULT_MODULE',
//			\psm\Utils\Utils_Files::SanFilename(
//				$modName
//			)
//		);
//	}


	// get page
	public static function getPage() {
		// page already defined
		if(defined('psm\PAGE')) return \psm\PAGE;
		// get page from url
		self::setPage(
			\psm\Utils\Vars::getVar('page', 'str')
		);
		if(defined('psm\PAGE')) return \psm\PAGE;
		// default page define
		if(defined('psm\DEFAULT_PAGE'))
			self::setPage(\psm\DEFAULT_PAGE);
		if(defined('psm\PAGE')) return \psm\PAGE;
		// default page portal var
		self::setPage(self::$defaultPage);
		if(defined('psm\PAGE')) return \psm\PAGE;
		// unknown page
		return '404';
	}
	private static function setPage($page) {
		if(empty($page)) return;
		if(defined('psm\PAGE')) return;
		define(
			'psm\PAGE',
			\psm\Utils\Utils_Files::SanFilename(
				$page
			)
		);
	}
	// default page
	public static function setDefaultPage($defaultPage) {
		self::$defaultPage =
			\psm\Utils\Utils_Files::SanFilename(
				$defaultPage
			);
	}


	// get page object
	public static function getPageObj() {
		if(self::$pageObj == NULL)
			self::$pageObj = \psm\Portal\Page::LoadPage(\psm\MODULE, self::getPage());
		return self::$pageObj;
	}


	// action
	public function getAction() {
		// already set
		if($this->action !== NULL)
			return $this->action;
		// get action
		$this->action = \psm\Utils\Vars::getVar('action', 'str');
		$this->action = \psm\Utils\Utils_Files::SanFilename($this->action);
		return $this->action;
	}


}
?>