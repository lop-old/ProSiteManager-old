<?php namespace psm\ItemDefines;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class DefinesArray {

	private $array = array();


	public function add($id, $array) {
		$this->array[$id] = $array;
	}
	public function addSub($id, $damage, $array) {
		$this->array[$id][$damage] = $array;
	}


	public function get($id) {
		if(isset($this->array[$id]))
			return $this->array[$id];
		return null;
	}


	public function &getArray() {
		return $this->array;
	}


}
?>