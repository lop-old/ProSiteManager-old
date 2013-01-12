<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE')){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class config {


	// config variables (defaults)
	private $settings  = array();


	public static function loadConfig($configFile) {
echo '<p>LOAD CONFIG: '.$configFile.'</p>';
	}


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
		return $this->languages[$this->currentLanguage];
	}
	public function getLang($name) {
		return $this->getLanguage($name);
	}
	public function getLangMsg($name) {
		$lang = $this->getLanguage();
		if($lang == null)
			return null;
		return $lang->getMsg($name);
	}


}
?>