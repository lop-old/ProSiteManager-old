<?php namespace psm\Portal;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';} else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class Module {

	public abstract function Init();

	public abstract function getModName();
	public abstract function getModVersion();

	protected $portal;
	protected $engine;


	public function __construct() {
		// get common objects
		$this->portal = \psm\Portal::getPortal();
		$this->engine = \psm\Portal::getEngine();
		// register paths
		$this->_registerPagesPath(\psm\Portal::getLocalPath('module pages',   $this->getModName() ));
		$this->_registerClassPath(\psm\Portal::getLocalPath('module classes', $this->getModName() ));
	}


	// register paths
	protected function _registerPagesPath($path='') {
		if(empty($path))
			$path = __DIR__.DIR_SEP.'classes';
		\psm\ClassLoader::registerClassPath($this->getModName(), $path);
	}
	protected function _registerClassPath($path='') {
		if(empty($path))
			$path = __DIR__.DIR_SEP.'classes';
		\psm\ClassLoader::registerClassPath($this->getModName(), $path);
	}


	// load page
	protected function _LoadPage() {
		$pageObj = \psm\Portal::getPageObj();
		if(empty($pageObj))
			$engine->addToPage('<p>PAGE IS NULL</p>');
		$this->engine->addToPage($pageObj);
	}


}
?>