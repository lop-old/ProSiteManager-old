<?php namespace builder;
use \psm\Portal as Portal;
global $ClassCount; $ClassCount++;
class module_builder extends Portal\Module {

	// build server
	const module_name = 'builder';
	const module_title = 'BuildServer';
	const module_title_html = '<sub>pxn</sub>BuildServer';
	const version = '3.0.11';



//	public function __construct() {
//		parent::__construct();
//$pass = new \psm\PassCrypt();
//echo $pass->hash('pass');
//exit();
// load config.php
//$config = \psm\config::loadConfig('config.php');
// load database config
//\psm\pxdb\dbPool::LoadConfig();
//$db = \psm\pxdb\dbPool::getDB('main');
//$user = waUser::getUserSession($db);
//echo '<br /><br /><br /><pre>'.print_r($user, TRUE).'</pre>';
//	}


//		// override menu
//		$menu = Portal\Menu::getMenu('main');
//		$menu = new Portal\Menu\Item('main', Portal::getModObj()->getModTitleHtml(), Portal\URL::factory()->setRawURL('/'), 'icon', FALSE);
//		Portal\Menu::addItem($menu, 'test', 'TEST', 'url', 'icon', 5);
//		$menu->setWritable(FALSE);
//		Portal\Menu::setMenu($menu);
//		// override sub-menu
//		$menu = Portal\Menu::getMenu('quick');
//		$menu = new Portal\Menu\Item('quick', 'WebAuctionPlus');
//		Portal\Menu::addItem($menu, 'version', '3.0.6 build 35');
//		$menu = new Portal\Menu\Item('quick', 'title', 'url', 'icon', FALSE);
//		Portal\Menu::addItem($menu, 'test', 'TEST', 'url', 'icon', 5);
//		$menu->setWritable(FALSE);
//		Portal\Menu::setMenu($menu);




	public function Init() {
		Portal::LoadPage();
		Portal::LoadAction();
		$engine = Portal::getEngine();
		$engine->setSiteTitle(self::getModTitle());
		$engine->setPageTitle('Home');
		$engine->Display();
//		// display page
//		$this->AutoLoad();
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