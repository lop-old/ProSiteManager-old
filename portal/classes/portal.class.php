<?php namespace psm\portal;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class portal {

	private $portalName;
	private $cwd;


	public function __construct() {
		$this->portalName = PORTAL;
		$this->cwd        = PORTAL_CWD;
		if(substr($this->cwd, -1, 1) != '/')
			$this->cwd .= '/';
		$portalIndex = $this->cwd.$this->portalName.'/index.php';
		include($portalIndex);
	}


//	// site/page title
//	private $title = null;
//	public function setTitle($title) {
//		$this->title = $title;
//	}


}
?>