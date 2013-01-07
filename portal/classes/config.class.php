<?php namespace psm\config;
if(!defined('DEFINE_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class config {


	// config variables (defaults)
	private $settings  = array();


	// language
	private $currentLanguage = null;
	private $languages = array();
	// add language class
	public function addLanguage($name, $lang) {
		$this->languages[$name] = $lang;
	}
	public function addLang($name, $lang) {
		$this->addLanguage($name, $lang);
	}
	// get language class
	public function getLanguage($name) {
		if(!isset($languages[$name]))
			return null;
		return $languages[$this->currentLanguage];
	}
	public function getLang($name) {
		return getLanguage($name);
	}
	public function getLangMsg($name) {
		$lang = getLanguage();
		if($lang == null)
			return null;
		return $lang->getMsg($name);
	}


}
?>