<?php namespace psm;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';} else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class listenerGroup {

	private $listeners = array();


	public function registerListener(Listener $listener) {
		\psm\Utils\Utils::appendArray($this->listeners, $listener);
	}


	public function trigger(&$args=array()) {
		if(!is_array($args)) {
			unset($args);
			$args = func_get_args();
		}
		foreach($this->listeners as $listener)
			$listener->trigger($args);
	}


}
?>