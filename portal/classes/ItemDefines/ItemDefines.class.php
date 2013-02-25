<?php namespace psm\ItemDefines;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class ItemDefines {

	protected static $itemsArray    = array();
	protected static $craftingArray = array();


	public function __construct() {
		self::LoadPack(self::$itemsArray,    'default', 'items');
		self::LoadPack(self::$craftingArray, 'default', 'crafting');
	}


	public static function LoadPack(&$array, $pack, $type) {
		$pack = \psm\Utils\Utils_Files::SanFilename($pack);
		$type = \psm\Utils\Utils_Files::SanFilename($type);
		if(\psm\Utils\Utils_Strings::endsWith($type, '.php'))
			$type = substr($type, 0, -4);
		$typePath = $type.'.php';
		$packPath = 'ItemPacks/'.$pack.'/';
		if(file_exists($packPath.$typePath)) {
			include($packPath.$typePath);
			$className = '\psm\ItemDefines\DefinesLoader_'.$pack.'_'.$type;
			if(class_exists($className)) {
				$clss = new $className();
			} else {
				echo '<p>ItemPack class not found! '.$className.'</p>';
			}
		} else {
			echo '<p>ItemPack not found! '.$pack.' : '.$type.'</p>';
		}
	}


}
?>