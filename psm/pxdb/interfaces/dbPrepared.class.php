<?php namespace psm\pxdb\interfaces;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
interface dbPrepared {

	public function Prepare($sql);
	public function Clean();
	public function Exec($sql='');
	public function hasNext();
	public function getRowCount();
	public function getInsertId();
	public function setString($index, $value);
	public function setInt($index, $value);
	public function setDouble($index, $value);
	public function setLong($index, $value);
	public function setBoolean($index, $value);
	public function getString($label);
	public function getInt($label);
	public function getDouble($label);
	public function getLong($label);
	public function getBoolean($label);

}
?>