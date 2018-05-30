<?php
/**
 * Created on 30/05/2018
 * It connects to the Moodle database
 *
 * @category   DataBase
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class DataBase_Moodle {
  private $conn;
  private $config = array(
    'host' => 'mysql.hostinger.com.br',
    'user' => 'u501599648_moext',
    'password' => '123Mud@r',
    'database' => 'u501599648_moext',
    'port' => 3306,
    'socket' => '',
    'dbcollation' => 'utf8mb4_unicode_ci',
  );

  // Connect to DataBase as specified in the config
  public function __construct(){
    $this->connect();
  }

  // Create a connection to the database specified in the config and set it's connection to $conn
  private function connect(){
    $conn = new mysqli($this->config['host'], $this->config['user'], $this->config['password'], $this->config['database'], $this->config['port'], $this->config['socket']);
    mysqli_set_charset($conn, $this->config['dbcollation']);
    $this->conn = $conn;
  }

  // Return the variable conn which has the connection to DataBase
  public function getConnection(){
    return $this->conn;
  }
}
?>