<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Utils {


	/**
	 * Sends http headers to disable page caching.
	 *
	 * @return boolean True if successful; False if headers already sent.
	 */
	public static function NoPageCache() {
		if(self::$NoPageCache_hasRun) return FALSE;
		if(headers_sent()) return FALSE;
		@header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		@header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@header('Cache-Control: no-store, no-cache, must-revalidate');
		@header('Cache-Control: post-check=0, pre-check=0', false);
		@header('Pragma: no-cache');
		self::$NoPageCache_hasRun = TRUE;
		return TRUE;
	}
	private static $NoPageCache_hasRun = FALSE;


//	// forward to url (caution: doesn't exit if headers already sent)
//	/**
//	 *
//	 *
//	 * @return
//	 */
	public static function ForwardTo($url, $delay=0) {
		if(headers_sent() || $delay != 0) {
			echo '<header><meta http-equiv="refresh" content="'.((int)$delay).';url='.$url.'"></header>';
		} else {
			header('HTTP/1.0 302 Found');
			header('Location: '.$url);
		}
		exit();
	}
//	// scroll to bottom
//	/**
//	 *
//	 *
//	 * @return
//	 */
	public static function ScrollToBottom() {
		echo '<!-- ScrollToBottom() -->'."\n".
			'<script type="text/javascript"><!--//'."\n".
			'document.scrollTop=document.scrollHeight; '.
			'window.scroll(0,document.body.offsetHeight); '.
			'//--></script>';
	}


//	/**
//	 *
//	 *
//	 * @return boolean
//	 */
//	public static function startsWith($haystack, $needle, $ignoreCase=FALSE) {
//		if(empty($haystack) || empty($needle)) return FALSE;
//		if($ignoreCase){
//			$haystack = strtolower($haystack);
//			$needle   = strtolower($needle);}
//			return !strncmp($haystack, $needle, strlen($needle));
//		return (substr($haystack, 0, strlen($needle)) === $needle);
//	}
//	/**
//	 *
//	 *
//	 * @return boolean
//	 */
//	public static function endsWith($haystack, $needle, $ignoreCase=FALSE) {
//		if(empty($haystack) || empty($needle)) return FALSE;
//		if($ignoreCase){
//			$haystack = strtolower($haystack);
//			$needle   = strtolower($needle);}
//			$length   = strlen($needle);
//			if($length == 0) return FALSE;
//			return (substr($haystack, 0-$length) === $needle);
//	}


//	// render time
//	private static $qtime = -1;
//	/**
//	 *
//	 *
//	 * @return
//	 */
//	public static function GetTimestamp() {
//		$qtime = explode(' ', microtime(), 2);
//		return $qtime[0] + $qtime[1];
//	}
//	/**
//	 *
//	 *
//	 * @return
//	 */
//	public static function GetRenderTime($roundPlaces=3) {
//		if(self::$qtime == 0) return 0;
//		return round(self::GetTimestamp() - $qtime, $roundPlaces);
//	}
//	$config['qtime'] = GetTimestamp();


	/**
	 * Checks for GD support.
	 *
	 * @return True if GD functions are available.
	 */
	public static function GDSupported() {
		return(function_exists('gd_info'));
	}


//	// string to seconds
//	/**
//	 *
//	 *
//	 * @return
//	 */
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
//	// seconds to string
//	/**
//	 *
//	 *
//	 * @return
//	 */
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
	 * @return Roman numerals string representing input number.
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


//	/**
//	 *
//	 *
//	 * @return
//	 */
	public static function MinMax($value, $min=FALSE, $max=FALSE) {
		if($min !== FALSE) if($value < $min) $value = $min;
		if($max !== FALSE) if($value > $max) $value = $max;
		return $value;
	}


}
?>