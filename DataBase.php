<?php
Class DataBase {
  private $host = "localhost";
  private $user = "root";
  private $password = "";
  private $db = "";
  private $conn;

  public function __construct($db){
    $conn = new mysqli($this->host, $this->user, $this->password, $db);
    $this->conn = $conn;
  }

  public function getConnection(){
    return $this->conn;
  }
}
?>
