<?php namespace psm\Widgets\Wiki;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class WikiPageParser {


	protected abstract function regex();


	public function ParseText($text) {
		foreach($this->regex() as $func => $pattern)
			$text = \preg_replace_callback($pattern, array($this, $func), $text);
		return $text;
	}


}
?>