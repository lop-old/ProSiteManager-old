<?php namespace psm;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class Language {

	private $messages = array();


	// load language
	abstract protected function load();


	// add message
	public function addMsg($name, $msg) {
		$this->messages[$name] = $msg;
	}


	// get message
	public function getMsg($name) {
		if(isset($this->messages[$name]))
			return $this->messages[$name];
		return '[Language Message Not Found | '.$name.']';
	}


}
?>