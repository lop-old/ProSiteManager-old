<?php namespace psm\Widgets\NavBar;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class NavBar_Item {

	private $isBrand    = FALSE;
	private $isDropdown = FALSE;
	private $isDivider  = FALSE;

	private $name;
	private $title;
	private $url;
	private $icon;
	private $isSelected;


	public function __construct($name='', $title='', \psm\Portal\URL $url=NULL, $icon='', $dropdown=FALSE, $isSelected=FALSE) {
		// divider
		if($name == '-') {
			$this->isDivider = TRUE;
			return;
		}
		// brand
		if($name == 'brand') {
			$this->isBrand = TRUE;
			$dropdown = FALSE;
			$icon = NULL;
		}
		// dropdown
		if($dropdown === TRUE) {
			$this->isDropdown = TRUE;
		}
		$this->name  = $name;
		$this->title = $title;
		$this->url   = $url;
		$this->icon  = $icon;
		$this->isSelected = $isSelected;
	}


	public function Render() {
		$url = $this->url;
		// brand
		if($this->isBrand === TRUE) {
			if(empty($url)) $url = \psm\Portal\URL::factory()->setMod( \psm\Portal::getModName() )->getURL();
			else            $url = $this->url->getURL();
			return TAB.TAB.
				'<a class="brand" href="'.$url.'">'.
				$this->title.'</a>'.NEWLINE;
		}
		// dropdown menu
		if($this->isDropdown === TRUE) {
			if(empty($url)) $url = '#';
			else            $url = $this->url->getURL();
			return TAB.TAB.TAB.
				'<li class="dropdown'.($this->isSelected ? ' active' : '').'">'.
				'<a'.($this->isSelected ? ' class="active"' : '').
				' href="'.$url.'"'.
				' class="dropdown-toggle" data-toggle="dropdown"> '.
				(empty($this->icon) ? '' : '<i class="'.$this->icon.' icon-white"></i> ').
				$this->title.' <b class="caret"></b></a>'.NEWLINE.
'<ul class="dropdown-menu"><li><a href="#">a</a></li><li><a href="#">b</a></li><li><a href="#">c</a></li></ul>'.
				'</li>'.NEWLINE;
		}
		// divider
		if($this->isDivider === TRUE) {
			return TAB.TAB.TAB.
				'<li class="divider'.(TRUE ? '-vertical' : '').'"></li>'.NEWLINE;
		}
		// menu button
		if(empty($url)) $url = '#';
		else            $url = $this->url->getURL();
		return TAB.TAB.TAB.
			'<li'.($this->isSelected ? ' class="active"' : '').'>'.
			'<a href="'.$url.'">'.
			(empty($this->icon) ? '' : '<i class="'.$this->icon.' icon-white"></i> ').
			$this->title.'</a></li>'.NEWLINE;
	}


}
?>