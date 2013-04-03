<?php namespace psm;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
// portal core
class Portal {

	// module instances
	private static $modules = array();

	// page
	private static $pageObj = NULL;
	private static $defaultPage = 'home';
	// action
	private $action = NULL;


	// new portal instance
	public function __construct() {
		// portal instance
		if(PortalLoader::getPortal() != NULL)
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


	// load modules
	public static function _LoadModules() {
		// already loaded
		if(\psm\PortalLoader::getPortal() != NULL)
			return;
		if(count(self::$modules) == 0)
			self::$modules = \psm\Portal\ModuleLoader::LoadModulesTxt(
				\psm\Paths::getLocal('root').DIR_SEP.'mods.txt'
			);
		if(count(self::$modules) == 0)
			self::Error('No modules/plugins loaded!');
	}


	// debug mode
	public static function isDebug() {
		return defined('psm\\DEBUG') && \psm\DEBUG === TRUE;
	}


	// error page
	public static function Error($msg) {
		\psm\msgPage::Error($msg);
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
		$this->action = \psm\Utils\Vars::getVar('action', 'str');
		$this->action = \psm\Utils\Utils_Files::SanFilename($this->action);
		return $this->action;
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
			\psm\Utils\Utils::Validate('psm\\Portal\\Module', $mod);
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
			\psm\Utils\Utils_Files::SanFilename(
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
//			\psm\Utils\Utils_Files::SanFilename(
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
			\psm\Utils\Utils_Files::SanFilename(
				$page
			)
		);
	}
	// default page
	public static function setDefaultPage($defaultPage) {
		self::$defaultPage =
		\psm\Utils\Utils_Files::SanFilename(
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