<?php
	/*
	 * Requires PHP::PDO to be enabled
	 */
	if (defined('PDO::ATTR_DRIVER_NAME')) {

		class MA_Database {
			
			// Class variables
			public $db_host;
			public $db_username;
			public $db_password;
			public $db_name;
			
			private $query_string = null;
			
			private static $_instance = null;
			
			protected $factory = null;
			protected $handler = null;

			// Constants
			const MA_COULD_NOT_INSTANTIATE = 'Missing required arguments for MA_Database instantiation.';
			//const MA_FATAL_ERROR = 'You done goofed.'; //not used yet
			
			/**
			 * Initialize the connection if required, return the instance if not
			 * TODO: refactor
			 */
			private function __construct($args = array()){
				if(false === isset(self::$_instance)){
					
					$this->db_password = $args['password'];
					$this->db_host = $args['host'];
					$this->db_username = $args['user'];
					$this->db_name = $args['database'];
					
					try {
						$this->factory = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_username, $this->db_password);
					}catch(PDOException $e){
						MA_ErrorHandler::Message('error', $e->getMessage());
					}
					
				}else {
					return self::$_instance; 
				}
			}
			
			/**
			 * Initialize the connection if required, return the instance if not
			 * @return object
			 */
			public static function getInstance($args = array()){
				if(false === isset(self::$_instance)){
					$class = __CLASS__;

					if(false === empty($args)){
						self::$_instance = new $class($args);
					}else {
						MA_ErrorHandler::Message('error', self::MA_COULD_NOT_INSTANTIATE);
					}
				}
				
				return self::$_instance;
			}
			
			/**
			 * Prepare the query, set $this->handler for use in other methods
			 * @return void
			 */	
			private function _query(){
				$this->handler = $this->factory->prepare($this->query_string, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			}
			
			/**
			 * Parse a query result into an array we can use
			 * @return array
			 */
			private function _as_array(){
				$this->handler->setFetchMode(PDO::FETCH_ASSOC);
				$this->handler->execute();
				
				$return = array();
				$i = 0;
				
				while($row = $this->handler->fetch()){
					$return[$i] = $row;
					
					$i++;
				}
				
				//return (empty($return) ? false : $return);
				return $return;
			}
			
			/**
			 * Parse a query result into an object we can use
			 * @return object
			 */
			private function _as_object(){
				$this->handler->setFetchMode(PDO::FETCH_OBJ);
				$this->handler->execute();
				
				$return = new stdClass();
				$i = 0;

				while($row = $this->handler->fetch()){
					$return->$i = $row;
					
					$i++;
				}
				
				//return (empty($return) ? false : $return);
				return $return;
			}
			
			/**
			 * Run the query and return an array
			 * @return array
			 * 
			 * @deprecated, use load methods instead
			 */
			public function query_as_array($query_string = null){
				MA_ErrorHandler::Message('warning', 'MA_Database::query_as_array has been deprecated');

				$this->loadArrayList($query_string);
			}

			/**
			 * Run the query and return an object
			 * @return object
			 *
			 * @deprecated, use load methods instead
			 */
			public function query_as_object($query_string = null){
				MA_ErrorHandler::Message('warning', 'MA_Database::query_as_object has been deprecated');

				$this->loadObjectList($query_string);
			}


			/**
			 * [loadArrayList Return an array of arrays]
			 * @param  [type] $query_string [description]
			 * @return [type]               [description]
			 */
			public function loadArrayList($query_string = null){
				$this->query_string = $query_string;
				$this->_query();
				
				return $this->_as_array();
			}

			/**
			 * [loadObjectList Return a list of objects]
			 * @param  [type] $query_string [description]
			 * @return [type]               [description]
			 */
			public function loadObjectList($query_string = null){
				$this->query_string = $query_string;
				$this->_query();
				
				return $this->_as_object();
			}
			
			/**
			 * Run boolean queries (insert, delete, etc)
			 * @return bool
			 */
			public function run($query_string = null){
				$this->query_string = $query_string;

				$result = $this->factory->exec($query_string);

				if($result === 0){
					MA_ErrorHandler::Message('warning', 'Query failed to run, 0 results.', $query_string);
				}
			}
			
		} //end class

		/**
		 * Class: MA_ErrorHandler
		 * 
		 * Error handling for MA_Database
		 */
		abstract class MA_ErrorHandler {
			/**
			 * Display messages to the user
			 * @return string
			 */
			public static function Message($type = 'error', $message = null, $query_string = null){
				switch($type){
					case 'error': 
						self::Error($message, $query_string);
					break;

					case 'warning':
						self::Warning($message, $query_string);
					break;
				}
			}

			/**
			 * Display errors as obnoxiously as possible
			 * @return string
			 */
			private function Error(){
				$errors = func_get_args();

				if(false === empty($errors)){
					echo '<div class="user-message error">';
						foreach($errors as $error){
							echo '<h3>'. $error .'</h3>';
						}
					echo '</div>';
				}

				die();
			}

			/**
			 * Display warnings to the user
			 * @return string
			 */
			private function Warning(){
				$warnings = func_get_args();

				if(false === empty($warnings)){
					echo '<div class="user-message warning">';
						foreach($warnings as $warning){
							echo '<h3>'. $warning .'</h3>';
						}
					echo '</div>';
				}
			}
		}

	}else {
		die('This class requires the PDO extension.  See <a href="http://php.net/manual/en/pdo.installation.php">this guide</a> to enable it.');
	}

?>