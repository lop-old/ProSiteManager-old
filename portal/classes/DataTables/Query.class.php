<?php namespace psm\DataTables;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class Query {

	// Note: don't initialize database object until needed.
	//       This class may load without being used.

	public abstract function runQuery();
	public abstract function getRow();

}
?>