<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class html_file {


	public static function LoadFile($theme, $filename) {
		$filepath = 'wa/html/'.Utils_File::SanFilename($theme).'/'.Utils_File::SanFilename($filename).'.html.php';
		if(!file_exists($filepath)) {
			echo '<p>File not found! '.$filepath.'</p>';
			exit();
		}
		include($filepath);
		$clss = '\wa\html_'.$filename;
		if(!class_exists($clss)) {
			echo '<p>Class not found! '.$clss.'</p>';
			exit();
		}
		return new $clss();
	}


	public function getBlock($blockName='') {
		$func = '_'.$blockName;
		if(!method_exists($this, $func))
			return null;
		return $this->$func();
	}


	protected static function addFileCSS() {
		foreach(func_get_args() as $file) {
			if(empty($file)) return '';
			$engine = Portal::getPortal()->getEngine();
			$engine->addHeader(
				'<link rel="stylesheet" type="text/css" href="'.$file.'" />'
			);
		}
	}
	protected static function addFileJS() {
		foreach(func_get_args() as $file) {
			if(empty($file)) continue;
			$engine = Portal::getPortal()->getEngine();
			$engine->addHeader(
				'<script type="text/javascript" language="javascript" src="'.$file.'"></script>'
			);
		}
	}


}
?>