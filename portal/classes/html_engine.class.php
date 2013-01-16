<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html_engine {

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
		$this->displayPage();
		$this->displayFooter();
	}
	// build header
	private function displayHeader() {
//		$css = $this->buildCss();
		foreach($this->header as &$v)
			echo $v;
		unset($v, $this->header);
	}
	// build css
	private function buildCss() {
		$css = LN.LN;
		foreach($this->css as &$v)
			$css .= $v.LN.LN;
		unset($v, $this->css);
		return $css;
	}
	// build page
	private function displayPage() {
		foreach($this->page as &$v)
			echo $v;
		unset($v, $this->page);
	}
	// build footer
	private function displayFooter() {
		foreach($this->footer as &$v)
			echo $v;
		unset($v, $this->footer);
	}


	// add to header
	public function addToHeader($data, $top=FALSE) {
		self::appendArray(
			$this->header,
			self::renderObject($data),
			$top
		);
	}
	// add to css
	public function addToCss($data, $top=FALSE) {
		self::appendArray(
			$this->css,
			self::renderObject($data),
			$top
		);
	}
	// add to page
	public function addToPage($data, $top=FALSE) {
		self::appendArray(
			$this->page,
			self::renderObject($data),
			$top
		);
	}
	// add to footer
	public function addToFooter($data, $top=FALSE) {
		self::appendArray(
			$this->footer,
			self::renderObject($data),
			$top
		);
	}


	// render class objects to html
	protected static function renderObject($data) {
		if($data == NULL)
			return NULL;
		// page class
		if($data instanceof Page)
			return $data->Render();
		return (string) $data;
	}


	// add data to array
	protected static function appendArray(&$array, $data, $top=FALSE) {
		if($data == NULL) return;
		// top of array
		if($top)
			array_unshift($array, $data);
		// bottom of array
		else
			$array[] = $data;
	}


}
?>