<?php namespace psm\html;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html {


	public function __construct($title=null) {
		if($title != null)
			$this->setTitle($title);
	}


	// site/page title
	private $title = null;
	public function setTitle($title) {
		$this->title = $title;
	}


	// page builder
	private static $pageBuilder = null;
	public static function getPageBuilder() {
		if(self::$pageBuilder == null)
			self::$pageBuilder = new html_PageBuilder();
		return self::$pageBuilder;
	}
	// add to header
	public function addToHeader($data, $top=FALSE) {
		self::getPageBuilder()->addToHeader($data, $top);
	}
	// add to css
	public function addToCss($data, $top=FALSE) {
		self::getPageBuilder()->addToCss($data, $top);
	}
	// add to page
	public function addToPage($data, $top=FALSE) {
		self::getPageBuilder()->addToPage($data, $top);
	}
	// add to footer
	public function addToFooter($data, $top=FALSE) {
		self::getPageBuilder()->addToFooter($data, $top);
	}


}
?>