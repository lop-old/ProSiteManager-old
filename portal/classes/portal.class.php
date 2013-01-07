<?php namespace psm;
if(!defined('DEFINE_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class portal {


	public function addPortal($portalName) {
		include($portalName.'/index.php');
	}




//	public function __construct($title=null) {
//		if($title != null)
//			$this->setTitle($title);
//	}


//	// site/page title
//	private $title = null;
//	public function setTitle($title) {
//		$this->title = $title;
//	}


}
?>