<?php namespace psm\Widgets\Wiki;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class WikiPage_Formatter extends \psm\Widgets\Wiki\WikiPageParser {

	private $headings = array();


	public function ParseText($text) {
		$regex = array(
			'_heading'	=> '/^(\={2,6}) (.*) (\={2,6})/m',
			'_bold'		=> "/'''(()|[^'].*)'''/U",
			'_italic'	=> "/''(()|[^'].*)''/U",
			'_strong'	=> "/\*\*(.*?)\*\*/U",
			'_underline'=> "/__(()|[^_].*)__/U",
			'_indent'	=> "/ (.*)__/U",
		);
		foreach($regex as $func => $pattern)
			$text = preg_replace_callback($pattern, array($this, $func), $text);
		return $text;
	}


	// heading
	protected function _heading($text) {
//echo '<pre>'.print_r($text, TRUE).'</pre>';exit();
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
	// italic
	protected function _italic($text) {
		return '<i>'.$text[1].'</i>';
	}
	// strong
	protected function _strong($text) {
		return '<strong>'.$text[1].'</strong>';
	}
	// underline
	protected function _underline($text) {
		return '<u>'.$text[1].'</u>';
	}


	// indent
	protected function _indent($text) {
		return '<di>'.$text[1].'</di>';
	}


}
?>