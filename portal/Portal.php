<?php namespace psm;
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com


//defines for use in index.php
//define('psm\DEBUG',			TRUE);
//define('psm\DEMO',			TRUE);
//define('psm\DEFAULT_MODULE',	'mysite');
//define('psm\DEFAULT_PAGE',	'home');


//**************************************************
// DO NOT CHANGE ANYTHING BELOW THIS LINE


// static constants
define('PORTAL_INDEX_FILE', TRUE);
define('DIR_SEP', DIRECTORY_SEPARATOR);
define('NEWLINE', "\n"); // new line
define('TAB', "\t"); // tab

// class loader
include('ClassLoader.php');
ClassLoader::registerClassPath('psm', __DIR__.DIR_SEP.'classes');

// debug mode
if(defined('psm\DEBUG')) {
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


class Portal {

	// portal core
	private static $portal = NULL;
	private $portalName;
	// template engine
	private $engine = NULL;

	// paths
	private $root;

	// page
	private $page = NULL;
	private $defaultPage = 'home';
	// action
	private $action = NULL;


	// new portal
	public function __construct($portalName) {
		// portal instance
		if(self::$portal != NULL) {
			echo '<p>Portal already loaded!</p>';
			exit();
		}
		self::$portal = $this;
		// portal name
		if(empty($portalName)) {
			echo '<p>portalName not set!</p>';
			exit();
		}
		$this->portalName = $portalName;
		// paths
		$this->root = realpath(__DIR__.'/../');
		// no page caching
		Utils::NoPageCache();
		// set timezone
		try {
			if(!@date_default_timezone_get())
				@date_default_timezone_set('America/New_York');
		} catch(\Exception $ignore) {}
		// load portal index
		$portalIndex = $this->root.'/'.$this->portalName.'/'.$this->portalName.'.php';
		include($portalIndex);
	}


	/**
	 *
	 *
	 */
	public function genericRender() {
		// load page
		$pageObj = Page::LoadPage($this->getPage());
		// failed to load
		if($pageObj == NULL) {
echo '<p>PAGE IS NULL</p>';
			return;
		}
		// get engine
		$engine = $this->getEngine();
		if($engine == NULL) {
echo '<p>ENGINE IS NULL</p>';
			return;
		}
		$engine->addToPage($pageObj);
		$engine->Display();
	}


	// get portal instance
	public static function getPortal() {
		return self::$portal;
	}


	// get portal name
	public function getPortalName() {
		return $this->portalName;
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


	/**
	 * Gets the main template engine instance, creating a new one if needed.
	 *
	 * @return html_Engine
	 */
	public static function getEngine() {
		return html_Engine::getEngine();
	}
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


	// get root path
	public function getRoot() {
		return $this->root;
	}


	// page
	public function getPage() {
		// already set
		if($this->page !== NULL)
			return $this->page;
		// get page
		$this->page = Vars::getVar('page', 'str');
		// default page
		if(empty($this->page))
			$this->page = $this->defaultPage;
		$this->page = Utils_File::SanFilename($this->page);
		return $this->page;
	}
	// default page
	public function setDefaultPage($defaultPage) {
		$this->defaultPage = Utils_File::SanFilename($defaultPage);
	}


	// action
	public function getAction() {
		// already set
		if($this->action !== NULL)
			return $this->action;
		// get action
		$this->action = Vars::getVar('action', 'str');
		$this->action = Utils_File::SanFilename($this->action);
		return $this->action;
	}


}
?>