<?php namespace psm;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class msgPage {


	/**
	 *
	 *
	 */
	public static function Error($msg) {
		echo '<div style="background-color: #ffbbbb;">'.NEWLINE;
		echo '<h4>'.$msg.'</h4>'.NEWLINE;
		echo '<h3>Backtrace:</h3>'.NEWLINE;
//		echo '<pre>'.NEWLINE;
//		\debug_print_backtrace();
//		echo '</pre>'.NEWLINE;
		if(function_exists('Kint\trace'))
			\Kint::trace();
		echo '</div>'.NEWLINE;
		exit();
	}


}
?>