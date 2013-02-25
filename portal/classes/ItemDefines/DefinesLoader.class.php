<?php namespace psm\ItemDefines;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class DefinesLoader {


	protected abstract function getPath();
	protected abstract function getType();


	protected function LoadCategory($category) {
		$path = $this->getPath().$category.'/'.$this->getType().'.xml';
//echo '<p>'.$path.'</p>';
		$this->LoadItems($path);
		$path = $this->getPath().$category.'/languages/'.$this->getLang().'.xml';
//echo '<p>'.$path.'</p>';
		$this->LoadLanguage($path);




//		$xmlData = file_get_contents($packPath.'items.php');
//		$xmlData = file_get_contents($packPath.'crafting.php');
//		$xml = new \SimpleXMLElement($xmlData);
//		unset($xmlData);
//print_r($xml);







	}


	private function LoadItems($filepath) {
	}


	private function LoadLanguage($filepath) {
	}


	private function LoadCrafting($filepath) {
	}


	private function getLang() {
		return 'en';
	}








//	private $array;


//	public function __construct($array) {
//		$this->array = $array;
//	}


//	protected function &getArray() {
//		return $this->array;
//	}


//	protected function addCraft($id, $data=array()) {
//		$this->array[$id] = $data;
//	}


//	protected function addSmelt($id, $data=array()) {
//		$this->array[$id] = $data;
//	}


}
?>