<?php namespace psm\Widgets\NavBar;
global $ClassCount; $ClassCount++;
class NavBar extends \psm\Widgets\Widget {

	private $brand = NULL;
	private $items = array();
	private $itemsRight = array();
	private $selected = NULL;


	public function setBrand($brand) {
		// convert from string
		if($brand != NULL)
			if(!($brand instanceof NavBar_Item))
				$brand = new NavBar_Item('brand', (string)$brand);
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
				TAB.'<div class="navbar navbar-inverse">'.NEWLINE.
				TAB.TAB.'<div class="navbar-inner">'.NEWLINE.
				TAB.TAB.TAB.'<div class="container">'.NEWLINE;
		else
			// main menu
			$output .=
			'<div class="navbar navbar-fixed-top">'.NEWLINE.
			TAB.'<div class="navbar-inner">'.NEWLINE.
			TAB.TAB.'<div class="container">'.NEWLINE;
		if($this->brand != NULL)
			$output .= $this->brand->Render();
		// menu items
		$output .=
			TAB.TAB.'<ul class="nav">'.NEWLINE;
		foreach($this->items as $item)
			$output .= $item->Render();
		$output .=
			TAB.TAB.'</ul>'.NEWLINE;
		// menu items - right side
		if(count($this->itemsRight) > 0) {
			$output .=
				TAB.TAB.'<ul class="nav pull-right">'.NEWLINE;
			foreach($this->itemsRight as $item)
				$output .= $item->Render();
			$output .=
				TAB.TAB.'</ul>'.NEWLINE;
		}
		// close tags
		if($isSubMenu === TRUE)
			// sub menu
			$output .= NEWLINE.
				TAB.TAB.TAB.'</div>'.NEWLINE.
				TAB.TAB.'</div>'.NEWLINE.
				TAB.'</div>'.NEWLINE.
				'</div>'.NEWLINE;
		else
			// main menu
			$output .=
				TAB.TAB.'</div>'.NEWLINE.
				TAB.'</div>'.NEWLINE.
				'</div>'.NEWLINE;
		$output .=
			'<!-- '.($isSubMenu===TRUE ? 'Sub-Menu' : 'Main-Menu').' -->'.NEWLINE.
			'<!-- '.($isSubMenu===TRUE ? '========' : '=========').' -->'.NEWLINE;
		return $output;
	}


	// set selected
	public function setSelected($selected) {
		$this->selected = $selected;
		return $this;
	}


	// add items
	public function add(\psm\Widgets\NavBar\NavBar_Item $item, $right=FALSE) {
		if($right === TRUE)
			\psm\Utils\Utils::appendArray($this->itemsRight, $item);
		else
			\psm\Utils\Utils::appendArray($this->items, $item);
		return $this;
	}
	// add menu button
	public function addButton($name, $title, \psm\Portal\URL $url, $icon, $right=FALSE) {
		$isSelected = ($this->selected == $name);
		$this->add( new NavBar_Item($name, $title, $url, $icon, FALSE, $isSelected), $right );
		return $this;
	}
	// add dropdown menu
	public function addDropdown($name, $title, \psm\Portal\URL $url, $icon, $right=FALSE) {
		$isSelected = ($this->selected == $name);
		$this->add( new NavBar_Item($name, $title, $url, $icon, TRUE, $isSelected), $right );
		return $this;
	}
	// add menu break
	public function addBreak($right=FALSE) {
		$this->add( new NavBar_Item('-'), $right );
		return $this;
	}


}
?>