<?php namespace psm\Utils;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class session {

	private static $session = NULL;


	public static function factory() {
		if(self::$session == NULL)
			self::$session = new self();
		return self::$session;
	}
	// init session
	protected function __construct() {
//if(defined('SESSION_INIT_HAS_RUN')) return;
//if(function_exists('session_status'))
//if(session_status() == PHP_SESSION_ACTIVE) return;
//if(!is_writable(session_save_path())) {
//echo 'Session path "'.session_save_path().'" is not writable for PHP!';
////chmod 770 '.session_save_path().'
////chown root:apache '.session_save_path().'
////exit(); // avoid redirects
//}
//session_start();
//define('SESSION_INIT_HAS_RUN', TRUE);
	}


	public function getString($key) {
		if(!$this->keyExists($key))
			return '';
		return $_SESSION[$key];
	}


	public function keyExists($key) {
		return isset($_SESSION[$key]);
	}


}
?>