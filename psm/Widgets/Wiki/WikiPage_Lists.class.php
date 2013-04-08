<?php namespace psm\Widgets\Wiki;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class WikiPage_Lists extends \psm\Widgets\Wiki\WikiPageParser {

	private $regex = array();


	public function __construct() {
		$this->regex['_list'] = '/^((\*|#)\s.*\n)(?!\2\s|(?:\s+((?:\*|#) |\n)))/Usm';
	}
	public function regex() {
		return $this->regex;
	}


	// bullet list
	protected function _list($text) {
		$text = \trim(\str_replace("\r", '', $text[1]));
		$type = \substr($text, 0, 1);
		$text = \psm\Utils\Utils_Strings::trim($text);
		if($type == '*') $output = '<ul>'.NEWLINE;
		else
		if($type == '#') $output = '<ol>'.NEWLINE;
		else return '';
		foreach(\explode("\n", $text) as $line) {
			$line = \psm\Utils\Utils_Strings::trim(
				$line,
				array('*', '#')
			);
			$output .= '<li>'.$line.'</li>'.NEWLINE;
		}
		if($type == '*') $output .= '</ul>'.NEWLINE;
		else
		if($type == '#') $output .= '</ol>'.NEWLINE;
		return $output;
	}


}
?>