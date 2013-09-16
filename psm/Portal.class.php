<?php namespace psm;
// PSM - Content Management Framework
// (c) (t) 2004-2013
// Mattsoft.net PoiXson.com

global $ClassCount; $ClassCount++;
// portal core
class Portal {

	// portal instance
	private static $portal = NULL;
	// module instances
	private static $modules = array();
	// selected module;
	private static $moduleName = NULL;

	// page
	private static $pageObj = NULL;
	private static $pageDefault = 'home';
	// action
	private static $action = NULL;


	// simple auto load
	public static function SimpleLoad($args=array()) {
		self::AutoLoad($args);
	}
	public static function AutoLoad($args=array()) {
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

		// portal already loaded
		if(self::$portal != NULL)
			return self::$portal;
		// no page caching
		\psm\Utils\Utils::NoPageCache();
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

		// load portal instance
		self::$portal = new \psm\Portal();

		// get page
		self::getPage();
		// get action
		self::getAction();
	}


	// auto init module
	public static function AutoLoad_Module() {
		// load page
		\psm\Portal::LoadPage();
		\psm\Portal::LoadAction();
		// display page
		\psm\Portal::getEngine()->Display();
	}


	/**
	 * Loads a page class.
	 *     default path - <mod>/pages/<page>.page.php
	 * @param string $page Name of the page to load.
	 * @return page Returns the page class instance, which can be rendered.
	 */
	public static function AutoLoad_Page($modName, $page) {
		$page = \psm\Utils\DirsFiles::SanFilename($page);
		// find page file
		$paths = \psm\Paths::getLocal('pages', $modName);
		$filepath = \psm\Utils\DirsFiles::FindFile($page.'.page.php', $paths);
		// page not found
		//TODO:
		if(empty($filepath)) {
			\psm\Portal::Error('Page not found!! '.$page);
			return;
		}
		// load file
		$result = include($filepath);
		// file failed to load
		//TODO:
		if($result === FALSE) {
			\psm\Portal::Error('Failed to load page!! '.$page);
			return;
		}
		// module name
		$modName = \psm\Portal::getModName();
		// load page class
		$clss = $modName.'\\Pages\\page_'.$page;
		if(\class_exists($clss))
			return new $clss();
		// string result
		return (string) $result;
	}


	// new portal instance
	public function __construct() {
		// portal instance
		if(self::$portal != NULL)
			self::Error('Portal already loaded!');
		// no modules loaded
		if(count(self::$modules) <= 0)
			self::Error('No modules/plugins loaded!');
	}


	// destruct portal
	public function __destruct() {
		if(\psm\html\Engine::hasDisplayed()) return;
		// load page if not already done
		if(self::$pageObj == NULL) {
			$mod = self::getModObj();
			if($mod == NULL)
				self::Error('Module not found! '.self::getModName());
			$mod->Init();
		}
		// render if not already done
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
	// safe unload
	public static function Unload() {
		self::$portal = NULL;
		exit();
	}
	// fast unload
	public static function ExitNow($file='', $line=0) {
		// disable auto page rendering
		\psm\html\Engine::hasDisplayed(TRUE);
		if(!empty($file))
			echo '<p>file: '.$file.
				($line > 0 ? ' line: '.$line : '').
				'</p>';
		self::Unload();
		exit();
	}


	// load modules
	public static function LoadModules() {
		// already loaded
		if(\psm\Portal::getPortal() != NULL)
			return;
		if(count(self::$modules) <= 0)
			self::LoadModulesTxt(
				self::$modules,
				\psm\Paths::getLocal('root').DIR_SEP.'mods.txt'
			);
		if(count(self::$modules) <= 0)
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
			if($mod == NULL) continue;
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
			echo '<p>Module not found! '.$name.'</p>';
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


	// portal framework version
	public static function getVersion() {
		if(!defined('psm\\VERSION'))
			return ' &lt;version_unknown&gt; ';
		return \psm\VERSION;
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


	// get module name
	public static function getModName() {
		// module already defined
		if(defined('psm\\MODULE')) return \psm\MODULE;
		// get module from url
		self::setModName(
			\psm\Utils\Vars::getVar('mod', 'str')
		);
		if(defined('psm\\MODULE')) return \psm\MODULE;
		// default module define
		if(defined('psm\\DEFAULT_MODULE'))
			self::setModName(\psm\DEFAULT_MODULE);
		if(defined('psm\\MODULE')) return \psm\MODULE;
		// first listed mod
		if(count(self::$modules) > 0) {
			$mod = reset(self::$modules);
			\psm\Utils\FuncArgs::classValidate('psm\\Portal\\Module', $mod);
			self::setModName(
				$mod->getModName()
			);
		}
		// unknown module
		return NULL;
	}
	private static function setModName($modName) {
		if(empty($modName)) return;
		if(defined('psm\\MODULE')) return;
		define(
			'psm\\MODULE',
			\psm\Utils\DirsFiles::SanFilename(
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
//			\psm\Utils\DirsFiles::SanFilename(
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
			\psm\Utils\Vars::getVar('page', 'str')
		);
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// default page define
		if(defined('psm\\DEFAULT_PAGE'))
			self::setPage(\psm\DEFAULT_PAGE);
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// default page portal var
		self::setPage(self::$pageDefault);
		if(defined('psm\\PAGE')) return \psm\PAGE;
		// unknown page
		return '404';
	}
	private static function setPage($page) {
		if(empty($page)) return;
		if(defined('psm\\PAGE')) return;
		define(
			'psm\\PAGE',
			\psm\Utils\DirsFiles::SanFilename(
				$page
			)
		);
	}
	// default page
	public static function setDefaultPage($pageDefault) {
		self::$pageDefault =
		\psm\Utils\DirsFiles::SanFilename(
			$pageDefault
		);
	}


	// get page class object
	public static function getPageObj() {
		if(self::$pageObj != NULL)
			return self::$pageObj;
		self::$pageObj =
			\psm\Portal::AutoLoad_Page(
				\psm\MODULE,
				self::getPage()
			);
		return self::$pageObj;
	}
	// load page class
	public static function LoadPage() {
		// already loaded
		if(self::$pageObj != NULL) return;
		// load page object
		self::$pageObj = \psm\Portal::getPageObj();
		// failed to load
		if(self::$pageObj == NULL)
			self::$pageObj = '<p>PAGE IS NULL</p>';
		\psm\Portal::getEngine()->addToPage(self::$pageObj);
	}


	// action
	public static function getAction() {
		// already set
		if(self::$action !== NULL)
			return self::$action;
		// get action
		self::$action =
			\psm\Utils\DirsFiles::SanFilename(
				\psm\Utils\Vars::getVar('action', 'str')
			);
		return self::$action;
	}
	public static function LoadAction() {
		if(!empty(self::$action) && \psm\Utils\FuncArgs::classEquals('psm\\Portal\\Page', self::$pageObj))
			self::$pageObj->Action(self::$action);
	}


	//TODO:
	public static function GetRenderTime() {
		return '1.111';
	}


}
?>