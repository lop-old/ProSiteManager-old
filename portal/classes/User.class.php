<?php namespace psm;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';} else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class User {

	private static $user = NULL;
	private $db = NULL;

	// table name
	const defaultTableName = 'Users';
	private $tableName = '';

	// url vars
	const DEFAULT_USERNAME_VAR = 'login_username';
	const DEFAULT_PASSWORD_VAR = 'login_password';
	private $usernameVar = self::DEFAULT_USERNAME_VAR;
	private $passwordVar = self::DEFAULT_PASSWORD_VAR;

	// demo mode
	private $demoAllowed = TRUE;
	private $isDemo      = FALSE;

	// user details
	private $userId = 0;
	private $username = NULL;
	private $meta = array();


	public static function getUserSession($db, $tableName='') {
		$user = &self::$user;
		// new user session
		if($user == NULL)
			$user = new self($db, $tableName);
		// check login, if available
		$user->_doLogin();
		// check session
		if(!$user->sessionOk())
			$user->_checkSession();
		return $user;
	}
	protected function __construct($db=NULL, $tableName='') {
//		Session::factory();
		// db to use
		if($db == NULL)
			die('<p>db can\'t be null!</p>');
		$this->db = $db;
		// default/custom table name
		$this->tableName = (empty($tableName) ? self::defaultTableName : $tableName);
	}


	// check session
	private function _checkSession() {
	}


	// do login
	private function _doLogin() {
		$username = trim(\psm\Utils\Vars::getVar($this->usernameVar, 'str', 'post'));
		$password = trim(\psm\Utils\Vars::getVar($this->passwordVar, 'str', 'post'));
		// no login to process
		if(empty($username) || empty($password)) return;
		// encrypt password
		$password = \psm\Utils\PassCrypt::hashNow($password);
		$table = 'PSM_Users';
		$query = "SELECT `user_id`, `username`, `email`, `date_join` FROM `".DB::san($table)."` WHERE `username` = :username AND `password` = :password LIMIT 1";
		$params = array(
			':username' => $username,
			':password' => $password,
		);
		$st = $this->db->prepare($query);
		$st->execute($params);
		// not found
		if($st->rowCount() != 1) {
die('Invalid login');
//$_SESSION[$config['session name']] = '';
			return;
		}
		$row = $st->fetch(\PDO::FETCH_ASSOC);
		// user details
		$this->userId   = (int) $row['user_id'];
		$this->username = $row['username'];
		// meta details
		$this->meta['email']     = $row['email'];
		$this->meta['date_join'] = $row['date_join'];

//		if( strtolower($row['playerName']) != strtolower($this->Name) ) return(FALSE);

//		$this->Money       = ((double) $row['money']      );
//		$this->ItemsSold   = ((int)    $row['itemsSold']  );
//		$this->ItemsBought = ((int)    $row['itemsBought']);
//		$this->Earnt       = ((double) $row['earnt']      );
//		$this->Spent       = ((double) $row['spent']      );
//		foreach(explode(',',$row['Permissions']) as $perm)
//			$this->permissions[$perm] = TRUE;
//		$this->invLocked   = ((boolean)$row['Locked']     );
//		$_SESSION[$config['session name']] = $this->Name;

//	// do login
//	public function doLogin($username, $password){global $config;
//	if($password===FALSE) $password = '';
//	return($this->doValidate($username, $password));
//	}
//	// validate session
//	private function doValidate($username, $password=FALSE){global $config;
//	$this->Name = trim($username);
//	if(empty($this->Name)) return(FALSE);
//	if($password!==FALSE && empty($password)) return(FALSE);
//	// validate player

//	$result = RunQuery($query, __file__, __line__);
//	if($result){
//		if(mysql_num_rows($result)==0){
//			$_SESSION[$config['session name']] = '';
//			$_GET['error'] = 'bad login';
//			return(FALSE);
//		}

//	// use iconomy table
//	if(toBoolean($config['iConomy']['use']) || $config['iConomy']['use']==='auto'){
//		global $db;
//		$result = mysql_query("SELECT `balance` FROM `".mysql_san($config['iConomy']['table'])."` WHERE ".
//				"LOWER(`username`)='".mysql_san(strtolower($this->Name))."' LIMIT 1", $db);
//		if($result){
//			$row = mysql_fetch_assoc($result);
//			$this->Money = ((double)$row['balance']);
//			$config['iConomy']['use'] = TRUE;
//		}else{
//			// table not found
//			if(mysql_errno($db) == 1146){
//				$config['iConomy']['use'] = FALSE;
//			}else echo mysql_error($db);
//		}
//		unset($result, $row);
//	}

	}


	// do logout
	public function doLogout() {
//session_init();
//$_SESSION[$config['session name']] = '';
//$_SESSION[CSRF::SESSION_KEY]       = '';
	}


	public function sessionOk() {
		return ($this->getUserId() > 0);
	}


	// user id
	public function getUserId(){
		return($this->userId);
	}
	// player name
	public function getUsername(){
		return($this->username);
	}
//	public function usernameEquals($username){
//		return(strtolower($username) == strtolower($this->getUsername()));
//	}


//	function __construct(){global $config;
//	$loginUrl = './?page=login';
//	if(empty($config['session name'])) $config['session name'] = 'WebAuctionPlus User';
//	// check logged in
//	if(isset($_SESSION[$config['session name']]))
//		$this->doValidate( $_SESSION[$config['session name']] );
//	// not logged in (and is required)
//	if(SettingsClass::getBoolean('Require Login'))
//		if(!$this->isOk() && $config['page'] != 'login'){
//		ForwardTo($loginUrl, 0); exit();}
//	}


//	// permissions
//	public function hasPerms($perms){
//		if(empty($perms) || count($this->permissions)==0) return(FALSE);
//		if(is_array($perms)){
//			if(count($perms) == 0) return(FALSE);
//			$hasPerms = TRUE;
//			foreach($perms as $perm)
//				if(!(boolean)@$this->permissions[$perm])
//				$hasPerms = FALSE;
//			return($hasPerms);
//		}
//		return((boolean)@$this->permissions[$perms]);
//	}
//
//
//	// inventory lock
//	public function isLocked(){
//		if($this->invLocked === null) return(TRUE);
//		return($this->invLocked);
//	}
//
//
//	// money
//	public function getMoney(){
//		return($this->Money);
//	}
//	//public function saveMoney($useMySQLiConomy, $iConTableName){
//	//  if ($useMySQLiConomy){
//	//    $query = mysql_query("UPDATE `".mysql_san($iConTableName)."` SET ".
//	//                         "`balance`=".((double)$this->money)" WHERE ".
//	//                         "`username`='".mysql_san($this->UserName)."' LIMIT 1");
//	//echo mysql_errno();
//	//exit();
//	//  }else{
//	//    $query = mysql_query("UPDATE `".$config['table prefix']."Players` SET ".
//	//                         "`money`=".((double)$this->money)." WHERE ".
//	//                         "`name`='".mysql_san($this->Name)."' LIMIT 1");
//	//  }
//	//  if ($useMySQLiConomy){
//	//    $query = mysql_query("UPDATE $iConTableName SET balance='$this->money' WHERE playername='$this->Name'");
//	//  }else{
//	//    $query = mysql_query("UPDATE WA_Players SET money='$this->money' WHERE name='$this->Name'");
//	//  }
//	//}
//	//public function spend($amount, $useMySQLiConomy, $iConTableName){
//	//  $this->money = $this->money - $amount;
//	//  $this->saveMoney($useMySQLiConomy, $iConTableName);
//	//  $this->spent = $this->spent + $amount;
//	//  $query = mysql_query("UPDATE WA_Players SET spent='$this->spent' WHERE name='$this->name'");
//	//}
//	//public function earn($amount, $useMySQLiConomy, $iConTableName){
//	//  $this->money = $this->money + $amount;
//	//  $this->saveMoney($useMySQLiConomy, $iConTableName);
//	//  $this->earnt = $this->earnt + $amount;
//	//  $query = mysql_query("UPDATE WA_Players SET earnt='$this->earnt' WHERE name='$this->name'");
//	//}
//
//
//	//TODO: this code doesn't check for failures
//	public static function MakePayment($fromPlayer, $toPlayer, $amount, $desc=''){
//		if(empty($fromPlayer) || empty($toPlayer) || $amount<=0){echo 'Invalid payment amount!'; exit();}
//		self::PaymentQuery($toPlayer,     $amount);
//		self::PaymentQuery($fromPlayer, 0-$amount);
//		// TODO: log transaction
//	}
//	public static function PaymentQuery($playerName, $amount){global $config;
//	if($config['iConomy']['use'] === TRUE){
//		$query = "UPDATE `".mysql_san($config['iConomy']['table'])."` SET ".
//				"`balance` = `balance` + ".((float)$amount)." ".
//				"WHERE LOWER(`username`)='".mysql_san(strtolower($playerName))."' LIMIT 1";
//	}else{
//		$query = "UPDATE `".$config['table prefix']."Players` SET ".
//				"`money` = `money` + ".((float)$amount)." ".
//				"WHERE LOWER(`playerName`)='".mysql_san(strtolower($playerName))."' LIMIT 1";
//	}
//	$result = RunQuery($query, __file__, __line__);
//	global $db;
//	if(mysql_affected_rows($db) != 1) echo '<p>Failed to make payment to/from: '.$playerName.'!</p>';
//	}


}
?>