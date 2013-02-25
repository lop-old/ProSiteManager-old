<?php namespace psm\Utils;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Utils_Strings {


	/**
	 *
	 *
	 * @return boolean
	 */
	public static function startsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle)) return FALSE;
		if($ignoreCase){
			$haystack = strtolower($haystack);
			$needle   = strtolower($needle);}
			return !strncmp($haystack, $needle, strlen($needle));
		return (substr($haystack, 0, strlen($needle)) === $needle);
	}
	/**
	 *
	 *
	 * @return boolean
	 */
	public static function endsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle)) return FALSE;
		if($ignoreCase){
			$haystack = strtolower($haystack);
			$needle   = strtolower($needle);}
			$length   = strlen($needle);
			if($length == 0) return FALSE;
			return (substr($haystack, 0-$length) === $needle);
	}


}
?>