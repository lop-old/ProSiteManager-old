<?php namespace psm;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
class listenerGlobalTags implements Listener {

	private $paths = array();


	public function trigger(&$args) {
		$data = &$args[0];
		self::renderPathTags($data);
		return $data;
	}


	// {path=...}
	public static function renderPathTags(&$data) {
		$data =	preg_replace_callback(
			'/\{path=(.*?)\}/s',
			array('\psm\listenerGlobalTags', '_pathCallback'),
			$data
		);
	}
	public static function _pathCallback($matches) {
		$match = $matches[1];
if($match == 'static')
return 'portal/static/';
if($match == 'theme')
return 'wa/html/default/';
print_r($matches);
exit();
	}


}
?>