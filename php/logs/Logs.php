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
  /**
  * @var int
  */
  private $id;
  /**
  * timestamp
  * @var int
  */
  private $time;
  /**
  * @var string
  */
  private $description;
  /**
  * @var boolean
  */
  private $error;
  /**
  * @var string
  */
  private $more;
  private $conn;

  public function __construct($conn){
    $this->conn = $conn;
    // Set default Time Zone
    // date_default_timezone_set("America/Sao_Paulo");
  }
  /**
  * Salva a log no banco de dados
  */
  public function saveLog($description, $error, $more, $categoriaNome, $username, $shortnamecourse, $periodo, $matriz){
    $timecreated = time();
    $error = ($error)?1:0;
    $shortnamecourse = ($shortnamecourse==null)?"null":"'{$shortnamecourse}'";
    $periodo = ($periodo==null)?"null":"'{$periodo}'";
    $matriz = ($matriz==null)?"null":"'{$matriz}'";
    // $categoria = $this->getCategoriaId($categoriaNome);
    $sql = "INSERT INTO logs(description, error, more, timecreated, username, shortnamecourse, periodo, matriz)
            VALUES ('{$description}', {$error}, '{$more}', {$timecreated}, '{$username}', {$shortnamecourse}, {$periodo}, {$matriz})";
    if(!$this->conn->query($sql)){
      return $this->conn->error;
    }
  }

  /**
  * Busca o id da categoria de logs de acordo com seu nome(unique key)
  * Retorna apenas valores inteiros
  */
  // public function getCategoriaId($categoriaNome){
  //   $sql = "SELECT id FROM categorias_de_logs WHERE nome = '{$categoriaNome}'";
  //   echo "<br/>".$sql."<br/>";
  //   $result = $this->conn->query($sql);
  //   if($result->num_rows > 0){
  //     $row = $result->fetch_assoc();
  //     return $row['id'];
  //   }
  // }
}

?>
