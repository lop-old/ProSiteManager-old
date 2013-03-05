<?php namespace psm\ItemDefines;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class DefinesLoader {

	protected $array;


	protected abstract function LoadCategories();
	protected abstract function getPath();
	protected abstract function getType();


	public function __construct (\psm\ItemDefines\DefinesArray $array) {
		$this->array = $array;
		$this->LoadCategories();
	}


	protected function LoadCategory($category) {
		$path = $this->getPath().$category.'/'.$this->getType().'.xml';
		$this->LoadItems($path);
		$path = $this->getPath().$category.'/languages/'.$this->getLang().'.xml';
		$this->LoadLanguage($path);
	}


	private function LoadItems($filepath) {
		if(!file_exists($filepath)) {
echo '<p>Item pack path doesn\'t exist! '.$filepath.'</p>';
			return;
		}
		$data = file_get_contents($filepath);
		$xml = new \SimpleXMLElement($data);
		unset($data);
		foreach($xml as $child) {
			$this->_addNode($child);
		}
		unset($xml);
	}


	// parse item node
	private function _addNode(\SimpleXMLElement $node) {
		if($node->getName() != 'item') return;
		// item attributes
		$attribs = $node->attributes();
		// item id
		if(!isset($attribs['id'])) return;
		$id = (int) $attribs['id'];
		if($id < 1) return;
		// get item nodes
		$children = $node->children();
		// sub-items
		if($children[0]->getName() == 'subitem') {
			foreach($children as $child) {
				$attribs = $child->attributes();
				if(!isset($attribs['damage'])) continue;
				if($attribs['damage'] == 'default')
					$damage = (string) $attribs['damage'];
				else
					$damage = (int) $attribs['damage'];
				$this->array->addSub($id, $damage, $this->getItem($child));
			}
		// item
		} else {
			$this->array->add($id, $this->getItem($children));
		}
		unset($item);
	}



	private function getItem(\SimpleXMLElement $node) {
		$title   = '';
		$icon    = '';
		$sprite  = '';
		$spriteX = -1;
		$spriteY = -1;
		$emc     = -1;
		foreach($node as $name => $value) {
			// title
			if($name == 'title') {
				$title = (string) $value;
				continue;
			}
			// icon
			if($name == 'icon') {
				$icon = (string) $value;
				continue;
			}
			// sprite sheet
			if($name == 'sprite') {
				$sprite = (string) $value;
				$attribs = $value->attributes();
				$spriteX = (int) $attribs['x'];
				$spriteY = (int) $attribs['y'];
				continue; 
			}
			// emc
			if($name == 'emc') {
				$emc = (int) $value;
				continue;
			}
		}
		// create item array
		$array = array(
			'title' => $title,
			'icon' => $icon,
		);
		// sprite sheet
		if(!empty($sprite)) {
			$array['sprite']  = $sprite;
			$array['spriteX'] = $spriteX;
			$array['spriteY'] = $spriteY;
		}
		// emc
		if($emc > 0) {
			$array['emc'] = $emc;
		}
		return $array;
	}


	private function LoadLanguage($filepath) {
	}


	private function LoadCrafting($filepath) {
	}


	private function getLang() {
		return 'en';
	}


}
?>