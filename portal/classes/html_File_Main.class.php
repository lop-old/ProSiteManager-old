<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class html_File_Main extends html_File {

	private static $htmlMain = NULL;


	public function __construct() {
		if(self::$htmlMain != NULL)
			die('<p>Main html file has already been loaded!</p>');
		self::$htmlMain = $this;
		// define empty blocks
		$header = ''; $this->addBlock('header', $header);
		$css = '';    $this->addBlock('css',    $css);
		$js = '';     $this->addBlock('js',     $js);
		$page = '';   $this->addBlock('page',   $page);
		$footer = ''; $this->addBlock('footer', $footer);
	}


	// add css file
	public static function addFileCSS() {
		// loop arguments
		foreach(func_get_args() as $file)
			self::addFileCSS_single($file);
	}
//	protected static function addFileCSS_ifExists() {
//TODO:
//	}
	public static function addFileCSS_single($file, $top=FALSE) {
		if(empty($file)) return;
		Portal::getEngine()->addHeader(
			NEWLINE.
			'<link rel="stylesheet" type="text/css" href="'.$file.'" />'.
			NEWLINE,
			$top
		);
	}


	// add js file
	public static function addFileJS() {
		// loop arguments
		foreach(func_get_args() as $file)
			self::addFileJS_single($file);
	}
//	protected static function addFileJS_ifExists($file) {
//TODO:
//	}
	public static function addFileJS_top($file) {
		// loop arguments
		foreach(array_reverse(func_get_args()) as $file)
			self::addFileJS_single($file, TRUE);
	}
	public static function addFileJS_single($file, $top=FALSE) {
		if(empty($file)) return;
		Portal::getEngine()->addHeader(
			str_replace(
				'{file}',
				$file,
				self::getMain()->getBlock('includeJS')
			),
			TRUE
		);
	}


	protected function _includeCSS() {
		return '
<link rel="stylesheet" type="text/css" href="{file}" />
';
	}


	protected function _includeJS() {
		return '
<script type="text/javascript" language="javascript" src="{file}"></script>
';
	}


	public static function getMain() {
//		if(self::$htmlMain == NULL)
//			self::$htmlMain = html_File::LoadFile('default', 'main');
		return self::$htmlMain;
	}



}
?>