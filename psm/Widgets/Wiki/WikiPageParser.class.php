<?php namespace psm\Widgets\Wiki;
global $ClassCount; $ClassCount++;
abstract class WikiPageParser {


	protected abstract function regex();


	public function ParseText($text) {
		foreach($this->regex() as $func => $pattern)
			$text = \preg_replace_callback($pattern, array($this, $func), $text);
		return $text;
	}


}
?>