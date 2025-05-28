<?php
session_start();
	class Conn extends PDO
	{
		private $host = 'localhost';
		private $dbname = 'web_service';
		private $user = 'root';
		private $pass = 'Anubis@68';
		
		public function __construct()
		{
			try{
				parent::__construct('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->user, $this->pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
				
				} catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
				exit;
			}
		}
	}
?>