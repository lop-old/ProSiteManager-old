<?php namespace psm\Portal;
global $ClassCount; $ClassCount++;
class URL {

	private $mod  = NULL;
	private $page = NULL;
	private $vars = array();
	private $rawURL = NULL;


	public static function factory() {
		return new self();
	}
	public function __construct() {
		$this->websitePath = \psm\Paths::getWeb('base');
	}


	// url to string
	public static function toString($url) {
		if(empty($url))
			return '';
		if(\psm\Utils\FuncArgs::classEquals('psm\\Portal\\URL', $url))
			return (string) $url->getURL();
		return (string) $url;
	}


	public function getURL() {
		if(!empty($this->rawURL))
			return $this->rawURL;
		$args = array();
		if(!empty($this->mod))  $args['mod']  = $this->mod;
		if(!empty($this->page)) $args['page'] = $this->page;
		foreach($this->vars as $name => $value)
			$args[$name] = $value;
		// assemble url
		$url = '';
		$i = 0;
		foreach($args as $name => $value) {
			$i++; if($i != 1) $url .= '&';
			$url .= $name.'='.$value;
		}
		return './?'.$url;
	}


	// mod name
	public function setMod($mod) {
		if(empty($mod))
			$mod = NULL;
		$this->mod = $mod;
		return $this;
	}
	// page name
	public function setPage($page) {
		if(empty($page))
			$page = NULL;
		$this->page = $page;
		return $this;
	}


	// custom url vars
	public function setVar($name, $value) {
		if(empty($name)) \psm\Portal::Error('URL var name is empty!');
		if(empty($value))
			unset($this->vars[$name]);
		else
			$this->vars[$name] = $value;
		return $this;
	}


	// raw url override
	public function setRawURL($rawURL) {
		if(empty($rawURL))
			$rawURL = NULL;
		$this->rawURL = $rawURL;
		return $this;
	}


}
?>