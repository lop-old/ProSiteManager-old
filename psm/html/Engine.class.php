<?php namespace psm\html;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
\ob_start();
class Engine {

	// main engine instance
	private static $engine = NULL;
	private static $hasDisplayed = FALSE;

	// main html file
	private $htmlMain;

	// site/page title
	private $siteTitle = NULL;
	private $pageTitle = NULL;

	// tag parsers
	private $globalTags;
	private $pathTags;


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


	public function __construct(\psm\html\tplFile &$htmlMain=NULL) {
		// load main html file
		if($htmlMain == NULL)
			$this->htmlMain = \psm\html\tplFile::LoadFile('wa', \psm\Portal::getPortalTheme(), 'main');
		else
			$this->htmlMain = $htmlMain;
		// validate html_File class type
		\psm\Utils\FuncArgs::classValidate('psm\\html\\tplFile_Main', $this->htmlMain);
		// tag parsers
		$this->globalTags = new TagArray();
		$this->pathTags = new TagArray(
			array(
				'{path=static}'	=> 'psm/static/',
				'{path=theme}'	=> 'psm/html/default/',
			)
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
	public static function Unload() {
		self::$engine = NULL;
	}


	// build page
	public function Display() {
		// run only once
		if(self::hasDisplayed(TRUE))
			return;
		// end output buffer
		$this->addToPage(
			\ob_get_clean()
		);
		// build title
//TODO: $this->siteTitle $this->pageTitle
		$this->globalTags->addTag('{title}', $this->siteTitle.' - '.$this->pageTitle);
		/* build header */
		// split by {header content} tag
		$splitHeader = new SplitBlock('{header content}', $this->htmlMain->getBlock('head'));
		// put it together
		$headerBlock =
			$splitHeader->getPart(0)                      .NEWLINE.
			$this->htmlMain->getBlock('header')           .NEWLINE.
			'<style type="text/css" title="currentStyle">'.NEWLINE.
			$this->htmlMain->getBlock('css')              .NEWLINE.
			'</style>'                                    .NEWLINE.
			$this->htmlMain->getBlock('js')               .NEWLINE.
			$splitHeader->getPart(1);
		$this->_echo($headerBlock);
		unset($headerBlock, $splitHeader, $this->blocksHeader,
			$this->blocksCss, $this->blocksJs);

		/* build page */
		// split by {page content} tag
		$splitPage = new SplitBlock('{page content}', $this->htmlMain->getBlock('body'));
		// put it together
		$bodyBlock =
			$splitPage->getPart(0)           .NEWLINE.
			$this->htmlMain->getBlock('page').NEWLINE.
			$splitPage->getPart(1);
		$this->_echo($bodyBlock);
		unset($bodyBlock, $splitPage, $this->blocksPage);

		/* build footer */
		// split by {footer content} tag
		$splitFooter = new SplitBlock('{footer content}', $this->htmlMain->getBlock('foot'));
		// build footer
		$footerBlock =
			$splitFooter->getPart(0)           .NEWLINE.
			$this->htmlMain->getBlock('footer').NEWLINE.
			$splitFooter->getPart(1)           .NEWLINE;
		if(!strpos($footerBlock, '{footer copyright}'))
			$footerBlock .= '<center>{footer copyright}</center>';
		$footerBlock = str_replace('{footer copyright}',
'<p><a href="http://dev.bukkit.org/server-mods/webauctionplus/" target="_blank" style="color: #333333;">'.
'<u>'.\psm\Portal::getModObj()->getModTitle().'</u> '.\psm\Portal::getModObj()->getModVersion().'</a><br />'.
'<span style="font-size: smaller;">by lorenzop</span><span style="font-size: xx-small;"> &copy; 2012-2013</span></p>',
			$footerBlock);
		$footerBlock = \str_replace('{class count}', \psm\getClassCount(), $footerBlock);
		$this->_echo($footerBlock);
		unset($footerBlock, $splitFooter, $this->blocksFooter);

	}


	private function _echo($data) {
		if(empty($data)) return;
		// render tags
		$this->globalTags->RenderTags($data);
		$this->pathTags->RenderTags($data);
		// echo data
		echo $data;
		@\ob_flush();
	}


//TODO: not finished
	public function setPageTitle($title) {
		$this->pageTitle = $title;
	}
	public function setSiteTitle($title) {
		$this->siteTitle = $title;
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
		if($data instanceof \psm\Portal\Page)
			return $data->Render();
		// default to string
		$data = (string) $data;
		// file:
		if(\psm\Utils\Strings::StartsWith($data, 'file:', TRUE))
			return \substr(5, $data);
		// string
		return $data;
	}


//	// call global tag parsers
//	public static function renderGlobalTags(&$data) {
//		$args = array();
//		$args[0] = &$data;
//		self::$globalTags->trigger($args);
//	}


	public static function hasDisplayed($hasDisplayed=FALSE) {
		$hasBefore = self::$hasDisplayed;
		if($hasDisplayed === TRUE)
			self::$hasDisplayed = TRUE;
		return $hasBefore;
	}


}
?>