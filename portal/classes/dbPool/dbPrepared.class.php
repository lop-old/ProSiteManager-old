<?php namespace psm\dbPool;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class dbPrepared {


	public function Prepare($sql) {
	}
	
	
	public function Exec($sql) {
	}
	
	
	public function Fetch() {
		return FALSE;
		//\PDO::FETCH_ASSOC
	}


	public function Clean() {
	}


}
?>