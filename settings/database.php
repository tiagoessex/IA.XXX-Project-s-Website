<?php
/*
    creates a databaase connection and returns a connector

    note that all settings, such as, usernames and passwords, 
    are defined in Config.php and not here.

*/
include_once('config.php');

class Database{
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" .DB_NAME, DB_USER, DB_PASSWORD);
            $this->conn->exec("set names ". DB_CHARSET);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}