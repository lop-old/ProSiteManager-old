<?php namespace psm\Portal\Menu;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class Item {

	private $name     = NULL;
	private $title    = NULL;
	private $url      = NULL;
	private $icon     = NULL;
	private $selected = FALSE;
	private $priority = -1;
	private $writable = TRUE;

	private $subs = array();


	public function __construct($name=NULL, $title=NULL, $url=NULL, $icon=NULL, $selected=NULL) {
		if($name  !== NULL) $this->name = $name;
		if($title !== NULL) $this->title = $title;
		if($url   !== NULL) $this->url = $url;
		if($icon  !== NULL) $this->icon = $icon;
		if($selected !== NULL) $this->selected = ($selected == TRUE);
	}


	// name
	public function getName() {
		if($this->name === NULL)
			$this->name = $this->title;
		if(empty($this->name))
			$this->name = 'unknown';
		return $this->name;
	}


	// title
	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title) {
		if(!$this->writable) return;
		$this->title = $title;
	}


	// url
	public function getURL() {
		return $this->url;
	}
	public function setURL($url) {
 		if(!$this->writable) return;
 		if(empty($url))
			$url = NULL;
		$this->url = $url;
	}


	// selected
	public function isSelected($selected=NULL) {
		if($selected != NULL)
			$this->selected = ($selected == TRUE);
		return $this->selected;
	}


	// writable
	public function setWritable($writable=TRUE) {
		$this->writable = ($writable == TRUE);
	}
	public function isWritable() {
		return $this->writable;
	}


	// priority
	public function getPriority() {
		if($this->priority < 0)
			return -1;
		return $this->priority;
	}
	public function setPriority($priority=-1) {
		if(!$this->writable) return;
		if($priority < 0)
			$this->priority = -1;
		else
			$this->priority = \psm\Utils\Numbers::MinMax($priority, 0, 9);
	}


	// add a sub-menu (priority 0-9 or -1 for default)
	public function addSub(\psm\Portal\Menu\Item &$item, $priority=-1) {
		if(!$this->writable) return;
		$name = $item->getName();
		if($priority > -1)
			$item->setPriority($priority);
		if($item->getPriority() > -1) {
			$priority = \psm\Utils\Numbers::MinMax($priority, 0, 9);
			$name = $priority.'_'.$name;
		}
		$this->subs[$name] = &$item;
	}
	// get submenus
	public function getSubs() {
		return $this->subs;
	}
	public function hasSubs() {
		return (count($this->subs) > 0);
	}


}
?>