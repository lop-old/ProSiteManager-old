<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class bootstrap_NavBar {

	private $brand = NULL;
	private $items = array();
	private $itemsRight = array();


	public static function newNavBar() {
		return new bootstrap_NavBar();
	}
	public function __construct() {
	}


	public function setBrand($brand) {
		// convert from string
		if($brand != NULL)
			if(!($brand instanceof \psm\bootstrap_NavBar_Item))
				$brand = new \psm\bootstrap_NavBar_Item('brand', (string)$brand);
		$this->brand = $brand;
		return $this;
	}


	public function Render($isSubMenu=FALSE) {
		// generate nav bar
		$output =
			'<!-- '.($isSubMenu===TRUE ? '========' : '=========').' -->'.NEWLINE.
			'<!-- '.($isSubMenu===TRUE ? 'Sub-Menu' : 'Main-Menu').' -->'.NEWLINE;
		if($isSubMenu === TRUE)
			// sub menu
			$output .=
				'<div class="container" id="sub-menu">'.NEWLINE.
				'	<div class="navbar navbar-inverse">'.NEWLINE.
				'		<div class="navbar-inner">'.NEWLINE.
				'			<div class="container">'.NEWLINE;
		else
			// main menu
			$output .=
			'<div class="navbar navbar-fixed-top">'.NEWLINE.
			'	<div class="navbar-inner">'.NEWLINE.
			'		<div class="container">'.NEWLINE;
		if($this->brand != NULL)
			$output .= $this->brand->Render();
		// menu items
		$output .=
			'		<ul class="nav">'.NEWLINE;
		foreach($this->items as $item)
			$output .= $item->Render();
		$output .=
			'		</ul>'.NEWLINE;
		// menu items - right side
		if(count($this->itemsRight) > 0) {
			$output .=
				'		<ul class="nav pull-right">'.NEWLINE;
			foreach($this->itemsRight as $item)
				$output .= $item->Render();
			$output .=
				'		</ul>'.NEWLINE;
		}
		// close tags
		if($isSubMenu === TRUE)
			// sub menu
			$output .= NEWLINE.
				'			</div>'.NEWLINE.
				'		</div>'.NEWLINE.
				'	</div>'.NEWLINE.
				'</div>'.NEWLINE;
		else
			// main menu
			$output .=
				'		</div>'.NEWLINE.
				'	</div>'.NEWLINE.
				'</div>'.NEWLINE;
		$output .=
			'<!-- '.($isSubMenu===TRUE ? 'Sub-Menu' : 'Main-Menu').' -->'.NEWLINE.
			'<!-- '.($isSubMenu===TRUE ? '========' : '=========').' -->'.NEWLINE;
		return $output;
	}


	// add items
	public function add(\psm\bootstrap_NavBar_Item $item, $right=FALSE) {
		if($right === TRUE)
			Utils::appendArray($this->itemsRight, $item);
		else
			Utils::appendArray($this->items, $item);
		return $this;
	}
	// add menu button
	public function addButton($name, $title, $url, $icon, $right=FALSE) {
		$this->add( new bootstrap_NavBar_Item($name, $title, $url, $icon), $right );
		return $this;
	}
	// add dropdown menu
	public function addDropdown($name, $title, $url, $icon, $right=FALSE) {
		$this->add( new bootstrap_NavBar_Item($name, $title, $url, $icon, TRUE), $right );
		return $this;
	}
	// add menu break
	public function addBreak($right=FALSE) {
		$this->add( new bootstrap_NavBar_Item('-'), $right );
		return $this;
	}


}
?>