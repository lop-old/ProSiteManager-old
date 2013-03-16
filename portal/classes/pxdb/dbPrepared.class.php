<?php namespace psm\pxdb;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class dbPrepared
implements \psm\pxdb\interfaces\dbPrepared {

	protected $st = NULL;
	protected $results = NULL;
	protected $sql = NULL;
	protected $args = '';
	protected $resultInt = -1;


	public abstract function getConn();


	public function Prepare($sql) {
		if(empty($sql)) \psm\msgPage::Error("sql can't be empty!");
		$this->Clean();
		if($this->getConn() == NULL) return NULL;
		try {
			$this->st = $this->getConn()->prepare($sql);
			$this->sql = $sql;
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}


	// clean up
	public function Clean() {
		$this->st = NULL;
		$this->results = NULL;
		$this->sql = NULL;
		$this->args = '';
		$this->resultInt = -1;
	}


	// execute query
	public function Exec($sql='') {
		if(!empty($sql))
			if($this->Prepare($sql) == NULL)
				return NULL;
		if($this->st == NULL) return NULL;
		if(empty($this->sql)) return NULL;
		//		getLog().debug("query", sql+(args.isEmpty() ? "" : "  ["+args+" ]") );
		try {
			$i = \strpos(' ', $this->sql);
			$firstPart = \strtoupper($i==-1 ? $this->sql : \substr($this->sql, 0, $i) );
			if(!$this->st->execute())
				return NULL;
			if($firstPart == 'INSERT' || $firstPart == 'UPDATE' || $firstPart == 'DELETE')
				$this->resultInt = $this->st->rowCount();
			else
				$this->results = $this->st->fetchAll(\PDO::FETCH_ASSOC);
			return $this;
		} catch (\SQLException $e) {
			//TODO:
			//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}



private $row = array();




	// has next row
	public function hasNext() {
		if($this->st == NULL) return FALSE;
		try {
$this->row = each($this->results);
return !empty($this->row);
//			return $this->st.nextRowset();
		} catch (\SQLException $e) {
			//TODO:
			//			e.printStackTrace();
			return FALSE;
		}
	}


	// row count
	public function getAffectedRows() {
		return $this->getResultInt();
	}
	// insert id
	public function getInsertId() {
		return $this->getResultInt();
	}
	private function getResultInt() {
		if($this->st == NULL) return -1;
		return $this->resultInt;
	}


	// query parameters
	public function setString($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindParam($index, \psm\Utils\Vars::castType($value, 'str'));
			$this->args .= ' String: '.\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}
	public function setInt($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindParam($index, \psm\Utils\Vars::castType($value, 'int'));
			$this->args .= ' Int: '.\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}
	public function setDouble($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindParam($index, \psm\Utils\Vars::castType($value, 'float'));
			$this->args .= ' Double: '.\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}
	public function setLong($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindParam($index, \psm\Utils\Vars::castType($value, 'long'));
			$this->args .= ' Long: '+\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}
	public function setBoolean($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindParam($index, \psm\Utils\Vars::castType($value, 'bool'));
			$this->args .= ' Bool: '.($value==TRUE ? 'TRUE' : 'FALSE');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}


	// get string
	public function getString($label) {
		if(!isset($this->row[$label]))
			return NULL;
		return \psm\Utils\Vars::castType($this->row[$label], 'str');
//		try {
//			if($this->st != NULL)
//TODO:
//\psm\Utils\Vars::castType($value, 'str')
//				return rs.getString(label);
//		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
//		}
		return NULL;
	}
	// get int
	public function getInt($label) {
		if(!isset($this->row[$label]))
			return NULL;
		return \psm\Utils\Vars::castType($this->row[$label], 'int');
//		try {
//			if($this->st != NULL)
//				return rs.getInt($label);
//		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
//		}
//		return NULL;
	}
	// get double
	public function getDouble($label) {
		if(!isset($this->row[$label]))
			return NULL;
		return \psm\Utils\Vars::castType($this->row[$label], 'float');
//		try {
//			if($this->st != NULL)
//				return rs.getDouble($label);
//		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
//		}
//		return NULL;
	}
	// get long
	public function getLong($label) {
		if(!isset($this->row[$label]))
			return NULL;
		return \psm\Utils\Vars::castType($this->row[$label], 'long');
//		try {
//			if($this->st != NULL)
//				return rs.getLong(label);
//		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
//		}
//		return NULL;
	}
	// get boolean
	public function getBoolean($label) {
		if(!isset($this->row[$label]))
			return NULL;
		return \psm\Utils\Vars::castType($this->row[$label], 'bool');
//		try {
//			if($this->st != NULL)
//				return rs.getBoolean(label);
//		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
//		}
//		return NULL;
	}


}
?>