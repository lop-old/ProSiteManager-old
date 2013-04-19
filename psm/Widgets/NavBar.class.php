<?php namespace psm\Widgets;
global $ClassCount; $ClassCount++;
class NavBar extends \psm\Widgets\NavBar\NavBar {


	public static function factory() {
		return new self();
	}


	// render menu object
	public static function RenderMenu($menu='main', $isSubMenu=FALSE) {
		if(\is_object($menu) && \psm\Utils\FuncArgs::classEquals('psm\\Portal\\Menu\\Item', $menu))
			$menuName = $menu->getName();
		else
			$menuName = (string) $menu;
		$nav = self::factory();
		$nav->setMenuName($menuName);
		$nav->isSubMenu($isSubMenu);
		return $nav->Render();
	}


}
?>