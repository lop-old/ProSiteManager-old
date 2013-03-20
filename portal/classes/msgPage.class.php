<?php namespace psm;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
global $ClassCount; $ClassCount++;
class msgPage {


	/**
	 *
	 *
	 */
	public static function Error($msg='') {
		echo '<div style="background-color: #ffbbbb;">'.NEWLINE;
		if(!empty($msg))
			echo '<h4>Error: '.$msg.'</h4>'.NEWLINE;
		echo '<h3>Backtrace:</h3>'.NEWLINE;
//		echo '<pre>'.NEWLINE;
//		\debug_print_backtrace();
//		echo '</pre>'.NEWLINE;
		if(method_exists('Kint', 'trace'))
			\Kint::trace();
		else
			echo '<pre>'.print_r(debug_backtrace(), TRUE).'</pre>';
		echo '</div>'.NEWLINE;
		\psm\Portal::Unload(); exit();
	}


}
?>