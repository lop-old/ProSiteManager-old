<?php namespace psm\html;
global $ClassCount; $ClassCount++;
abstract class tplFile_Main extends tplFile {

	private static $htmlMain = NULL;

	private $header = '';
	private $css    = '';
	private $js     = '';
	private $page   = '';
	private $footer = '';


	protected function __construct() {
		if(self::$htmlMain != NULL)
			\psm\Portal::Error('Main html file has already been loaded!');
		self::$htmlMain = $this;
		// define empty blocks
		$this->blocks['header'] = &$this->header;
		$this->blocks['css']    = &$this->css;
		$this->blocks['js']     = &$this->js;
		$this->blocks['page']   = &$this->page;
		$this->blocks['footer'] = &$this->footer;
//echo 'sadgsdhgh435635476';
	}


	// add css file
	public static function addFileCSS() {
		// loop arguments
		foreach(\func_get_args() as $file)
			self::addFileCSS_single($file);
	}
//	protected static function addFileCSS_ifExists() {
//TODO:
//	}
	public static function addFileCSS_single($file, $top=FALSE) {
		if(empty($file)) return;
		\psm\Portal::getEngine()->addHeader(
			NEWLINE.
			'<link rel="stylesheet" type="text/css" href="'.$file.'" />'.
			NEWLINE,
			$top
		);
	}


	// add js file
	public static function addFileJS() {
		// loop arguments
		foreach(\func_get_args() as $file)
			self::addFileJS_single($file);
	}
//	protected static function addFileJS_ifExists($file) {
//TODO:
//	}
	public static function addFileJS_top($file) {
		// loop arguments
		foreach(\array_reverse(func_get_args()) as $file)
			self::addFileJS_single($file, TRUE);
	}
	public static function addFileJS_single($file, $top=FALSE) {
		if(empty($file)) return;
		\psm\Portal::getEngine()->addHeader(
			\str_replace(
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