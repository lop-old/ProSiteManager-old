<?php

// class loader
function __autoload($name) {
//echo $name.'<br />';
	$parts = explode('\\', $name);
	$file = __DIR__.'/classes/'.end($parts).'.class.php';
//echo $file.'<br />';
	if(file_exists($file)) {
		include($file);
		return;
	}
	echo '<p style="color: red;">Unknown class: '.$name.'</p>';
}

?>