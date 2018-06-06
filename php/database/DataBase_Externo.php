<?php
/**
 * Created on 30/05/2018
 * It connects to the external database
 *
 * @category   DataBase
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class DataBase_Externo {
  private $conn;
  private $config = array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'external_enrolment',
    'port' => 3307,
    'socket' => '',
    'dbcollation' => 'utf8mb4_unicode_ci',
  );

  // Connect to DataBase as specified in the config
  public function __construct(){
    $this->connect();
  }

  // Create a connection to the database specified in the config and set it's connection to $conn
  private function connect(){
    $this->conn = new mysqli($this->config['host'], $this->config['user'], $this->config['password'], $this->config['database'], $this->config['port'], $this->config['socket']);
    $this->conn->set_charset("utf-8");
  }

  // Return the variable conn which has the connection to DataBase
  public function getConnection(){
    return $this->conn;
  }
}
?>
