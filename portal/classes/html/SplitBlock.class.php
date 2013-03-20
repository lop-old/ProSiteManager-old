<?php namespace psm\html;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
class SplitBlock {

	private $parts = NULL;


	public function __construct($tag, $data, $count=2) {
		$this->parts = explode($tag, $data, $count);
	}


	public function &getPart($part) {
		$part = (int)$part;
		if(!isset($this->parts[$part])) {
			$null = NULL;
			return $null;
		}
//		html_engine::renderGlobalTags($this->parts[$part]);
		return $this->parts[$part];
	}


}
?>