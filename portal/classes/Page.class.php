<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class Page {

	// page paths
	private static $paths = array();


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
	public static function LoadPage($page) {
		$page = \psm\Utils\Utils_Files::SanFilename($page);
		// default path - portalname/pages/
		if(count(self::$paths) == 0) {
			self::$paths = array(
				\psm\Utils\Utils_Files::mergePaths(
					\psm\PATH_ROOT,
					Portal::getPortal()->getPortalName(),
					'pages'
				)
			);
		}
		// look for page
		foreach(self::$paths as $v) {
			$file = DIR_SEP.\psm\Utils\Utils_Files::mergePaths($v, $page.'.php');
			// file not found
			if(!file_exists($file))
				continue;
			// load file
			$result = include($file);
			// file failed to load
			if($result === FALSE)
				continue;
			// portal name
			$portalName = Portal::getPortal()->getPortalName();
			// page class name
			$clss = '\\'.$portalName.'\\page_'.$page;
			// load page class
			if(class_exists($clss))
				return new $clss();
			return $result;
		}
echo '<p>Page not found!! '.$page.'</p>';
	}


	public static function addPath($path) {
		if(in_array($path, self::$paths)) return;
		self::$paths[] = $path;
	}


}
?>