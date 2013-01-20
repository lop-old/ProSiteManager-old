<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html_Engine {

	// main html file
	private $htmlMain;

	// main block arrays
	private $blocksHeader;
	private $blocksCss;
	private $blocksJs;
	private $blocksPage;
	private $blocksFooter;

	// global tag parsers
	private static $globalTags = NULL;


	public function __construct(html_File &$htmlMain=NULL) {
		if($htmlMain == NULL)
$this->htmlMain = html_File::LoadFile('default', 'main');
		else
			$this->htmlMain = $htmlMain;
		// tag parsers
		self::$globalTags = new listenerGroup();
		// global tags
		self::$globalTags->registerListener(new listenerGlobalTags());
		// block arrays
		$this->blocksHeader = new html_BlockArray('portal - appended header');
		$this->blocksCss    = new html_BlockArray('portal - appended css');
			$this->blocksCss->setPrepend ('<style type="text/css">');
			$this->blocksCss->setPostpend('</style>');
		$this->blocksJs    = new html_BlockArray('portal - appended javascript');
			$this->blocksJs->setPrepend ('<script type="text/javascript" language="javascript">');
			$this->blocksJs->setPostpend('</script>');
		$this->blocksPage   = new html_BlockArray('portal - page contents');
		$this->blocksFooter = new html_BlockArray('portal - footer contents');
	}


	// build page
	private $buildHasRun = FALSE;
	public function Build() {
		// run only once
		if($this->buildHasRun) return;
		$this->buildHasRun = TRUE;

		// build header
		// split by {header content} tag
		$split = new html_SplitBlock('{header content}', $this->htmlMain->getBlock('head'));
		// open header block
		echo $split->getPart(0);
		// build header
		$this->blocksHeader->Display(TRUE);
		// build inline css
		$this->blocksCss->Display(TRUE);
		// build inline javascript
		$this->blocksJs->Display(TRUE);
		// close header block
		echo $split->getPart(1);
		unset($split, $this->blocksHeader,
			$this->blocksCss, $this->blocksJs);

		// build page
		// split by {page content} tag
		$split = new html_SplitBlock('{page content}', $this->htmlMain->getBlock('body'));
		// open body block
		echo $split->getPart(0);
		// build page content
		$this->blocksPage->Display(TRUE);
		// close body block
		echo $split->getPart(1);
		unset($split, $this->blocksPage);

		// build footer
		// split by {footer content} tag
		$split = new html_SplitBlock('{footer content}', $this->htmlMain->getBlock('footer'));
		// open footer block
		echo $split->getPart(0);
		$this->blocksFooter->Display(TRUE);
		// close footer block
		echo $split->getPart(1);
		unset($split, $this->blocksFooter);

	}


	/* add to block arrays */
	// add to header
	public function addHeader($data, $top=FALSE) {
		$this->blocksHeader->add(self::renderObject($data), $top);
	}
	// add to css
	public function addCss($data, $top=FALSE) {
		$this->blocksCss->add(self::renderObject($data), $top);
	}
	// add to page
	public function addPage($data, $top=FALSE) {
		$this->blocksPage->add(self::renderObject($data), $top);
	}
	// add to footer
	public function addFooter($data, $top=FALSE) {
		$this->blocksFooter->add(self::renderObject($data), $top);
	}


	// render class objects to html
	protected static function renderObject(&$data) {
		if($data == NULL)
			return NULL;
		// page class
		if($data instanceof \psm\Page)
			return $data->Render();
		return (string) $data;
	}


	// call global tag parsers
	public static function renderGlobalTags(&$data) {
		$args = array();
		$args[0] = &$data;
		self::$globalTags->trigger($args);
	}


}
?>