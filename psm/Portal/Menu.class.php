<?php namespace psm\Portal;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
use \psm\Portal\Menu as Menu;
global $ClassCount; $ClassCount++;
final class Menu {
	private function __construct() {}

	protected static $menus = array();


	// new menu
	public static function &newMenu($menuName, $title=NULL, $url=NULL) {
		$menu = NULL;
		// menu already exists
		if(isset(self::$menus[$menuName])) {
			$menu = &self::$menus[$menuName];
			if($title !== NULL) $menu->setTitle($title);
			if(!empty($url))   $menu->setURL($url);
		// new menu
		} else {
			$menu = new Menu\Item($menuName, $title, $url);
			self::$menus[$menuName] = $menu;
		}
		return $menu;
	}
	// get menu
	public static function &getMenu($menuName) {
		return self::newMenu($menuName);
	}
	// set/replace menu
	public static function setMenu($menu) {
		if(!\psm\Utils\FuncArgs::classEquals('psm\\Portal\\Menu\\Item', $menu)) {
			\psm\Portal::Error('Invalid argument for menu!');
			exit();
		}
		$menuName = $menu->getName();
		self::$menus[$menuName] = $menu;
	}


	// add menu item
	public static function addItem(Menu\Item &$menu, $name='', $title='', $url='', $icon='', $priority=-1) {
		$item = new Menu\Item($name, $title, $url, $icon);
		$menu->addSub($item, $priority);
	}


	// add break
	public static function addBreak(Menu\Item &$menu, $priority=-1) {
		$item = new Menu\Item('-');
		$menu->addSub($item, $priority);
	}


}
?>