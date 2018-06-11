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
  * @param string $description Descrição do processo
  * @param boolean $error Sinaliza se ocorreu falha no processo
  * @param string $more Apresenta o erro retornado pelo MySQL
  * @param string $username Username do estudante
  * @param string $shortnamecourse Shortname do curso
  * @param string $periodo Período do curso
  * @param string $matriz Matriz do curso
  * @return array Caso ocorra erro é retornado a descrição dada pelo MySQL
  */
  public function saveLog($description, $error, $more, $username, $shortnamecourse, $periodo, $matriz){
    $timecreated = time();
    $error = ($error)?1:0;
    $shortnamecourse = ($shortnamecourse==null)?"null":"'{$shortnamecourse}'";
    $periodo = ($periodo==null)?"null":"'{$periodo}'";
    $matriz = ($matriz==null)?"null":"'{$matriz}'";
    // $categoria = $this->getCategoriaId();
    $sql = "INSERT INTO logs(description, error, more, timecreated, username,
            shortnamecourse, periodo, matriz) VALUES ('{$description}', {$error},
              '{$more}', {$timecreated}, '{$username}', {$shortnamecourse},
                {$periodo}, {$matriz})";
    if(!$this->conn->query($sql)){
      return $this->conn->error;
    }
  }
}

?>
