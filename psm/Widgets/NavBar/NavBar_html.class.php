<?php namespace psm\Widgets\NavBar;
global $ClassCount; $ClassCount++;
final class NavBar_html {
	private function __construct() {}


//	'<!-- ======'.\str_repeat('=', \strlen($menuName)).' -->'.NEWLINE.
//	'<!-- Menu: '.$menuName.' -->'.NEWLINE;


	// open tags
	public static function navOpen($isSubMenu=FALSE) {
		// sub menu
		if($isSubMenu)
			return
				'<!-- ======== -->'.NEWLINE.
				'<!-- Sub-Menu -->'.NEWLINE.
				'<div class="container" id="sub-menu">'.NEWLINE.
				TAB.'<div class="navbar navbar-inverse">'.NEWLINE.
				TAB.'<div class="navbar-inner">'.NEWLINE.
				TAB.TAB.'<div class="container">'.NEWLINE.
				NEWLINE;
		// main menu
		else
			return
				'<!-- ========= -->'.NEWLINE.
				'<!-- Main-Menu -->'.NEWLINE.
				'<div class="navbar navbar-fixed-top">'.NEWLINE.
				TAB.'<div class="navbar-inner">'.NEWLINE.
				TAB.TAB.'<div class="container">'.NEWLINE.
				NEWLINE;
	}
	// close tags
	public static function navClose($isSubMenu=FALSE) {
		// sub menu
		if($isSubMenu)
			return NEWLINE.
				TAB.TAB.'</div>'.NEWLINE.
				TAB.'</div></div>'.NEWLINE.
				'</div>'.NEWLINE.
				'<!-- Sub-Menu -->'.NEWLINE.
				'<!-- ======== -->'.NEWLINE.
				NEWLINE;
		// main menu
		else
			return NEWLINE.
				TAB.TAB.'</div>'.NEWLINE.
				TAB.'</div>'.NEWLINE.
				'</div>'.NEWLINE.
				'<!-- Main-Menu -->'.NEWLINE.
				'<!-- ========= -->'.NEWLINE.
				NEWLINE;
	}


	// brand
	public static function navBrand($title, $url='') {
		if(empty($url))
			$url = \psm\Portal\URL::factory()->setMod( \psm\Portal::getModName() );
		$url = \psm\Portal\URL::toString($url);
		return TAB.TAB.
			'<a class="brand" href="'.$url.'">'.
			$title.'</a>'.
			NEWLINE;
	}


	// menu button
	public static function navButton($title, $url='', $isSelected=FALSE, $isDropdown=NULL) {
		// css classes
		$liClasses = '';
		$aClasses  = '';
		if($isDropdown != NULL) {
			$menuContent = NavBar::RenderMenu($isDropdown);
			$liClasses .= 'dropdown ';
		}
		if($isSelected) {
			$liClasses .= 'active ';
			$aClasses  .= 'active ';
		}
		if(!empty($aClasses))
			$aClasses = 'class="'.$aClasses.'" ';
		// link
		if(empty($url))
			$url = '#';
		$urlA = '<a '.$aClasses.'href="'.\psm\Portal\URL::toString($url).'">';
		$urlB = '</a>';
		if(!empty($liClasses))
			$liClasses = ' class="'.$liClasses.'" ';
		return TAB.TAB.TAB.
			'<li'.$liClasses.'>'.$urlA.$title.$urlB.'</li>'.NEWLINE;

//			(empty($this->icon) ? '' : '<i class="'.$this->icon.' icon-white"></i> ').
//			$this->title.'</a></li>'.NEWLINE;
//				'<a'.($this->isSelected ? ' class="active"' : '').
//				' href="'.$url.'"'.
//				' class="dropdown-toggle" data-toggle="dropdown"> '.
//				(empty($this->icon) ? '' : '<i class="'.$this->icon.' icon-white"></i> ').
//				$this->title.' <b class="caret"></b></a>'.NEWLINE.
//'<ul class="dropdown-menu"><li><a href="#">a</a></li><li><a href="#">b</a></li><li><a href="#">c</a></li></ul>'.
//				'</li>'.NEWLINE;

	}


	// divider
	public static function navBreak() {
		return TAB.TAB.TAB.
			'<li class="divider-vertical"></li>'.
			NEWLINE;
	}


}
?>