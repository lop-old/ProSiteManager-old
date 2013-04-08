<?php namespace psm\Portal;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
abstract class Module {

	public abstract function Init();

	public abstract function getModName();
	public abstract function getModVersion();

	public abstract function getModTitle();
	public abstract function getModTitleHtml();


	public function __construct() {
		// register paths
		$this->_registerPagesPath();
		$this->_registerClassPath();
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


	// load page
	protected function _LoadPage() {
		$content = \psm\Portal::getPageObj();
		if(empty($content))
			$content = '<p>PAGE IS NULL</p>';
		\psm\Portal::getEngine()->addToPage($content);
	}


}
?>