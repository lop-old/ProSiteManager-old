<?php namespace psm\html;
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
	public function getAllParts() {
		return \implode(
			NEWLINE.NEWLINE,
			$this->parts
		);
	}


}
?>