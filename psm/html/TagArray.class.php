<?php namespace psm\html;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class TagArray implements TagParser {

	private $tags = array();


	public function __construct() {
		$args = \func_get_args();
		if(count($args) == 0) {
		} else
		if(count($args) == 1) {
			$this->add($args[0]);
		} else {
			foreach($args as $arg)
				$this->add($arg);
		}
	}


	public function add($tags) {
		if(!\is_array($tags)) return;
		foreach($tags as $tagName => $tagValue)
			$this->addTag($tagName, $tagValue);
	}
	public function addTag($tagName, $tagValue) {
		\psm\Utils\Strings::forceStartsWith($tagName, '{');
		\psm\Utils\Strings::forceEndsWith  ($tagName, '}');
		$this->tags[$tagName] = (string) $tagValue;
	}


	public function RenderTags(&$data) {
		foreach($this->tags as $tagName => $tagValue)
			$data = \str_replace($tagName, $tagValue, $data);
		return $data;
	}


}
?>