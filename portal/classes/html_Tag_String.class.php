<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html_Tag_String extends html_Tag {

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


	public static function Validate($clss) {
		if(!($clss instanceof self))
			die('<p>Not instance of '.__CLASS__.'!</p>');
//TODO: throw exception
	}


	public function addString($tagName, $value) {
		if(!Utils::startsWith($tagName, '{') || !Utils::endsWith($tagName, '}'))
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