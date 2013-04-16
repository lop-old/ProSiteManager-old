<?php namespace psm\Utils;
global $ClassCount; $ClassCount++;
final class Utils {
	private function __construct() {}


	/**
	 * Sends http headers to disable page caching.
	 *
	 * @return boolean True if successful; False if headers already sent.
	 */
	public static function NoPageCache() {
		if(self::$NoPageCache_hasRun) return FALSE;
		if(\headers_sent()) return FALSE;
		@\header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		@\header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@\header('Cache-Control: no-store, no-cache, must-revalidate');
		@\header('Cache-Control: post-check=0, pre-check=0', false);
		@\header('Pragma: no-cache');
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
		if(\headers_sent() || $delay != 0) {
			echo '<header><meta http-equiv="refresh" content="'.((int)$delay).';url='.$url.'"></header>';
		} else {
			\header('HTTP/1.0 302 Found');
			\header('Location: '.$url);
		}
		exit();
	}
//	// scroll to bottom
//	/**
//	 *
//	 *
//	 * @return
//	 */
	public static function ScrollToBottom($id='') {
		if(empty($id)) $id = 'document';
		echo NEWLINE.'<!-- ScrollToBottom() -->'.NEWLINE.
			'<script type="text/javascript"><!--//'.NEWLINE.
			$id.'.scrollTop='.$id.'.scrollHeight; '.
			'window.scroll(0,document.body.offsetHeight); '.
			'//--></script>'.NEWLINE.NEWLINE;
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
		if($top) \array_unshift($array, $data);
		// bottom of array
		else     \array_push($array, $data);
	}


//	public static function msSleep($ms) {
//		\usleep(
//			((int)$ms) * 1000
//		);
//	}
//	// render time
//	private static $qtime = -1;
//	/**
//	 *
//	 *
//	 * @return
//	 */
//	public static function GetTimestamp() {
//		$qtime = \explode(' ', \microtime(), 2);
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
//	// render time
//	public static function GetTimestamp(){
//		$qtime = \explode(' ', \microtime());
//		return $qtime[0] + $qtime[1];
//	}
//	public static function GetRenderTime($roundnum=3){
//		global $qtime;
//		if($qtime == 0) return 0;
//		return round(self::GetTimestamp() - $qtime, $roundnum);
//	}
//	echo \psm\Utils::GetTimestamp();


	/**
	 * Checks for GD support.
	 *
	 * @return True if GD functions are available.
	 */
	public static function GDSupported() {
		return \function_exists('gd_info');
	}


}
?>