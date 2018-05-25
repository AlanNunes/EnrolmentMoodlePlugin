<?php
/**
 * Created on ??/05/2018
 *
 * @category   Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Grades {
  private $id; // Primary Key for Grades
  private $fullname; // Name of the course
  private $shortname; // Identifies the course
  private $conn; // Connection to database

  public function __construct($conn){
    $this->conn = $conn;
  }

  // This method return all grades(matrizes)
  public function getGrades(){
    $sql = "SELECT * FROM matrizes";
    $result = $this->conn->query($sql);
    $grades;
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $grades[] = $row;
      }
      return array("erro" => false, "description" => "All grades were listed successfully", "grades" => $grades);
    }else{
      return array("erro" => false, "description" => "Grades were not found", "grades" => $grades);
    }
  }

  // Recebe uma array com os períodos e seus respectivos cursos
  // Registra todos os módulos com a id da matriz passada com parâmetro
  public function registerModulos($matriz, $cursos){
    $periodos = sizeof($cursos);
    $sql = "";
    // Caminha por todos os períodos
    for( $i = 0; $i < $periodos; $i++ ){
      $cursosSize = sizeof($cursos[$i]);
      // Caminha por todos os cursos, dentro do período
      for( $j = 0; $j < $cursosSize; $j++ ){
        $shortnamecourse = $cursos[$i][$j];
        $periodo = $i+1;
        $sql .= "INSERT INTO modulos (matriz, sortorder, shortnamecourse, periodo) VALUES ({$matriz}, {$j}, '{$shortnamecourse}', {$periodo});";
      } // Fim Cursos
    }// Fim Período
    if($this->conn->multi_query($sql) === TRUE){
      return array("erro" => false, "description" => "Todos os módulos foram registrados com sucesso.");
    }else{
      return array("erro" => true, "description" => "Os módulos não foram registrados.", "more" => $this->conn->error);
    }
  }

  // This function register a new Grade(matriz, grade curricular)
  // Esta função cria uma nova Matriz(grade curricular)
  public function registerGrade($nome, $idCurso, $modalidade){
    $sql = "INSERT INTO matrizes (nome, curso, ativo, modalidade) VALUES ('{$nome}', {$idCurso}, true, {$modalidade})";
    if($this->conn->query($sql)){
      return array("erro" => false, "description" => "A matriz foi criada com sucesso", "id" => $this->conn->insert_id);
    }
    return array("erro" => true, "description" => "A matriz não foi criada.", "more" => $this->conn->error);
  }

  // Retorna a matriz que o aluno deve ser inscrito, de acordo com seu curso e modalidade
  public function getMatrizByCourseAndModalidade($shortnamecourse, $modalidade){
    $sql = "SELECT m.id FROM matrizes m INNER JOIN cursos c ON m.curso = c.id
            INNER JOIN modalidades_de_cursos mc ON mc.id = m.modalidade
              WHERE m.ativo = true AND c.shortname = '{$shortnamecourse}' AND mc.nome = '{$modalidade}'";
    $result = $this->conn->query($sql);
    if($result){
      if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $matriz = $row["id"];
        return array("erro" => false, "description" => "Matriz encontrada", "matriz" => $matriz);
      }else{
        return array("erro" => true, "description" => "Nenhuma matriz foi encontrada", "more" => $this->conn->error);
      }
    }else{
      return array("erro" => true, "description" => "Erro desconhecido", "more" => $this->conn->error);
    }
  }
}

?>
