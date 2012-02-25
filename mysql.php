<?php

	class Database {
		
		public $db_host;
		public $db_username;
		public $db_password;
		public $db_name;
		
		private $query_string = null;
		
		protected static $_instance = null;
		
		protected $factory = null;
		protected $handler = null;
		
		
		/*
		 * Initialize the connection if required, return the instance if not
		 * NOTE: needs to be refactored
		 */
		public function __construct($args = null){
			
			if(false === isset(self::$_instance)){
				if(false === is_null($args)){
					$this->db_password = $args['password'];
					$this->db_host = $args['host'];
					$this->db_username = $args['user'];
					$this->db_name = $args['database'];
				}else {
					die('Connection arguments are missing');
				}
				
				try {
					$this->factory = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_username, $this->db_password);
				}catch(PDOException $e){
					echo $e->getMessage();
				}
			}else {
				return self::$_instance; 
			}
		}
		
		/*
		 * Initialize the connection if required, return the instance if not
		 * NOTE: to be removed in later versions
		 */
		public static function init($args = null){
			
			if(false === isset(self::$_instance)){
				$class = __CLASS__;
				self::$_instance = new $class($args);
			}
			
			return self::$_instance;
		
		}
		
		/*
		 * Prepare the query
		 */	
		protected function _query(){
				
			$this->handler = $this->factory->prepare($this->query_string);
			
		}
		
		/*
		 * Parse a query result into an array we can use
		 */
		protected function _as_array(){
			
			$this->handler->setFetchMode(PDO::FETCH_ASSOC);
			$this->handler->execute();
			
			$return = array();
			$i = 0;
			
			while($row = $this->handler->fetch()){
				$return[$i] = $row;
				
				$i++;
			}
			
			return $return;
					
		}
		
		/*
		 * Parse a query result into an object we can use
		 */
		protected function _as_object(){
			
			$this->handler->setFetchMode(PDO::FETCH_OBJ);
			$this->handler->execute();
			
			$return = new stdClass();
			$i = 0;
			
			while($row = $this->handler->fetch()){
				$return->$i = $row;
				
				$i++;
			}
			
			return $return;
			
		}
		
		/*
		 * Run the query and return an array
		 */
		public function query_as_array($query_string = null){
			
			$this->query_string = $query_string;
			$this->_query();
			
			return $this->_as_array();
		
		}
		
		/*
		 * Run the query and return an object
		 */
		public function query_as_object($query_string = null){
			
			$this->query_string = $query_string;
			$this->_query();
			
			return $this->_as_object();
		
		}
		
		/*
		 * Run boolean queries (insert, delete, etc)
		 */
		public function run($query_string = null){
			
			die("I'm broken.");
			
			/*$this->query_string = $query_string;
			$this->_query();
			
			$this->handler->execute();
			
			$this->handler = null;*/
		
		}
		
		/*
		 * Kill it with fire
		 */
		public function __destruct(){
		
			$this->factory = null;
			$this->handler = null;
			$this->query_string = null;
		
		}
		
	}

?>