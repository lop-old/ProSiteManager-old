<?php namespace psm\Widgets\Wiki;
global $ClassCount; $ClassCount++;
class WikiPage extends \psm\Widgets\Widget {

	// wiki parser classes
	private $parsers = array();


	// load parsers
	public function __construct() {
		$this->_LoadParser('Formatter');
		$this->_LoadParser('Lists');
		$this->_LoadParser('Blocks');
	}
	protected function _LoadParser($parserName) {
		if(isset($this->parsers[$parserName]))
			return;
//		if(!\psm\Utils\Utils_Strings::startsWith($parserName, '\\'))
		$clss = 'psm\\Widgets\\Wiki\\WikiPage_'.$parserName;
		if(!class_exists($clss)) {
			\psm\Portal::Error('Wiki parser class not found! '.$clss);
			return;
		}
		$clss = '\\'.$clss;
		$this->parsers[$parserName] = new $clss();
	}


	// render wiki page
	public function Render($text='') {
		if(empty($text)) return '';
		// run parsers
		$text = strip_tags($text);
		foreach($this->parsers as $parser)
			$text = $parser->ParseText($text);
		// parse line breaks
		$lines = \explode("\n", \str_replace("\r", '', \trim($text)));
		$text = '';
		foreach($lines as $line) {
//			$line = \trim($line);
			if(empty($line))
				$text .= '<br />'.NEWLINE;
			else
				$text .= $line.NEWLINE;
		}
		// finished
		return '
<div style="float: right; font-size: x-small;"><a href="./?action=edit">-Edit Page-</a></div>
'.$text.'<br /><br />';
//		return $text;
//		return '&gt; WIKI &lt;';
	}


}
?>