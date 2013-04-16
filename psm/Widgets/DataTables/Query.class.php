<?php namespace psm\Widgets\DataTables;
global $ClassCount; $ClassCount++;
abstract class Query {

	// Note: don't initialize database object until needed.
	//       This class may load without being used.

	public abstract function runQuery();
	public abstract function getRow();

}
?>