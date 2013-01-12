<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html_PageBuilder {

	// buffers
	private $header = array();
	private $css    = array();
	private $page   = array();
	private $footer = array();


	// build page
	private $buildHasRun = FALSE;
	public function Build() {
		// run only once
		if($this->buildHasRun) return;
		$this->buildHasRun = TRUE;
		$this->displayHeader();
	}
	// build header
	private function displayHeader() {
//		$css = $this->buildCss();
		foreach($this->header as &$v) {
			echo $v;
		}
		unset($v);
	}
	// build css
	private function buildCss() {
		$css = "\n\n";
		foreach($this->css as &$v) {
			$css .= $v."\n\n";
		}
		return $css;
	}
	// build page
	private function displayPage() {
		foreach($this->page as &$v) {
			echo $v;
		}
	}
	// build footer
	private function displayFooter() {
		foreach($this->footer as &$v) {
			echo $v;
		}
	}


	// add to header
	public function addToHeader($data, $top=FALSE) {
		self::addToArray($this->header, $data, $top);
	}
	// add to css
	public function addToCss($data, $top=FALSE) {
		self::addToArray($this->css, $data, $top);
	}
	// add to page
	public function addToPage($data, $top=FALSE) {
		self::addToArray($this->page, $data, $top);
	}
	// add to footer
	public function addToFooter($data, $top=FALSE) {
		self::addToArray($this->footer, $data, $top);
	}


	// add data to array
	protected static function appendArray(&$array, $data, $top=FALSE) {
		// top of array
		if($top) {
			array_unshift($array, $data);
		// bottom of array
		} else {
			$array[] = $data;
		}
	}


}
?>