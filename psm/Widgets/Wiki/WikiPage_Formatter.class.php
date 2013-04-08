<?php namespace psm\Widgets\Wiki;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class WikiPage_Formatter extends \psm\Widgets\Wiki\WikiPageParser {

	private $regex = array();
	private $headings = array();


	public function __construct() {
		$this->regex['_heading']	= "/^(\={2,6}) (.*) (\={2,6})/m";
		$this->regex['_bold']		= "/'''(()|[^'].*)'''/U";
		$this->regex['_strong']		= "/\*\*(.*?)\*\*/U";
		$this->regex['_italic']		= "/''(()|[^'].*)''/U";
		$this->regex['_emphasis']	= "/\/\/(.*?)\/\//";
		$this->regex['_underline']	= "/__(()|[^_].*)__/U";
		$this->regex['_super']		= "/\^\^(()|.*)\^\^/U";
		$this->regex['_sub']		= "/,,(()|.*),,/U";
		$this->regex['_hr']			= "/\-\-\-\-/m";
	}
	public function regex() {
		return $this->regex;
	}


	// heading
	protected function _heading($text) {
		$h = \strlen($text[1]) - 1;
		$h = \psm\Utils\Utils_Numbers::MinMax($h, 1, 5);
		$text = \trim($text[2]);
		if(!isset($this->headings[$text]))
			$this->headings[$text] = $h;
		return
			'<h'.$h.' style="padding-top: 15px;">'.$text.'</h'.$h.'>'.
//			'<div style="float: right; font-size: x-small; margin-top: -8px; margin-left: 6px; margin-right: '.($h <= 1 ? $h*20 : 40 ).'px;">'.
//				'<a href="./?page=wiki&action=edit">-Edit-</a></div>'.
			($h <= 1 ? '<hr />' : '');
	}


	// bold
	protected function _bold($text) {
		return '<b>'.$text[1].'</b>';
	}
	// strong
	protected function _strong($text) {
		return '<strong>'.$text[1].'</strong>';
	}
	// italic
	protected function _italic($text) {
		return '<i>'.$text[1].'</i>';
	}
	// emphasis
	protected function _emphasis($text) {
		return '<em>'.$text[1].'</em>';
	}
	// underline
	protected function _underline($text) {
		return '<u>'.$text[1].'</u>';
	}


	// super-script
	protected function _super($text) {
		return '<sup>'.$text[1].'</sup>';
	}
	// sub-script
	protected function _sub($text) {
		return '<sub>'.$text[1].'</sub>';
	}


	// horizontal rule
	protected function _hr($text) {
		return '<hr />';
	}


}
?>