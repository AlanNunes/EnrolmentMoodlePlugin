<?php
/**
 * Created on 21/05/2018
 *
 * @category   Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Modalidades_de_Cursos {
  private $id; // Primary Key for Grades
  private $nome; // Nome da modalidade ( Unique Key )
  private $conn; // Connection to database

  public function __construct($conn){
    $this->conn = $conn;
  }

  // Esta função recolhe todas as modalidades de cursos existentes no banco de dados
  public function getModalidades(){
    $sql = "SELECT * FROM modalidades_de_cursos";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $modalidades[] = $row;
      }
      return array('erro' => false, 'description' => 'As modalidades de cursos foram encontradas com sucesso', 'modalidades' => $modalidades, 'more' => $this->conn->error);
    }else{
      return array('erro' => false, 'description' => 'Nenhuma modilidade de curso foi encontrada.', 'modalidades' => 0, 'more' => $this->conn->error);
    }
  }
}

?>
