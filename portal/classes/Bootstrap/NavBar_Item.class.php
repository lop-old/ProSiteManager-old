<?php namespace psm\Bootstrap;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';} else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class NavBar_Item {

	private $isBrand    = FALSE;
	private $isDropdown = FALSE;
	private $isDivider  = FALSE;

	private $name  = NULL;
	private $title = NULL;
	private $url   = NULL;
	private $icon  = NULL;


	public function __construct($name='', $title='', $url='', $icon='', $dropdown=FALSE) {
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
	}


	public function Render() {
		// brand
		if($this->isBrand === TRUE)
			return '		'.
				'<a class="brand" href="'.
				(empty($this->url) ? '#' : $this->url).'">'.
				$this->title.'</a>'.NEWLINE;
		// dropdown menu
		if($this->isDropdown === TRUE)
			return '			'.
				'<a'.($this->isActive() ? ' class="active"' : '').
				' href="'.(empty($this->url) ? '#' : $this->url).'"'.
				'class="dropdown-toggle" data-toggle="dropdown">'.
				(empty($this->icon) ? '' : '<i class="'.$this->icon.'icon-white"></i> ').
				$this->title.' <b class="caret"></b></a>'.NEWLINE;
		// divider
		if($this->isDivider === TRUE)
			return '			'.
				'<li class="divider'.(TRUE ? '-vertical' : '').'"></li>'.NEWLINE;
		// menu button
		return '			'.
			'<li'.($this->isActive() ? ' class="active"' : '').'>'.
			'<a href="'.(empty($this->url) ? '#' : $this->url).'">'.
			(empty($this->icon) ? '' : '<i class="'.$this->icon.' icon-white"></i> ').
			$this->title.'</a></li>'.NEWLINE;
	}


	private function isActive() {
		$page = \psm\Portal::getPortal()->getPage();
		return ($this->name == $page);
	}


}
?>