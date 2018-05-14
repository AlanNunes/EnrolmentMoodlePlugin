<?php
/**
 * Created on ??/05/2018
 *
 * @category   DataBase
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class DataBase {
  private $host = "localhost";
  private $user = "root";
  private $password = "";
  private $db = "";
  private $conn;

  public function __construct($db){
    $conn = new mysqli($this->host, $this->user, $this->password, $db);
    mysqli_set_charset($conn,"utf8");
    $this->conn = $conn;
  }

  public function getConnection(){
    return $this->conn;
  }
}
?>
