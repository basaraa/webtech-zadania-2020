<?php
require_once "config.php";

class Database{
    public $conn;
    public function Napojenie(){
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=".servername.";dbname=".dbname,username, password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>