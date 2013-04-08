<?php namespace psm;
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
use \psm\Utils as Utils;
global $ClassCount; $ClassCount++;
// portal core
class Portal {

	// portal instance
	private static $portal = null;
	// module instances
	private static $modules = array();
	// selected module;
	private static $moduleName = NULL;

	// page
	private static $pageObj = NULL;
	private static $defaultPage = 'home';
	// action
	private $action = NULL;


	public static function auto($args=array()) {
// World Groups
//$a = array(
//	'Survival' => array('world', 'pvp'),
//	'Creative' => array('clean'),
//);
//$data = json_encode($a);
//$orig = $data;
//echo $orig.'<br />';
//
//$data = str_replace(
//	array('{', '}', ':', ',' ),
//	array('' , '' , ' ', ', '),
//	$data);
//
//echo $data.'<br />';
//
//$data = str_replace(
//	array(', ', ' '),
//	array(',' , ':'),
//	$data);
//$data = '{'.$data.'}';
//
//echo $data.'<br />';
//echo '<br />'.($data == $orig);
//exit();

		if(self::$portal != NULL)
			return self::$portal;
		// no page caching
		Utils\Utils::NoPageCache();
		// set timezone
		try {
			//TODO: will make a config entry for this
			if(!@\date_default_timezone_get())
				@\date_default_timezone_set('America/New_York');
		} catch(\Exception $ignore) {}
		// parse portal args
		if(empty($args)) $args = array();
		if(!is_array($args)) $args = array($args);
		foreach($args as $key => $value) {
			$key = \str_replace('_', ' ', $key);
			// default module
			if($key == 'default module' && !defined('psm\\DEFAULT_MODULE'))
				define('psm\\DEFAULT_MODULE', (string) $value);
			else
			// module to load
			if($key == 'module' && !defined('psm\\MODULE'))
				define('psm\\MODULE', (string) $value);
			// unknown argument
			else
				echo '<p>Unknown argument! '.$key.' - '.$value.'</p>';
		}
		// load mods.txt
		self::LoadModules();
		self::getModName();
		self::getPage();

		// load portal instance
		self::$portal = new \psm\Portal();
	}


	// new portal instance
	public function __construct() {
		// portal instance
		if(self::$portal != NULL)
			self::Error('Portal already loaded!');
		// no modules loaded
		if(count(self::$modules) == 0)
			self::Error('No modules/plugins loaded!');
	}


	// destruct portal
	public function __destruct() {
		// load page if not already done
		if(self::$pageObj == NULL) {
			$mod = self::getModObj();
			if($mod == NULL)
				self::Error('Module not found! '.self::getModName());
			$mod->Init();
		}
		// render if not already done
		if(!\psm\html\Engine::hasDisplayed())
			self::getEngine()->Display();
		// unload modules
		self::$modules = NULL;
		self::$modules = array();
//		$array = array_keys(self::$modules);
//		foreach($array as $key)
//			unset($modules[$key]);
// unload html engine
		\psm\html\Engine::Unload();
		// unload db
//TODO: removing this
//		\psm\pxdb\dbPool::CloseAll();
		@\ob_end_flush();
	}
	public static function Unload() {
		self::$portal = null;
		unset(self::$portal);
		exit();
	}


	// load modules
	public static function LoadModules() {
		// already loaded
		if(\psm\Portal::getPortal() != NULL)
			return;
		if(count(self::$modules) == 0)
			self::LoadModulesTxt(
				self::$modules,
				\psm\Paths::getLocal('root').DIR_SEP.'mods.txt'
			);
		if(count(self::$modules) == 0)
			self::Error('No modules/plugins loaded!');
	}
	// load mods.txt modules list
	public static function LoadModulesTxt(&$mods, $modsFile) {
		\psm\Utils\Strings::forceEndsWith($modsFile, '.txt');
		// mods.txt file not found
		if(!\file_exists($modsFile))
			\psm\Portal::Error('Modules list file not found!');
		$data = \file_get_contents($modsFile);
		$array = \explode("\n", $data);
		foreach($array as $line) {
			$line = \trim($line);
			if(empty($line)) continue;
			// already loaded
			if(isset($mods[$line])) continue;
			// load module
			$mod = self::LoadModule($line);
			// failed to load
			if($mod == null) continue;
			// mod loaded successfully
			$mods[$line] = $mod;
		}
	}
	// load a module
	private static function LoadModule($name) {
		// file mod/mod.php
		$file = \psm\Paths::getLocal('module', $name).DIR_SEP.$name.'.php';
		// module file not found
		if(!\file_exists($file)) {
			//TODO:
			echo '<p>Module file not found! '.$name.'</p>';
			return;
		}
		include $file;
		// class \mod\module_mod
		$clss = $name.'\\module_'.$name;
		// class not found
		if(!\class_exists($clss)) {
			//TODO:
			echo '<p>Module class not found! '.$clss.'</p>';
			return;
		}
		return new $clss();
	}


	/**
	 * Gets the main portal instance
	 *
	 * @return Portal
	 */
	public static function getPortal() {
		return self::$portal;
	}


	/**
	 * Gets the main template engine instance, creating a new one if needed.
	 *
	 * @return html_Engine
	 */
	public static function getEngine() {
		return \psm\html\Engine::getEngine();
	}


	// debug mode
	public static function isDebug() {
		return defined('psm\\DEBUG') && \psm\DEBUG === TRUE;
	}


	// error page
	public static function Error($msg) {
		echo '<div style="background-color: #ffbbbb;">'.NEWLINE;
		if(!empty($msg))
			echo '<h4>Error: '.$msg.'</h4>'.NEWLINE;
		echo '<h3>Backtrace:</h3>'.NEWLINE;
		if(\method_exists('Kint', 'trace'))
			\Kint::trace();
		else
			echo '<pre>'.print_r(debug_backtrace(), TRUE).'</pre>';
		echo '</div>'.NEWLINE;
		\psm\Portal::Unload();
		exit();
	}


	/**
	 * Sets the website title.
	 *
	 *
	 */
	public function setTitle($title) {
		//TODO:
	}
	/**
	 * Gets the website title.
	 *
	 * @return string
	 */
	public function getTitle() {
		//TODO:
	}


	/**
	 * Gets the portal theme.
	 *
	 *
	 */
	public static function getPortalTheme() {
		return 'default';
	}


	// action
	public static function getAction() {
		// already set
		if($this->action !== NULL)
			return $this->action;
		// get action
		$this->action = Utils\Vars::getVar('action', 'str');
		$this->action = Utils\DirsFiles::SanFilename($this->action);
		return $this->action;
	}


	// get module name
	public static function getModName() {
		// module already defined
		if(defined('psm\\MODULE')) return \psm\MODULE;
		// get module from url
		self::setModName(
			Utils\Vars::getVar('mod', 'str')
		);
		if(defined('psm\\MODULE')) return \psm\MODULE;
		// default module define
		if(defined('psm\\DEFAULT_MODULE'))
			self::setModName(\psm\DEFAULT_MODULE);
		if(defined('psm\\MODULE')) return \psm\MODULE;
		// first listed mod
		if(count(self::$modules) > 0) {
			$mod = reset(self::$modules);
			Utils\FuncArgs::classValidate('psm\\Portal\\Module', $mod);
			self::setModName(
				$mod->getModName()
			);
		}
		// unknown module
		return NULL;
	}
	private static function setModName($modName) {
		if(empty($modName)) return;
		if(defined('psm\MODULE')) return;
		define(
			'psm\\MODULE',
			Utils\DirsFiles::SanFilename(
				$modName
			)
		);
	}
//	// default module
//	public static function setDefaultModName($modName) {
//		if(empty($modName)) return;
//		if(defined('psm\DEFAULT_MODULE')) return;
//		define(
//			'psm\DEFAULT_MODULE',
//			Utils\DirsFiles::SanFilename(
//				$modName
//			)
//		);
//	}
	public static function getModObj($modName='') {
		if(empty($modName))
			$modName = self::getModName();
		if(!isset(self::$modules[$modName]))
			return NULL;
		return self::$modules[$modName];
	}


	// get page
	public static function getPage() {
		// page already defined
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// get page from url
		self::setPage(
			Utils\Vars::getVar('page', 'str')
		);
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// default page define
		if(defined('psm\\DEFAULT_PAGE'))
			self::setPage(\psm\DEFAULT_PAGE);
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// default page portal var
		self::setPage(self::$defaultPage);
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// unknown page
		return '404';
	}
	private static function setPage($page) {
		if(empty($page)) return;
		if(defined('psm\\PAGE')) return;
		define(
			'psm\\PAGE',
			Utils\DirsFiles::SanFilename(
				$page
			)
		);
	}
	// default page
	public static function setDefaultPage($defaultPage) {
		self::$defaultPage =
		Utils\DirsFiles::SanFilename(
			$defaultPage
		);
	}


	// get page object
	public static function getPageObj() {
		if(self::$pageObj == NULL)
			self::$pageObj = \psm\Portal\PageLoader::LoadPage(\psm\MODULE, self::getPage());
		return self::$pageObj;
	}


	//TODO:
	public static function GetRenderTime() {
		return '1.111';
	}


}
?>