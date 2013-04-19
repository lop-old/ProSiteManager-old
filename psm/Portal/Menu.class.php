<?php namespace psm\Portal;
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