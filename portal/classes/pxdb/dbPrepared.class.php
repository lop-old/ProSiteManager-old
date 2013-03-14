<?php namespace psm\dbPool;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
abstract class dbPrepared {

	protected $conn = NULL;
	protected $st = NULL;
	protected $rs = NULL;
	protected $sql = NULL;
	protected $args = '';
	protected $resultInt = -1;


	public function Prepare($sql) {
		if(empty($sql)) \psm\msgPage::Error("sql can't be empty!");
		$this->Cleanup();
		if($this->conn == NULL) return NULL;
		try {
			$this->st = $this->conn->prepare($sql);
			$this->sql = $sql;
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}


//	public function Fetch() {
//		return FALSE;
//		//\PDO::FETCH_ASSOC
//	}




	// query parameters
	public function setString($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindValue($index, \psm\Utils\Vars::castType($value, 'str'));
			$this->args .= ' String: '.\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}
	public function setInt($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindValue($index, \psm\Utils\Vars::castType($value, 'int'));
			$this->args .= ' Int: '.\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}
	public function setDouble($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindValue($index, \psm\Utils\Vars::castType($value, 'float'));
			$this->args .= ' Double: '.\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}
	public function setLong($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindValue($index, \psm\Utils\Vars::castType($value, 'long'));
			$this->args .= ' Long: '+\psm\Utils\Vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}
	public function setBoolean($index, $value) {
		if($this->st == NULL) return NULL;
		try {
			$this->st->bindValue($index, \psm\Utils\Vars::castType($value, 'bool'));
			$this->args .= ' Bool: '.($value==TRUE ? 'TRUE' : 'FALSE');
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}


	// execute query
	public function Exec() {
		if($this->st == NULL) return NULL;
		if(empty($sql)) return NULL;
//		getLog().debug("query", sql+(args.isEmpty() ? "" : "  ["+args+" ]") );
		try {
			$i = \strpos(' ', $this->sql);
			$firstPart = \strtoupper($i==-1 ? $this->sql : \substr($this->sql, 0, $i) );
			if($firstPart == 'INSERT' || $firstPart == 'UPDATE' || $firstPart == 'DELETE')
				$this->resultInt = $this->st->executeUpdate();
			else
				$this->rs = $this->st->executeQuery();
			return $this;
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
			$this->Cleanup();
			return NULL;
		}
	}


	// has next row
	public function hasNext() {
		if($this->rs == NULL) return FALSE;
		try {
			return $this->rs.next();
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
		if($this->rs == NULL) return -1;
		return $this->resultInt;
	}


	// get string
	public function getString($label) {
		try {
			if($this->rs != NULL)
//TODO:
//\psm\Utils\Vars::castType($value, 'str')
				return rs.getString(label);
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
		}
		return NULL;
	}
	// get int
	public function getInt($label) {
		try {
			if($this->rs != NULL)
				return rs.getInt($label);
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
		}
		return NULL;
	}
	// get double
	public function getDouble($label) {
		try {
			if($this->rs != NULL)
				return rs.getDouble($label);
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
		}
		return NULL;
	}
	// get long
	public function getLong($label) {
		try {
			if($this->rs != NULL)
				return rs.getLong(label);
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
		}
		return NULL;
	}
	// get boolean
	public function getBoolean($label) {
		try {
			if($this->rs != NULL)
				return rs.getBoolean(label);
		} catch (\SQLException $e) {
//TODO:
//			e.printStackTrace();
		}
		return NULL;
	}


	// clean up
	public function Cleanup() {
		$this->st = NULL;
		$this->rs = NULL;
		$this->sql = NULL;
		$this->args = '';
		$this->resultInt = -1;
	}


}
?>