<?php namespace psm\dbPool;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
require(__DIR__.DIR_SEP.'phppdo'.DIR_SEP.'phppdo.php');
class dbPool {

	const dbNameDefault = 'main';

	// database connection pool
	// [dbname]['config'] - config.php file name/path
	// [dbname]['pdo']    - pdo object
	private static $pool = array();


	public static function getDB($dbName=NULL) {
		// set default
		if(empty($dbName))
			$dbName = self::dbNameDefault;
		// db doesn't exist
		if(!self::dbExists($dbName)) {
			\psm\msgPage::Error('Database '.$dbName.' doesn\'t exist!');
			return NULL;
		}
		// get db connection
		$db = self::$pool[$dbName];
		if(!$db->isConnected()) {
			\psm\msgPage::Error('Failed to connect to database '.$dbName.'!');
			return NULL;
		}
		// clean
		$db->Cleanup();
		return $db;
	}


	// load db config file
	public static function LoadConfig($dbName='') {
		// build file path
		$filePath =
			\psm\Paths::getLocal('root').DIR_SEP.
			'config'.
			(empty($dbName) ?
				'' :
				'.'.\psm\Utils\Utils_Files::SanFilename($dbName)
			).
			'.php';
		// config file not found
		if(!file_exists($filePath)) {
			\psm\msgPage::Error('Database config not found! '.$filePath);
			return FALSE;
		}
		// load db config.php or config.<mod>.php
		require_once($filePath);
	}
	// add db to pool - call from config
	protected static function add_MySQL($config=array()) {
		if(!is_array($config))
			\psm\msgPage::Error('Database config argument must be an array!');
		foreach($config as $dbName => $array) {
			if(empty($dbName))    continue;
			if(!is_array($array)) continue;
			// new db conn
			$db = \psm\dbPool\dbPoolConn::factory($dbName, 'mysql', $config[$dbName]);
			if($db == NULL) continue;
			self::$pool[$dbName] = $db;
		}
	}
//TODO: this isn't needed, should not be used, counter-productive
//	// close all db connections
//	public static function CloseAll() {
//		$keys = array_keys(self::$pool);
//		foreach($keys as $key)
//			self::Close($key);
//	}
//	// close db connection
//	public static function Close($dbName='') {
//		if(empty($dbName)) $dbName = 'main';
//		if(!empty(self::$pool[$dbName])) {
//			self::$pool[$dbName]->Close();
//			unset(self::$pool[$dbName]);
//		}
//	}


	public static function dbExists($dbName) {
		if(empty($dbName)) $dbName = 'main';
		return isset(self::$pool[$dbName]);
	}
	public static function isConnected($dbName) {
		if(empty($dbName)) $dbName = 'main';
		if(!self::dbExists($dbName)) return FALSE;
		return self::$pool[$dbName]->isConnected();
	}


	public static function san($data) {
		return mysql_real_escape_string($data);
	}


//driver-specific topics at http://devuni.com/forums/viewforum.php?f=24
//Currently supported drivers
//1. MySQL driver (both mysql and mysqli extensions)
//2. SQLite driver (only SQLite 2.x)
//3. Postgresql driver
//4. DBLib driver (both mssql and sybase_ct extensions)
//PHPPDO caveats
//1. You should not extend PHPPDO or the statement object, because that will break the compatibility.
//2. Cursors are not supported.
//For PHPPDO questions, help, comments, discussion, etc visit
//http://devuni.com/forums/


}
?>