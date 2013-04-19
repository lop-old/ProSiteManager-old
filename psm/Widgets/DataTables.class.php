<?php namespace psm\Widgets;
global $ClassCount; $ClassCount++;
class DataTables extends \psm\Widgets\DataTables\Table {


	public static function factory($headings, $queryClass, $usingAjax=FALSE) {
		return new self($headings, $queryClass, $usingAjax);
	}


}
?>