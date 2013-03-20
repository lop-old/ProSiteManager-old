<?php namespace psm\Portal;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
final class PageLoader {
	private function __construct() {}


	/**
	 * Loads a page class.
	 *     default path - <mod>/pages/<page>.page.php
	 * @param string $page Name of the page to load.
	 * @return page Returns the page class instance, which can be rendered.
	 */
	public static function LoadPage($modName, $page) {
		$page = \psm\Utils\Utils_Files::SanFilename($page);
		// find page file
		$paths = \psm\Paths::getLocal('pages', $modName);
		$filepath = \psm\Utils\Utils_Files::findFile($page.'.page.php', $paths);
		// page not found
//TODO:
		if($filepath == NULL) {
			\psm\msgPage::Error('Page not found!! '.$page);
			return;
		}
		// load file
		$result = include($filepath);
		// file failed to load
//TODO:
		if($result === FALSE) {
			\psm\msgPage::Error('Failed to load page!! '.$page);
			return;
		}
		// module name
		$modName = \psm\Portal::getModName();
		// load page class
		$clss = $modName.'\Pages\page_'.$page;
		if(class_exists($clss))
			return new $clss();
		// string result
		return (string) $result;
	}


}
?>