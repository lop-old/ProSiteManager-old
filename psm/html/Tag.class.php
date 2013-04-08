<?php namespace psm\html;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
abstract class Tag implements \psm\Listener {


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