<?php namespace psm\Widgets;
global $ClassCount; $ClassCount++;
class Widget_NavBar extends \psm\Widgets\NavBar\NavBar {


	public static function factory() {
		return new self();
	}


}
?>