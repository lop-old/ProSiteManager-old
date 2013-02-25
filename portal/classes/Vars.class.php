<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Vars {


	// get,post,cookie (highest priority last)
	public static function getVar($name, $type, $source=array('get','post')) {
		if(!is_array($source))
			$source = @explode(',', (string)$source);
		$data = null;
		foreach($source as $v) {
			$d = null;
			// get
			if($v === 'g' || $v === 'get')
				$d = self::get($name, $type);
			else
			// post
			if($v === 'p' || $v === 'post')
				$d = self::post($name, $type);
			else
			// cookie
			if($v === 'c' || $v === 'cookie')
				$d = self::cookie($name, $type);
			// var found
			if($d !== null)
				$data = $d;
		}
		return $data;
	}
	// get var only
	public static function get($name, $type) {
		if(isset($_GET[$name]))
			return self::castType($_GET[$name], $type);
		return null;
	}
	// post var only
	public static function post($name, $type) {
		if(isset($_POST[$name]))
			return self::castType($_POST[$name], $type);
		return null;
	}
	// cookie var only
	public static function cookie($name, $type) {
		if(isset($_COOKIE[$name]))
			return self::castType($_COOKIE[$name], $type);
		return null;
	}


	// cast variable type
	public static function castType($data, $type) {
		$temp = strtolower( substr( (string)$type, 0, 1) );
		// string
		if($temp === 's')
			return (string) $data;
		// integer
		if($temp === 'i')
			return (integer) $data;
		// float/double
		if($temp === 'f' || $temp === 'd')
			return (float) $data;
		// boolean
		if($temp === 'b')
			return self::toBoolean($data);
		return $data;
	}
	// convert to boolean
	public static function toBoolean($value) {
		if(gettype($value) === 'boolean') return $value;
		$temp = strtolower( substr( (string)$value, 0, 1) );
		if($temp === 't' || $temp === 'y' || $temp === 'a') return TRUE;
		if($temp === 'f' || $temp === 'n' || $temp === 'd') return FALSE;
		return (boolean) $value;
	}


//	// php session
//	if(function_exists('session_status'))
//		if(session_status() == PHP_SESSION_DISABLED){
//		echo '<p>PHP Sessions are disabled. This is a requirement, please enable this.</p>';
//		exit();
//	}
//	session_init();


}
?>