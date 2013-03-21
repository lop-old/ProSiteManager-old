<?php namespace psm\Portal;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
abstract class Page {

	// page paths
	private static $pagePaths = array();


	/**
	 *	Render page.
	 *
	 * @return string Returns the output html, or FALSE if failed.
	 */
	abstract public function Render();


	/**
	 * Handle an action.
	 *
	 * @param string $action Action to be performed.
	 */
	abstract protected function Action($action);


	// add pages path
	public static function addPath($path) {
		$path = \psm\Utils\Utils_Files::trimPath($path);
		if(!in_array($path, self::$pagePaths))
			self::$pagePaths[] = $path;
	}


}
?>