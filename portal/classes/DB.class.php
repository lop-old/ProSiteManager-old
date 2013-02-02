<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
require(__DIR__.'/phppdo/phppdo.php');
class DB {

	const dbDefaultName = 'main';

	private static $dbPool = array();


	public static function getDB($dbName=NULL) {
		// set default
		if($dbName == NULL)
			$dbName = self::dbDefaultName;
		if(!self::dbIsSet($dbName))        return NULL;
		if(!isset(self::$dbPool[$dbName])) return NULL;
		if(!self::dbIsConnected($dbName))
			require_once(self::$dbPool[$dbName]['config']);
		if(!self::dbIsConnected($dbName))  return NULL;
		return self::$dbPool[$dbName]['pdo'];
	}


	// add db to pool
	public static function addDB($dbName, $dbConfigFile) {
		if(empty($dbName))                die('<p>dbName is empty!</p>');
		if(empty($dbConfigFile))          die('<p>dbConfigFile is empty!</p>');
		if(!file_exists($dbConfigFile))   die('<p>db config file doesn\'t exist! '.$dbConfigFile.'</p>');
		if(isset(self::$dbPool[$dbName])) die('<p>db already exists! '.$dbName.'</p>');
		// add config to pool
		self::$dbPool[$dbName]['config'] = $dbConfigFile;
	}


	// connect to database
	public static function Connect($dbName, $dsn, $user='', $pass='', $driver_options=array()) {
		try {
			$driver = strtolower(trim(substr($dsn, 0, strpos($dsn, ':'))));
			if(	empty($driver) || !class_exists('PDO') || !extension_loaded('pdo_'.$driver) )
				$class = 'PHPPDO';
			else
				$class = 'PDO';
			self::$dbPool[$dbName]['pdo'] =
				new $class($dsn, $user, $pass, $driver_options);
//self::$dbPool[$dbName]['pdo']->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
//self::$dbPool[$dbName]['pdo']->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}
	// mysql
	public static function Connect_MySQL($dbName, $host='localhost', $port=3306, $database='', $user='', $pass='', $driver_options=array()) {
		return self::Connect(
			$dbName,
			'mysql:host='.$host.';port='.((int)$port).';dbname='.$database,
			$user,
			$pass,
			$driver_options
		);
	}


	public static function dbIsSet($dbName) {
		if(empty($dbName))
			return FALSE;
		return isset(self::$dbPool[$dbName]);
	}
	public static function dbIsConnected($dbName) {
		if(empty($dbName))                        return FALSE;
		if(!self::dbIsSet($dbName))               return FALSE;
		if(!isset(self::$dbPool[$dbName]['pdo'])) return FALSE;
		if(self::$dbPool[$dbName]['pdo'] == NULL) return FALSE;
		return TRUE;
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
