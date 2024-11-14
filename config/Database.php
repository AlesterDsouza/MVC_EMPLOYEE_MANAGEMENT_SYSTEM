<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "12345678";
    private $dbname = "MVC_EMPLOYEE";
    public $conn;

    public function __construct($dbname) {
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
?>