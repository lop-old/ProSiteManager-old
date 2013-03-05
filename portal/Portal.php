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
if(defined('psm\INDEX_FILE'))
	die('<p>Portal.php already included?</p>');
define('psm\INDEX_FILE', TRUE);
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
if(defined('psm\DEBUG') && \psm\DEBUG == TRUE) {
	error_reporting(E_ALL | E_STRICT);
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
		@\ob_end_flush();
	}


	// load modules
	protected static function _LoadModules() {
		if(count(self::$modules) == 0)
			self::$modules = \psm\Portal\Module_Loader::LoadModulesTxt(
				\psm\Paths::getLocal('root').DIR_SEP.'mods.txt'
			);
		if(count(self::$modules) == 0)
			die('No modules/plugins loaded!');
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
	 * Gets the portal theme.
	 *
	 *
	 */
	public static function getPortalTheme() {
		return 'default';
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
	public static function GetRenderTime() {
		return '1.111';
	}


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
	public static function getModObj($modName='') {
		if(empty($modName))
			$modName = self::getModName();
		if(!isset(self::$modules[$modName]))
			return NULL;
		return self::$modules[$modName];
	}


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