<?php namespace psm\Portal;
global $ClassCount; $ClassCount++;
abstract class Module {


	// init module
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


	// display page
	protected function AutoLoad() {
		\psm\Portal::AutoLoad_Module();
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