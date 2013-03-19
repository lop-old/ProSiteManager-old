<?php namespace psm\Widgets\Wiki;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class WikiPage_Lists extends \psm\Widgets\Wiki\WikiPageParser {

	private $headings = array();


	public function ParseText($text) {
		$regex = array(
			'_list' => '/^((\*|#)\s.*\n)(?!\2\s|(?:\s+((?:\*|#) |\n)))/Usm',
		);
		foreach($regex as $func => $pattern)
			$text = \preg_replace_callback($pattern, array($this, $func), $text);
		return $text;
	}


	// bullet list
	protected function _list($text) {
		$text = \str_replace("\r", '', $text[1]);
		$text = \psm\Utils\Utils_Strings::trim($text);
		$type = \substr($text, 0, 1);
		if($type == '*') $output = '<ul>'.NEWLINE;
		else
		if($type == '#') $output = '<ol>'.NEWLINE;
		else return '';
		foreach(\explode("\n", $text) as $line) {
			$line = \trim(\substr($line, 1));
			$output .= '<li>'.$line.'</li>'.NEWLINE;
		}
		if($type == '*') $output .= '</ul>'.NEWLINE;
		else
		if($type == '#') $output .= '</ol>'.NEWLINE;
		return $output;
	}


}
?>