<?php namespace psm\html;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
class urlBuilder {


	public function getUrl() {
		return '/psm/?mod='.$this->getModName().'&page='.$this->getPage();
	}


	public function getModName() {
		return 'wa';
	}
	public function getPage() {
		return 'home';
	}

}
?>