<?php
/**
 * Created on 28/05/2018
 *
 * @category   Logs
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Logs {
  private $id;
  private $time;
  private $description;
  private $error;
  private $more;
  private $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }

  public function saveLog($description, $error, $more){
    $timecreated = time();
    $sql = "INSERT INTO logs(description, error, more, timecreated) VALUES ('{$description}', {$error}, '{$more}', {$timecreated})";
    $this->conn->query($sql);
    return $this->conn->error;
  }
}

?>
