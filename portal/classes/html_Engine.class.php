<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html_Engine {

	// main engine instance
	private static $engine = NULL;

	// main html file
	private $htmlMain;

	// tag parsers
	private $tagString;
	private $tagPaths;


	/**
	 * Gets the main template engine instance, creating a new one if needed.
	 *
	 * @return html_Engine
	 */
	public static function getEngine() {
		if(self::$engine == NULL)
			self::$engine = new self();
		return self::$engine;
	}


	public function __construct(html_File &$htmlMain=NULL) {
		// load main html file
		if($htmlMain == NULL)
//TODO: add theme
			$this->htmlMain = html_File::LoadFile('default', 'main');
		else
			$this->htmlMain = $htmlMain;
		// validate html_File class type
		Utils::Validate('psm\html_File_Main', $this->htmlMain);
		// tag parsers
		$this->tagString = new html_Tag_String();
$paths = array(
'{path=static}'=>'portal/static/',
'{path=theme}'=>'wa/html/default/',
);
		$this->tagPaths = new html_Tag_String(
$paths
//			Portal::getPortal()->getPathsArray()
		);
//		self::$globalTags = new listenerGroup();
//		// global tags
//		self::$globalTags->registerListener(new listenerGlobalTags());
//		// block arrays
//		$this->blocksHeader = new html_BlockArray('portal - appended header');
//		$this->blocksCss    = new html_BlockArray('portal - appended css');
//			$this->blocksCss->setPrepend ('<style type="text/css">');
//			$this->blocksCss->setPostpend('</style>');
//		$this->blocksJs    = new html_BlockArray('portal - appended javascript');
//			$this->blocksJs->setPrepend ('<script type="text/javascript" language="javascript">');
//			$this->blocksJs->setPostpend('</script>');
//		$this->blocksPage   = new html_BlockArray('portal - page contents');
//		$this->blocksFooter = new html_BlockArray('portal - footer contents');
	}


	// build page
	private $buildHasRun = FALSE;
	public function Display() {
		// run only once
		if($this->buildHasRun)
			die('<p>Engine already built the page!</p>');
		$this->buildHasRun = TRUE;

		/* build header */
		// split by {header content} tag
		$splitHeader = new html_SplitBlock('{header content}', $this->htmlMain->getBlock('head'));
		// open header block
		$this->_Render(
			$splitHeader->getPart(0)
		);
//var_dump($this->htmlMain->getBlock('header'));
//exit();
		// build header
		$this->_Render(
			$this->htmlMain->getBlock('header')
		);
//		$this->blocksHeader->Display(TRUE);
		// build inline css
		$this->_Render(
			'<style type="text/css" title="currentStyle">'.NEWLINE.
			$this->htmlMain->getBlock('css').
			'</style>'
		);
//		$this->blocksCss->Display(TRUE);
		// build inline javascript
		$this->_Render(
			$this->htmlMain->getBlock('js')
		);
//		$this->blocksJs->Display(TRUE);
		// close header block
		$this->_Render(
			$splitHeader->getPart(1)
		);
		unset($splitHeader, $this->blocksHeader,
			$this->blocksCss, $this->blocksJs);

		/* build page */
		// split by {page content} tag
		$splitPage = new html_SplitBlock('{page content}', $this->htmlMain->getBlock('body'));
		// open body block
		$this->_Render(
			$splitPage->getPart(0)
		);
		// build page content
		$this->_Render(
			$this->htmlMain->getBlock('page')
		);
//		$this->blocksPage->Display(TRUE);
		// close body block
		$this->_Render(
			$splitPage->getPart(1)
		);
		unset($splitPage, $this->blocksPage);

		/* build footer */
		// split by {footer content} tag
		$splitFooter = new html_SplitBlock('{footer content}', $this->htmlMain->getBlock('foot'));
		// open footer block
		$this->_Render(
			$splitFooter->getPart(0)
		);
		$this->_Render(
			$this->htmlMain->getBlock('footer')
		);
//		$this->blocksFooter->Display(TRUE);
		// close footer block
		$this->_Render(
			$splitFooter->getPart(1)
		);
		unset($splitFooter, $this->blocksFooter);

	}


	private function _Render($data) {
		// string tags
		$args = array('data' => &$data);
		$this->tagString->trigger($args);
		// path tags
		$args = array('data' => &$data);
		$this->tagPaths->trigger($args);
		echo $data;
	}


	/* add to block arrays */
	// add to header
	public static function addHeader($data, $top=FALSE) {
		self::getEngine()->addToHeader($data, $top);
	}
	public function addToHeader($data, $top=FALSE) {
		$this->htmlMain->addBlock('header', $data, $top);
//		$this->blocksHeader->add(self::renderObject($data), $top);
	}


	// add to css
	public static function addCSS($data, $top=FALSE) {
		self::getEngine()->addToCSS($data, $top);
	}
	public function addToCSS($data, $top=FALSE) {
		$this->htmlMain->addBlock('css', $data, $top);
//		$this->blocksCss->add(self::renderObject($data), $top);
	}


	// add to page
	public static function addPage($data, $top=FALSE) {
		self::getEngine()->addToPage($data, $top);
	}
	public function addToPage($data, $top=FALSE) {
		$this->htmlMain->addBlock('page', $data, $top);
//		$this->blocksPage->add(self::renderObject($data), $top);
	}


	// add to footer
	public static function addFooter($data, $top=FALSE) {
		self::getEngine()->addToFooter($data, $top);
	}
	public function addToFooter($data, $top=FALSE) {
		$this->htmlMain->addBlock('footer', $data, $top);
//		$this->blocksFooter->add(self::renderObject($data), $top);
	}


	// render class objects to html
	public static function renderObject(&$data) {
		if($data == NULL)
			return NULL;
		// page class
		if($data instanceof \psm\Page)
			return $data->Render();
		// default to string
		$data = (string) $data;
		// file:
		if(Utils::startsWith($data, 'file:', TRUE))
			return substr(5, $data);
		// string
		return $data;
	}


//	// call global tag parsers
//	public static function renderGlobalTags(&$data) {
//		$args = array();
//		$args[0] = &$data;
//		self::$globalTags->trigger($args);
//	}


}
?>