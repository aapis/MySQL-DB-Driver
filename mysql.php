<?php

	class Database {
		
		public $db_host;
		public $db_username;
		public $db_password;
		public $db_name;
		public $table;
		
		private $query_string = null;
				
		protected static $link;
		protected static $_instance;
		protected $query_obj;
		protected $factory = null;
		protected $handler = null;
		
		
		public function __construct($args = null){
			
			if(false === isset(self::$_instance)){
				if(false === is_null($args)){
					$this->db_password = $args['password'];
					$this->db_host = $args['host'];
					$this->db_username = $args['user'];
					$this->db_name = $args['database'];
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
		
		public static function init($args = null){
			
			if(false === isset(self::$_instance)){
				$class = __CLASS__;
				self::$_instance = new $class($args);
			}
			
			return self::$_instance;
		
		}
			
		protected function query(){
			
			$this->handler = $this->factory->prepare($this->query_string);
					
		}
		
		protected function as_array(){
			
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
		
		public function as_object(){
			
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
		
		public function query_as_array($query_string){
			
			$this->query_string = $query_string;
			$this->query();
			
			return $this->as_array();
		
		}
		
		public function query_as_object($query_string){
			
			$this->query_string = $query_string;
			$this->query($query_string);
			
			return $this->as_object();
		
		}
		
		public function insert($qstring){
		
			if(!mysql_query($qstring)){
				
				if(!$this->supress){
					
					$this->displayError("Warning: Query string was empty.", $qstring);
					
				}
				
			}
		
		}
		
		public function update($qstring){
		
			if(!mysql_query($qstring)){
				
				if(!$this->supress){
				
					$this->displayError("Warning: Query string was empty.", $qstring);
					
				}
				
			}
		
		}
		
		public function delete($qstring){
		
			if(!mysql_query($qstring)){
				
				if(!$this->supress){
				
					$this->displayError("Warning: Query string was empty.", $qstring);
					
				}
				
			}
		
		}
		
		private function displayError($message = false, $query = false){
		
			if($message){
				
				$message ."<br />";
				echo $query;
					
					if(!$this->last_row){
					
						$message .= "<p>Query: ". stripslashes($query) ."<p>";
					
					}
				
				echo $message;
				
			}else {
			
				die("Fatal Error: Could not connect to the database.");
				
			}
		
		}
		
		private function sqlError($query){
		
			$this->error = $query;
		
		}
		
		public function __destruct(){
		
			$this->factory = null;
		
		}
		
	}

?>