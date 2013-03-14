<?php namespace psm\dbPool;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class dbPoolConn extends dbPrepared {


	public static function factory($dbName, $driver, $config=array()) {
		return new self(
			$dbName,
			$driver,
			@$config['host'],
			@$config['port'],
			@$config['database'],
			@$config['us'.'er'],
			@$config['pa'.'ss']
		);
	}
	private function __construct($dbName, $driver, $database, $host='localhost', $port=3306, $u='', $p='') {
		// db already exists
		if(dbPool::dbExists($dbName)) {
			\psm\msgPage::Error('Database config '.$dbName.' already loaded!');
			return FALSE;
		}
		// build data source name
		$dsn = self::BuildDSN($driver, $database, $host, $port, $u, $p);
		unset($u, $p);
		if(empty($dsn))
			\psm\msgPage::Error('Failed to generate DSN for database!');
		try {
			$clss =
				(!class_exists('PDO') || !extension_loaded('pdo_'.$driver)) ?
				'PHPPDO' :
				'PDO';
			$this->conn = new $clss($dsn);
			unset($dsn);
			// debug mode
			if(\psm\Portal::isDebug())
				$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
			// production mode
			else
				$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
			$this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
			return TRUE;
		} catch(\PDOException $e) {
			\psm\msgPage($e->getMessage());
		}
		return FALSE;
	}


	// build data source name
	private static function BuildDSN($driver, $database, $host='localhost', $port=3306, $u='', $p='') {
		// driver
		if(empty($driver))
			\psm\msgPage::Error('Database driver not set!');
		// database
		if(empty($database))
			\psm\msgPage::Error('database not set!');
		// host
		if(empty($host) || \strtolower($host) == 'localhost')
			$host = '127.0.0.1';
		// port
		$port = \psm\Utils\Utils_Numbers::MinMax(1, 65535, (int) $port );
		// user
		if(empty($u))
			$u = 'ro'.'ot';
		$p = (empty($p) ? '' : ':'.$p);
		return 'mysql://'.$u.$p.$host.'/'.$database;
	}


	public function isConnected() {
		return ($this->conn != NULL);
	}


}
?>