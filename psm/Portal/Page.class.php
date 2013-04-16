<?php namespace psm\Portal;
global $ClassCount; $ClassCount++;
abstract class Page {

	// page paths
	private static $pagePaths = array();


	/**
	 *	Render page.
	 *
	 * @return string Returns the output html, or FALSE if failed.
	 */
	public abstract function Render();


	/**
	 * Handle an action.
	 *
	 * @param string $action Action to be performed.
	 */
	public abstract function Action($action);


	// add pages path
	public static function addPath($path) {
		$path = \psm\Utils\Files::TrimPath($path);
		if(!in_array($path, self::$pagePaths))
			self::$pagePaths[] = $path;
	}


}
?>