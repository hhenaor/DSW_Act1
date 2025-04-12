<?php

	include_once "Iconnection.php";

	class conn_imp implements Iconnection {

		private $host;
		private $port;
		private $database;
		private $user;
		private $password;
		private static $instance;
		private $conn;

		// constructor
		private function __construct(
				$host = "localhost",
				$port = "3306",
				$database = "mpnotes_db",
				$user = "root",
				$password = ""
			) {
			$this->host = $host;
			$this->port = $port;
			$this->database = $database;
			$this->user = $user;
			$this->password = $password;
		}

		// connect to database
		public function connect() {
			$this->conn = new mysqli(
				$this->host,
				$this->user,
				$this->password,
				$this->database,
				$this->port
			);
		}

		// get instance of the class
		public static function getInstance() {
			if (!conn_imp::$instance) {
				conn_imp::$instance = new conn_imp();
			}
			return conn_imp::$instance;
		}

		// send query to database and return result
		public function query($sql_query) {
			return $this->conn->query($sql_query);
		}

		// send update to database
		public function update($sql_query, $type = "") {
			$this->conn->query($sql_query);
		}

		// close connection to database
		public function disconnect() {
			if ($this->conn) {
				$this->conn->close();
			}
			$this->conn = null;
		}

	}

?>