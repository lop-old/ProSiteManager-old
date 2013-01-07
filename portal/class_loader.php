<?php
if(!defined('DEFINE_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}


// class loader
function __autoload($name) {
	$parts = explode('\\', $name);
	$file = 'portal/classes/'.end($parts).'.class.php';
	if(file_exists($file)) {
		include($file);
		return;
	}
	echo '<p style="color: red;">Unknown class: '.$name.'</p>';
}


?>