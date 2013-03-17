<?php namespace psm\pxdb;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class dbPrepared
implements \psm\pxdb\interfaces\dbPrepared {

	protected $st      = NULL;
	protected $row     = NULL;
	protected $sql     = NULL;
	protected $args    = '';
	protected $resultInt = -1;
	protected $insertId  = -1;


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
		$this->st      = NULL;
		$this->row     = NULL;
		$this->sql     = NULL;
		$this->args    = '';
		$this->resultInt = -1;
		$this->insertId  = -1;
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
			// get first part
			$i = \strpos(' ', $this->sql);
			$firstPart = \strtoupper($i==-1 ? $this->sql : \substr($this->sql, 0, $i) );
			// run query
			if(!$this->st->execute())
				return NULL;
			// insert
			if($firstPart == 'INSERT')
				$this->resultInt = $this->db->lastInsertId();
			else
				$this->resultInt = $this->st->rowCount();
			return $this;
		} catch (\SQLException $e) {
			//TODO:
			//			e.printStackTrace();
			$this->Clean();
			return NULL;
		}
	}


	// has next row
	public function hasNext() {
		if($this->st == NULL) return FALSE;
		try {
			$this->row = $this->st->fetch(\PDO::FETCH_ASSOC);
			if($this->row === FALSE) {
				$this->Clean();
				return FALSE;
			}
			return $this->row;
		} catch (\SQLException $e) {
			//TODO:
			//			e.printStackTrace();
			return FALSE;
		}
	}


	// row count
	public function getRowCount() {
		if($this->st == NULL) return -1;
		return $this->resultInt;
	}
	// insert id
	public function getInsertId() {
		if($this->st == NULL) return -1;
		return $this->insertId;
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
		if($this->row == NULL || !isset($this->row[$label]))
			return FALSE;
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
//		return NULL;
	}
	// get int
	public function getInt($label) {
		if($this->row == NULL || !isset($this->row[$label]))
			return FALSE;
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
		if($this->row == NULL || !isset($this->row[$label]))
			return FALSE;
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
		if($this->row == NULL || !isset($this->row[$label]))
			return FALSE;
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
		if($this->row == NULL || !isset($this->row[$label]))
			return FALSE;
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