<?php namespace psm\Widgets;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Widget_DataTables extends \psm\Widgets\DataTables\Table {


	public static function factory($headings, $queryClass, $usingAjax=FALSE) {
		return new self($headings, $queryClass, $usingAjax);
	}


}
?>