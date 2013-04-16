<?php namespace psm\Portal;
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
		$page = \psm\Utils\DirsFiles::SanFilename($page);
		// find page file
		$paths = \psm\Paths::getLocal('pages', $modName);
		$filepath = \psm\Utils\DirsFiles::FindFile($page.'.page.php', $paths);
		// page not found
//TODO:
		if($filepath == NULL) {
			\psm\Portal::Error('Page not found!! '.$page);
			return;
		}
		// load file
		$result = include($filepath);
		// file failed to load
//TODO:
		if($result === FALSE) {
			\psm\Portal::Error('Failed to load page!! '.$page);
			return;
		}
		// module name
		$modName = \psm\Portal::getModName();
		// load page class
		$clss = $modName.'\\Pages\\page_'.$page;
		if(\class_exists($clss))
			return new $clss();
		// string result
		return (string) $result;
	}


}
?>