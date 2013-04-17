<?php namespace psm\Widgets;
global $ClassCount; $ClassCount++;
class Widget_Wiki extends \psm\Widgets\Wiki\WikiPage {


	public static function factory() {
		return new self();
	}


}
?>