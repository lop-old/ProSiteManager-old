<?php namespace psm\html;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Tag_String extends Tag {

//	private $parts = NULL;
//
//
//	public function __construct($tag, $data, $count=2) {
//		$this->parts = explode($tag, $data, $count);
//	}
//
//
//	public function &getPart($part) {
//		$part = (int)$part;
//		if(!isset($this->parts[$part]))
//			return null;
//		return $this->parts[$part];
//	}


	private $tags = array();


	public function __construct(&$tags=NULL) {
		if($tags != NULL)
			$this->tags = &$tags;
	}


	public function addString($tagName, $value) {
		if(!\psm\Utils\Utils::startsWith($tagName, '{') || !\psm\Utils\Utils::endsWith($tagName, '}'))
			$tagName = '{'.$tagName.'}';
		$this->tags[$tagName] = $value;
	}


	protected function RenderTags(&$args) {
		$data = &$args['data'];
		foreach($this->tags as $tagName => $value)
			$data = str_replace($tagName, $value, $data);
		return $data;
	}


}
?>