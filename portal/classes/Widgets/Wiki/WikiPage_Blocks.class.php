<?php namespace psm\Widgets\Wiki;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class WikiPage_Blocks extends \psm\Widgets\Wiki\WikiPageParser {

	private $regex = array();
	private $headings = array();


	public function __construct() {
		$this->regex['_indent']		= "/ (.*)__/U";
		$this->regex['_blockquote']	= '/\n((\>).*\n)(?!(\>))/Us';
	}
	public function regex() {
		return $this->regex;
	}


	// indent
	protected function _indent($text) {
//		return '<>'.$text[1].'</>';
	}


	// blockquote
	protected function _blockquote($text) {
		$text = \trim(\str_replace("\r", '', $text[1]));
		$text = \psm\Utils\Utils_Strings::trim($text, '>');
		$output = '';
		foreach(\explode("\n", $text) as $line) {
			$line = \psm\Utils\Utils_Strings::trim($line, '>');
			$output .= $line.'<br />'.NEWLINE;
		}
		return '<blockquote>'.$output.'</blockquote>';
	}


}
?>