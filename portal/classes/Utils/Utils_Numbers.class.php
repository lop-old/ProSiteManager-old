<?php namespace psm\Utils;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Utils_Numbers {


	// String to seconds
	/**
	 *
	 *
	 * @return int seconds
	 */
	public static function toSeconds($text) {
		$a = substr($text, -1, 1);
		if($a == 'm') return ((int) $text) * 60;
		if($a == 'h') return ((int) $text) * 3600;
		if($a == 'd') return ((int) $text) * 86400;
		if($a == 'w') return ((int) $text) * 604800;
		if($a == 'n') return ((int) $text) * 2592000;
		if($a == 'y') return ((int) $text) * 31536000;
		              return  (int) $text;
	}
	// Seconds to string
	/**
	 *
	 *
	 * @return string
	 */
	public static function fromSeconds($seconds) {
		$result = '';
		// years
		if($seconds > 31536000) {
			$t = floor($seconds / 31536000);
			$seconds = $seconds % 31536000;
			$result .= ' '.$t.' Year'.
				($t>1 ? 's' : '');
		}
		// days
		if($seconds > 86400) {
			$t = floor($seconds / 86400);
			$seconds = $seconds % 86400;
			$result .= ' '.$t.' Day'.
				($t>1 ? 's' : '');
		}
		// hours
		if($seconds > 3600) {
			$t = floor($seconds / 3600);
			$seconds = $seconds % 3600;
			$result .= ' '.$t.' Hour'.
				($t>1 ? 's' : '');
		}
		// minutes
		if($seconds > 60) {
			$t = floor($seconds / 60);
			$seconds = $seconds % 60;
			$result .= ' '.$t.' Minute'.
				($t>1 ? 's' : '');
		}
		// seconds
		if($seconds > 0) {
			$result .= ' '.$seconds.' Second'.
				($seconds>1 ? 's' : '');
		}
		// trim extra space
		if(substr($result, 0, 1) == ' ')
			$result = substr($result, 1);
		return $result;
	}


	/**
	 * Convert a number to roman numerals
	 *
	 * @return string Roman numerals string representing input number.
	 */
	public static function NumberToRoman($number) {
		if($number > 15) return (string) $number;
		$number = (int) $number;
		$result = '';
		$lookup = array(
			'M' => 1000,
			'CM'=> 900,
			'D' => 500,
			'CD'=> 400,
			'C' => 100,
			'XC'=> 90,
			'L' => 50,
			'XL'=> 40,
			'X' => 10,
			'IX'=> 9,
			'V' => 5,
			'IV'=> 4,
			'I' => 1
		);
		foreach($lookup as $roman => $value) {
			$matches = intval($number / $value);
			$result .= str_repeat($roman, $matches);
			$number = $number % $value;
		}
		return $result;
	}


	/**
	 * Check the min and max of a value and return the result.
	 *
	 *
	 * @return int value
	 */
	public static function MinMax($value, $min=FALSE, $max=FALSE) {
		if($min !== FALSE) if($value < $min) $value = $min;
		if($max !== FALSE) if($value > $max) $value = $max;
		return $value;
	}


}
?>