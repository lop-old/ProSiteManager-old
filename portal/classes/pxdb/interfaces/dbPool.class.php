<?php namespace psm\pxdb\interfaces;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
interface dbPool {

	public static function getDB($dbName=NULL);
	public static function LoadConfig($dbName='');
	public static function dbExists($dbName);
	public static function isConnected($dbName);
	public static function san($data);

}
?>