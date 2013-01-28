<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class settings {


	public static function Validate($clss) {
		if(!($clss instanceof self))
			die('<p>Not instance of '.__CLASS__.'!</p>');
		//TODO: throw exception
	}


}
?>