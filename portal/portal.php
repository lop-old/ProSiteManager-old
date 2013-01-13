<?php namespace psm;
//defines:
//PORTAL_DONT_USE_ERROR_HANDLER


// class loader
include('ClassLoader.php');
ClassLoader::registerClassPath('psm', __DIR__.DIRECTORY_SEPARATOR.'classes');

// debug mode
if(file_exists(__DIR__.'/php_error.php') &&
!(defined('PORTAL_DONT_USE_ERROR_HANDLER') && PORTAL_DONT_USE_ERROR_HANDLER===TRUE) ) {
	error_reporting(E_ALL | E_STRICT);
	// log display
	//ini_set('display_errors', 'On');
	//ini_set('html_errors', 'On');
	// log file
	ini_set('log_errors', 'On');
	ini_set('error_log', 'php_errors.log');
	// error handler
	require('php_error.php');
	\php_error\reportErrors(array(
		'catch_ajax_errors'			=> TRUE,
		'catch_supressed_errors'	=> FALSE,
		'catch_class_not_found'		=> FALSE,
		'snippet_num_lines'			=> 11,
		'application_root'			=> __DIR__,
		'background_text'			=> 'PSM',
	));
}


class portal {

	// portal core
	private static $portal = NULL;
	private $portalName;

	// paths
	private $root;

	// page
	private $page = NULL;
	private $defaultPage = 'home';


	// new portal
	public function __construct($portalName) {
		// index load point
		if(defined('PORTAL_INDEX_FILE')) {
			echo '<p>Portal object already created!</p>';
			exit();
		}
		define('PORTAL_INDEX_FILE', TRUE);
		// portal instance
		if(self::$portal != NULL) {
			echo '<p>Portal already loaded!</p>';
			exit();
		}
		// portal name
		if(empty($portalName)) {
			echo '<p>portalName not set!</p>';
			exit();
		}
		$this->portalName = $portalName;
		// paths
		$this->root = realpath(__DIR__.'/../');
		// load portal index
		$portalIndex = $this->root.'/'.$this->portalName.'/index.php';
		include($portalIndex);
	}


	// page
	public function getPage() {
		if($this->page == NULL)
			return $this->default_page;
		return $this->page;
	}
	public function setDefaultPage($defaultPage) {
		$this->defaultPage = $defaultPage;
	}


}
?>