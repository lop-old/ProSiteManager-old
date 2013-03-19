<?php namespace psm\Utils;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Utils_Strings {


	public static function trim($text) {
		while(TRUE) {
			if(!self::_trim(substr($text, 0, 1)))
				break;
			$text = \substr($text, 1);
		}
		while(TRUE) {
			if(!self::_trim(substr($text, -1, 1)))
				break;
			$text = \substr($text, 0, -1);
		}
		return $text;
	}
	private static function _trim($char) {
		if($char == ' '  || $char == "\t") return TRUE;
		if($char == "\n" || $char == "\r") return TRUE;
		return FALSE;
	}


	/**
	 *
	 *
	 * @return boolean
	 */
	public static function startsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		if($ignoreCase) {
			$haystack = strtolower($haystack);
			$needle   = strtolower($needle);
		}
		$length = strlen($needle);
		if($length == 0)
			return FALSE;
		return (substr($haystack, 0, $length) === $needle);
	}
	/**
	 *
	 *
	 * @return boolean
	 */
	public static function endsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		if($ignoreCase){
			$haystack = strtolower($haystack);
			$needle   = strtolower($needle);
		}
		$length = strlen($needle);
		if($length == 0)
			return FALSE;
		return (substr($haystack, 0-$length) === $needle);
	}


	/**
	 *
	 *
	 */
	public static function forceStartsWith(&$haystack, $needle) {
		if(empty($haystack) || empty($needle))
			return;
		if(!self::startsWith($haystack, $needle))
			$haystack = $needle.$haystack;
	}
	/**
	 *
	 *
	 */
	public static function forceEndsWith(&$haystack, $needle) {
		if(empty($haystack) || empty($needle))
			return;
		if(!self::endsWith($haystack, $needle))
			$haystack = $haystack.$needle;
	}


}
?>