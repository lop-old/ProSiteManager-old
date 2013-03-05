<?php namespace psm\Portal;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
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


	/**
	 * Loads a page class.
	 *
	 * @param string $page Name of the page to load.
	 * @return page Returns the page class instance, which can be rendered.
	 */
	public static function LoadPage($modName, $page) {
		$page = \psm\Utils\Utils_Files::SanFilename($page);
		// default path - mod/pages/
		if(count(self::$pagePaths) == 0)
			self::addPath(\psm\Portal::getLocalPath('module', $modName).DIR_SEP.'pages');
		// look for page
		foreach(self::$pagePaths as $v) {
			$file = DIR_SEP.$v.DIR_SEP.$page.'.php';
			// file not found
			if(!file_exists($file))
				continue;
			// load file
			$result = include($file);
			// file failed to load
			if($result === FALSE)
				continue;
			// module name
			$modName = \psm\Portal::getModName();
			// load page class
			$clss = $modName.'\Pages\page_'.$page;
			if(class_exists($clss))
				return new $clss();
			// string result
			return (string) $result;
		}
		return '<p>Page not found!! '.$page.'</p>';
	}


	// add pages path
	public static function addPath($path) {
		$path = \psm\Utils\Utils_Files::trimPath($path);
		if(!in_array($path, self::$pagePaths))
			self::$pagePaths[] = $path;
	}


}
?>