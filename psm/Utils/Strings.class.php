<?php namespace psm\Utils;
global $ClassCount; $ClassCount++;
final class Strings {
	private function __construct() {}


	public static function Trim($text, $alsoTrim=NULL) {
		if(!is_array($alsoTrim)) {
			if(empty($alsoTrim))
				$alsoTrim = array();
			else
				$alsoTrim = \str_split($alsoTrim, 1);
		}
		while(TRUE) {
			if(!self::_Trim(\substr($text, 0, 1), $alsoTrim))
				break;
			$text = \substr($text, 1);
		}
		while(TRUE) {
			if(!self::_Trim(\substr($text, -1, 1), $alsoTrim))
				break;
			$text = \substr($text, 0, -1);
		}
		return $text;
	}
	// return true if the char should be trimmed
	private static function _Trim($char, $alsoTrim) {
		if($char == ' '  || $char == "\t") return TRUE;
		if($char == "\n" || $char == "\r") return TRUE;
		if(!empty($alsoTrim))
			foreach($alsoTrim as $one)
				if($char == $one)
					return TRUE;
		return FALSE;
	}


	/**
	 * 
	 */
	public static function Contains($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		if($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		return (strpos($haystack, $needle) !== FALSE);
	}


	/**
	 *
	 *
	 * @return boolean
	 */
	public static function StartsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		if($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		$length = \strlen($needle);
		if($length == 0)
			return FALSE;
		return (\substr($haystack, 0, $length) === $needle);
	}
	/**
	 *
	 *
	 * @return boolean
	 */
	public static function EndsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		if($ignoreCase){
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		$length = \strlen($needle);
		if($length == 0)
			return FALSE;
		return (\substr($haystack, 0-$length) === $needle);
	}


	/**
	 *
	 *
	 */
	public static function forceStartsWith(&$haystack, $needle) {
		if(empty($haystack) || empty($needle))
			return;
		if(!self::StartsWith($haystack, $needle))
			$haystack = $needle.$haystack;
	}
	/**
	 *
	 *
	 */
	public static function forceEndsWith(&$haystack, $needle) {
		if(empty($haystack) || empty($needle))
			return;
		if(!self::EndsWith($haystack, $needle))
			$haystack = $haystack.$needle;
	}


}
?>