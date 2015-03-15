<?php
require(dirname(__FILE__)."/PDO.Log.class.php");
class DB
{
	# @object, The PDO object
	private $pdo;

	# @object, PDO statement object
	private $sQuery;

	# @array,  The database settings
	private $settings;

	# @bool ,  Connected to the database
	private $bConnected = false;

	# @object, Object for logging exceptions	
	private $log;

	# @array, The parameters of the SQL query
	private $parameters;
	# @int, The number of the SQL query
	public $querycount = 0;


		public function __construct()
		{ 			
			$this->log = new Log();	
			$this->Connect();
			$this->parameters = array();
		}
	

		private function Connect()
		{
			$this->settings = parse_ini_file("PDO.settings.ini.php");
			$dsn = 'mysql:dbname='.$this->settings["dbname"].';host='.$this->settings["host"].'';
			try 
			{
				# Read settings from INI file, set UTF8
				$this->pdo = new PDO($dsn, $this->settings["user"], $this->settings["password"], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				
				# We can now log any exceptions on Fatal error. 
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				# Disable emulation of prepared statements, use REAL prepared statements instead.
				$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				# Connection succeeded, set the boolean to true.
				$this->bConnected = true;
			}
			catch (PDOException $e) 
			{
				# Write into log
				echo $this->ExceptionLog($e->getMessage());
				die();
			}
		}


	 	public function CloseConnection()
	 	{
	 		# Set the PDO object to null to close the connection
	 		# http://www.php.net/manual/en/pdo.connections.php
	 		$this->pdo = null;
			# http://php.net/manual/en/pdostatement.closecursor.php
			# $this->pdo->closeCursor();
	 	}
		

		private function Init($query,$parameters = "")
		{
			# Connect to database
			if(!$this->bConnected) { $this->Connect(); }
			try {
				$this->parameters = $parameters;
				# Prepare query
				$this->sQuery = $this->pdo->prepare($this->BuildParams($query,$this->parameters));
				# Bind parameters
					
				if(!empty($this->parameters)) {
					if(array_key_exists(0, $parameters))
					{
						$parametersType = true;
						array_unshift($this->parameters , "");
						unset($this->parameters[0]);
					}else{
						$parametersType = false;
					}
					foreach($this->parameters as $column => $value)
					{
						$this->sQuery->bindParam($parametersType ? intval($column) : ":".$column, $this->parameters[$column]);//It would be query after loop end(before 'sQuery->execute()').It is wrong to use $value.
					}
				}

				# Execute SQL 
				$this->succes 	= $this->sQuery->execute();
				#Counter
				$this->querycount++;
			}
			catch(PDOException $e)
			{
					# Write into log and display Exception
					echo $this->ExceptionLog($e->getMessage(), $this->BuildParams($query) );
					die();
			}

			# Reset the parameters
			$this->parameters = array();
		}

		private function BuildParams($query, $params = null)
		{
			if(!empty($params)) {
				$rawStatement = explode(" ", $query);
				foreach ($rawStatement as $value) {
					if(strtolower($value)=='in')
					{
						return str_replace("(?)", "(".implode(",",array_fill(0,count($params), "?")).")", $query);
					}
				}
			}
			return $query;
		}

		
		public function query($query,$params = null, $fetchmode = PDO::FETCH_ASSOC)
		{
			$query = trim($query);
			$rawStatement = explode(" ", $query);
			$this->Init($query,$params);
			# Which SQL statement is used 
			$statement = strtolower($rawStatement[0]);
			if ($statement === 'select' || $statement === 'show') {
				return $this->sQuery->fetchAll($fetchmode);
			}
			elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
				return $this->sQuery->rowCount();	
			} else {
				return NULL;
			}
		}
		
	
		public function lastInsertId() {
			return $this->pdo->lastInsertId();
		}	
		

		public function column($query,$params = null)
		{
			$this->Init($query,$params);
			return $this->sQuery->fetchAll(PDO::FETCH_COLUMN);
			
		}	


		public function row($query,$params = null,$fetchmode = PDO::FETCH_ASSOC)
		{
			$this->Init($query,$params);
			return $this->sQuery->fetch($fetchmode);			
		}


		public function single($query,$params = null)
		{
			$this->Init($query,$params);
			return $this->sQuery->fetchColumn();
		}


	private function ExceptionLog($message , $sql = "")
	{
		$exception  = 'Unhandled Exception. <br />';
		$exception .= $message;
		$exception .= "<br /> You can find the error back in the log.";

		if(!empty($sql)) {
			# Add the Raw SQL to the Log
			$message .= "\r\nRaw SQL : "  . $sql;
		}
			# Write into log
			$this->log->write($message);

		return $exception;
	}			
}
?>