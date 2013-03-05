<?php namespace psm\ItemDefines;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class ItemDefines {

	protected static $itemsArray    = null;
	protected static $craftingArray = null;


	public function __construct() {
		self::$itemsArray    = new \psm\ItemDefines\DefinesArray();
		self::$craftingArray = new \psm\ItemDefines\DefinesArray();
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
				if($type == 'items')
					$clss = new $className(self::$itemsArray);
				else
				if($type == 'crafting')
					$clss = new $className(self::$craftingArray);
			} else {
				echo '<p>ItemPack class not found! '.$className.'</p>';
			}
		} else {
			echo '<p>ItemPack not found! '.$pack.' : '.$type.'</p>';
		}
	}


	public function &getItemsArray() {
		return self::$itemsArray->getArray();
	}


}
?>