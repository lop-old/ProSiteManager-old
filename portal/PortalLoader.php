<?php namespace psm;
use \psm\portal as psmportal;
//defines:
//PORTAL_CWD
//PORTAL


include('class_loader.php');


// debug mode
if(file_exists(__DIR__.'/php_error.php')) {
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


// portal loader
$portal = NULL;
function newPortal($portalName='') {
	// index load point
	if(defined('PORTAL_INDEX_FILE'))
		die('<p>Portal object already created!</p>');
	define('PORTAL_INDEX_FILE', TRUE);
	// cwd
	if(!defined('PORTAL_CWD'))
		define('PORTAL_CWD', realpath(__DIR__.'/../'));
	// portal name
	if(!empty($portalName))
		define('PORTAL', $portalName);
	// load portal
	global $portal;
	$portal = new psmportal\portal();
}


?>