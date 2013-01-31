<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class html_Tag  implements Listener {


	// replace the tags
	protected abstract function RenderTags(&$args);


	/* (non-PHPdoc)
	 * @see \psm\listener::trigger()
	 */
	public function trigger(&$args) {
		return $this->RenderTags($args);
//print_r($args);
//echo __FILE__.' '.__LINE__;
//exit();
// TODO Auto-generated method stub
	}


}
?>