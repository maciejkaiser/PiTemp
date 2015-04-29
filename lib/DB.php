<?php
class DB{
	
	public $error;
	
	/**
	 * Variable to gets connection
	 */
	private $conn;
	
    /**
     * Construct - make connection
     * 
     * @param string $core
     * @param string $dbname
     * @param string $host
     * @param string $user
     * @param string $pass
     */
	function __construct($core, $dbname, $host, $user, $pass){
		$this->getConnection($core, $dbname, $host, $user, $pass);
	}
	
	/**
	 * Make connection to database (MySQL, PostgreSQL etc.) - using PDO
	 *
	 *@param string $core - information about core using by database e.g mysql
	 *@param string $dbname - name of database that we are using in project
	 *@param string $host - server adress e.g localhost
	 *@param string $user - our username
	 *@param string $pass - our password (can be null)
	 *
	 *@return connection $this->conn
	 *
	 */
	public function getConnection($core, $dbname, $host, $user, $pass){
		$this->core = $core;
		$this->dbName = $dbname;
		$this->host = $host;
		$this->user = $user;
		$this->password = $pass;
		
		if (!empty($this->host) && !empty($this->user)) {
			try {
				if($this->dbName){
					$this->dsn = $this->core.":dbname=".$this->dbName.";host=".$this->host;
				}else{
					$this->dsn = $this->core.":host=".$this->host;
				}
				$this->conn = new PDO($this->dsn, $this->user, $this->password, null);
				$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
				return $this->conn;
			} catch (PDOException $e) {
				$this->error = "Error : ".$e->getMessage()."\n";
			}
		}else{
			$this->error = "Error! : host, user or password is empty!";
		}
	}
	
	public function query($data){
		if($this->conn){
			return $this->conn->query($data);
		}else{
			//$this->error = " Can't make this query. ";
		}
	}
	
	/**
	 * Insert Rows to table in database
	 *
	 *@param string $table - name of table for insert data
	 *@param array $data - data to insert into table
	 *
	 *@return bool
	 *
	 */
	public function insertRows($table, $data){
		$result = $this->conn->query("INSERT INTO ".$table." VALUES(".$data.")");
		if($result !== null ? true : false);
	}
	
	/**
	 * Get Rows from table in database
	 *
	 *@param string $table - name of table to gets data
	 *@param string $fields - to specify what you want to return e.q object_id, object_name
	 *@param array $data - array of "WHERE" statement
	 *
	 *@return bool
	 *
	 */
	public function getRows($table, $fields = "*", $data = array()){
		$result = $this->conn->query("SELECT ".$fields." FROM `".$table."`");
		return $result->fetchAll();
	}
	
	/**
	 * Closes connection to database
	 */
	private function closeConnection(){
		$this->conn = null;
	}
	
	function __destruct(){
		$this->closeConnection();
	}
	
}