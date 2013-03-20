<?php namespace psm\Utils;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
class Utils {


	/**
	 * Validates the type of class for an object.
	 *
	 * @return boolean Returns true if object is the type $className.
	 */
	public static function isClass($className, $clss) {
		//echo '<p>$className - '.$className.'</p>';
		//echo '<p>get_class($clss) - '.get_class($clss).'</p>';
		//echo '<p>get_parent_class($clss) - '.get_parent_class($clss).'</p>';
		return
			get_class($clss) == $className ||
			is_subclass_of($clss, $className
		);
	}
	/**
	 * Validates the type of class for an object, throwing an exception
	 * if invalid.
	 */
	public static function Validate($className, $clss) {
		if(!self::isClass($className, $clss))
			\psm\msgPage::Error("Class object isn't of type ".$className);
	}


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


	/**
	 *
	 *
	 * @param string[] $array
	 * @param string $data
	 * @param boolean $top
	 */
	public static function appendArray(&$array, &$data, $top=FALSE) {
		// top of array
		if($top) array_unshift($array, $data);
		// bottom of array
		else     array_push($array, $data);
	}


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


}
?>