<?php namespace psm\Widgets\NavBar;
global $ClassCount; $ClassCount++;
class NavBar extends \psm\Widgets\Widget {

	private $menu = NULL;
	private $menuName = NULL;
	private $isSubMenu = FALSE;
//	private $selected = NULL;


	public function Render() {
		$menu = $this->getMenu();
		if($menu == NULL || !\psm\Utils\FuncArgs::classEquals('psm\\Portal\\Menu\\Item', $menu)) {
			\psm\Portal::Error('Invalid argument for menu!');
			return;
		}
		$menuName = $menu->getName();
		// generate nav bar
		$html = '';
		$html .= NavBar_html::navOpen($this->isSubMenu, $menuName);
		$title = $menu->getTitle();
		$url   = $menu->getURL();
		// brand
		if(!empty($title))
			$html .= NavBar_html::navBrand($title, $url);
		// pull left/right
		foreach(\array_reverse(array(FALSE, TRUE)) as $pull) {
			// menu items
			$html .= NEWLINE.'<ul class="nav'.($pull ? '' : ' pull-right').'">'.NEWLINE;
			foreach($menu->getSubs() as $m) {
				if( ($m->getPriority() > 4) == $pull) continue;
				$title = $m->getTitle();
				// break
				if($title == '-') {
					$html .= NavBar_html::navBreak();
					continue;
				}
				$url = $m->getURL();
				$subs = NULL;
				if($m->hasSubs())
					$subs = $m->getSubs();
				$html .= NavBar_html::navButton($title, $url, FALSE, $subs);
			}
			$html .= '</url>'.NEWLINE.NEWLINE;
		}
		$html .= NavBar_html::navClose($this->isSubMenu, $menuName);
		return $html;

//		if($isSubMenu === TRUE)
//			// sub menu
//			$output .=
//				'<div class="container" id="sub-menu">'.NEWLINE.
//				TAB.'<div class="navbar navbar-inverse">'.NEWLINE.
//				TAB.TAB.'<div class="navbar-inner">'.NEWLINE.
//				TAB.TAB.TAB.'<div class="container">'.NEWLINE;
//		else
//			// main menu
//			$output .=
//			'<div class="navbar navbar-fixed-top">'.NEWLINE.
//			TAB.'<div class="navbar-inner">'.NEWLINE.
//			TAB.TAB.'<div class="container">'.NEWLINE;
//		if($this->brand != NULL)
//			$output .= $this->brand->Render();
//		// menu items
//		$output .=
//			TAB.TAB.'<ul class="nav">'.NEWLINE;
//		foreach($this->items as $item)
//			$output .= $item->Render();
//		$output .=
//			TAB.TAB.'</ul>'.NEWLINE;
//		// menu items - right side
//		if(count($this->itemsRight) > 0) {
//			$output .=
//				TAB.TAB.'<ul class="nav pull-right">'.NEWLINE;
//			foreach($this->itemsRight as $item)
//				$output .= $item->Render();
//			$output .=
//				TAB.TAB.'</ul>'.NEWLINE;
//		}
//		// close tags
//		if($isSubMenu === TRUE)
//			// sub menu
//			$output .= NEWLINE.
//				TAB.TAB.TAB.'</div>'.NEWLINE.
//				TAB.TAB.'</div>'.NEWLINE.
//				TAB.'</div>'.NEWLINE.
//				'</div>'.NEWLINE;
//		else
//			// main menu
//			$output .=
//				TAB.TAB.'</div>'.NEWLINE.
//				TAB.'</div>'.NEWLINE.
//				'</div>'.NEWLINE;
//		$output .=
//			'<!-- '.($isSubMenu===TRUE ? 'Sub-Menu' : 'Main-Menu').' -->'.NEWLINE.
//			'<!-- '.($isSubMenu===TRUE ? '========' : '=========').' -->'.NEWLINE;
//		return $output;
	}


	// set menu object
	public function setMenuName($menuName) {
		$this->menuName = $menuName;
		return $this;
	}
	public function setMenu(\psm\Portal\Menu $menu) {
		$this->menu = $menu;
		return $this;
	}
	// get menu object
	protected function getMenu() {
		if($this->menu != NULL)
			return $this->menu;
		// load menu object
		if(!empty($this->menuName))
			$this->menu = \psm\Portal\Menu::getMenu($this->menuName);
		return $this->menu;
	}


	public function isSubMenu($isSubMenu=NULL) {
		if($isSubMenu != NULL)
			$this->isSubMenu = ($isSubMenu === TRUE);
		return $this->isSubMenu;
	}


	// selected page
//	public function isSelected() {
//		return FALSE;
//	}
	public function setSelected($selected) {
		$this->selected = $selected;
		return $this;
	}


//	// add items
//	public function add(\psm\Widgets\NavBar\NavBar_html $item, $right=FALSE) {
//		if($right === TRUE)
//			\psm\Utils\Utils::appendArray($this->itemsRight, $item);
//		else
//			\psm\Utils\Utils::appendArray($this->items, $item);
//		return $this;
//	}
//	// add menu button
//	public function addButton($name, $title, \psm\Portal\URL $url, $icon, $right=FALSE) {
//		$isSelected = ($this->selected == $name);
//		$this->add( new NavBar_html($name, $title, $url, $icon, FALSE, $isSelected), $right );
//		return $this;
//	}
//	// add dropdown menu
//	public function addDropdown($name, $title, \psm\Portal\URL $url, $icon, $right=FALSE) {
//		$isSelected = ($this->selected == $name);
//		$this->add( new NavBar_html($name, $title, $url, $icon, TRUE, $isSelected), $right );
//		return $this;
//	}
//	// add menu break
//	public function addBreak($right=FALSE) {
//		$this->add( new NavBar_html('-'), $right );
//		return $this;
//	}


}
?>