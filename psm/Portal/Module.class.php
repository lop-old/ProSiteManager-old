<?php namespace psm\Portal;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
abstract class Module {


	// init page
	public abstract function Init();
	public static function AutoInit() {
		// load page
		\psm\Portal::LoadPage();
		\psm\Portal::LoadAction();
		// display page
		$engine->Display();
	}


	public abstract function getModName();
	public abstract function getModVersion();

	public abstract function getModTitle();
	public abstract function getModTitleHtml();


	public function __construct() {
		// register paths
		$this->_registerPagesPath();
		$this->_registerClassPath();
	}


	public static function setSiteTitle($siteTitle) {
		\psm\Portal::getEngine()->setSiteTitle($siteTitle);
	}
	public static function setPageTitle($pageTitle) {
		\psm\Portal::getEngine()->setPageTitle($pageTitle);
	}


	// register paths
	protected function _registerPagesPath($path='') {
		if(empty($path))
			$path = \psm\Paths::getLocal('module pages', $this->getModName());
		\psm\Loader::registerClassPath($this->getModName(), $path);
	}
	protected function _registerClassPath($path='') {
		if(empty($path))
			$path = \psm\Paths::getLocal('module classes', $this->getModName());
		\psm\Loader::registerClassPath($this->getModName(), $path);
	}


}
?>