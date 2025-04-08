<?php  
	class Database {
		private $host;
		private $database;
		private $username;
		private $password;
		private $connection;

		public function __construct($host, $database, $username, $password) {
			$this->host = $host;
			$this->database = $database;
			$this->username = $username;
			$this->password = $password;

			$this->connect();
		}

		private function connect() {
			$this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

			if ($this->connection->connect_error) {
				die("Koneksi gagal: " . $this->connection->connect_error);
			}
		}

		public function getConnection() {
			return $this ->connection;
		}
	}

	$database = new Database('localhost:3310', 'web_book', 'root', '');

	$kon = $database->getConnection();
?>