<?php namespace testportal;
use \psm\Portal as Portal;
global $ClassCount; $ClassCount++;
class module_builder extends Portal\Module {

	// build server
	const module_name = 'testportal';
	const module_title = 'Test Portal';
	const module_title_html = '<sub>pxn</sub>TestPortal';
	const version = '3.0.11';


	public function Init() {
		Portal::LoadPage();
		Portal::LoadAction();
		$engine = Portal::getEngine();
		$engine->setSiteTitle(self::getModTitle());
		$engine->setPageTitle('Test Title');
		$engine->Display();
	}


	// get module name
	public function getModName() {
		return self::module_name;
	}
	public static function getModuleName() {
		return self::module_name;
	}
	// get version
	public function getModVersion() {
		return self::version;
	}
	public static function getVersion() {
		return self::version;
	}
	// get mod title
	public function getModTitle() {
		return self::module_title;
	}
	public function getModTitleHtml() {
		return self::module_title_html;
	}


}
?>