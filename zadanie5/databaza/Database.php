<?php
require_once "config.php";

class Database{
    public function Napojenie(){
        $conn = null;
        $conn = new mysqli(servername, username, password, dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>