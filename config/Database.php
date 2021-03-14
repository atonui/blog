<?php

class Database {
    private $host = "localhost";
    private $db_name = "rkkxyprs_cmd";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: ".$exception->getMessage();
        }

        return $this->conn;
    }
}

//$db = new Database();