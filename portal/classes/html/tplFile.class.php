<?php namespace psm\html;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class tplFile {

//	protected $tags = array();
	protected $blocks = array();

	protected static $cachedFiles = array();


	public static function LoadFile($theme, $filename) {
//TODO: add caching!!!
		$filepath =
			'wa/html/'.\psm\Utils\Utils_Files::SanFilename($theme).
			'/'.
			str_replace('..', '', $filename).
			'.html.php';
		if(!file_exists($filepath))
			die('<p>File not found! '.$filepath.'</p>');
		include($filepath);
		$clss = '\wa\html_'.$filename;
		if(!class_exists($clss))
			die('<p>Class not found! '.$clss.'</p>');
		return new $clss();
	}


	public function addBlock($blockName, &$data, $top=FALSE) {
		if($data == NULL) return;
		$rendered = Engine::renderObject($data);
		// attempt loading block function
		if(!isset($this->blocks[$blockName]))
			$this->blocks[$blockName] = $this->getFunc($blockName);
		if($this->blocks[$blockName] == NULL)
			$this->blocks[$blockName] = '';
		// add to block
		if($top)
			$this->blocks[$blockName] = $rendered . $this->blocks[$blockName];
		else
			$this->blocks[$blockName] .= $rendered;
	}
	public function getBlock($blockName='block') {
//if($blockName == 'header'){
//	var_dump($this->blocks);
//exit();
//}

		// return cached block
		if(isset($this->blocks[$blockName]))
			return $this->blocks[$blockName];
		// load block function
		$this->blocks[$blockName] = $this->getFunc($blockName);
		// block function not found
		if($this->blocks[$blockName] == NULL) {
			unset($this->blocks[$blockName]);
			return NULL;
		}
		// return loaded block
		return $this->blocks[$blockName];
	}
	protected function getFunc($blockName) {
		$func = '_'.$blockName;
		if(!method_exists($this, $func))
			return null;
		return $this->$func();
	}


	public function getFilePath() {
		return __FILE__;
	}


	public function &getTags() {
		return $this->tags;
	}
	public function &getTag($tag) {
		if(!isset($this->tags[$tag]))
			return null;
		return $this->tags[$tag];
	}
	public function addTag($tag, $value) {
		$this->tags[$tag] = $value;
	}


}
?>