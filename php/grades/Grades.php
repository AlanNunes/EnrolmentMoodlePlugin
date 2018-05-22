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

  // This function register a new Grade(matriz, grade curricular)
  // Esta função cria uma nova Matriz(grade curricular)
  public function registerGrade($data){
    $cursos = $data["cursos"]; // Gets all the courses, it's already in order
    $nomeMatriz = $data["nomeMatriz"]; // Gets the grade's name
    $modalidade = $data["modalidade"]; // Gets the grade's modalidade
    $sqlNewGrade = "INSERT INTO matrizes (nome) VALUES ('{$nomeMatriz}')"; // Sql to insert a new Grade(matriz)
    if($this->conn->query($sqlNewGrade)){
      $idGrade = $this->conn->insert_id; // Gets the id of the grade(matriz, grade curricular) that was created
      $size = sizeof($cursos);
      for($i = 0; $i < $size; $i++){
        $sortorder = $i; // Sets the number order
        $shortname = $cursos[$i]; // Sets the shortname of the course
        if($i == 0){
          $sqlModulos = "INSERT INTO modulos (matriz, sortorder, shortnamecourse, ativo, modalidade)
                          VALUES ({$idGrade}, {$sortorder}, '{$shortname}')";
        }else{
          $sqlModulos .= ",({$idGrade}, {$sortorder}, '{$shortname}')";
        }
      }
      // Execute the multi query
      if($this->conn->multi_query($sqlModulos)){
        return array("erro" => false, "description" => "Sua matriz foi criada com sucesso !");
      }else{
        return array("erro" => true, "description" => "A matriz foi criada com sucesso. Porém os cursos relacionados a ela não foram inseridos na mesma.");
      }
    }else{
      return array("erro" => true, "description" => "Já existe uma matriz com este nome, por favor, escolha outro.");
    }
  }

  // Retorna a matriz que o aluno deve ser inscrito, de acordo com seu curso e modalidade
  public function getMatrizByCourseAndModalidade($shortnamecourse, $modalidade){
    $sql = "SELECT m.id FROM matrizes m INNER JOIN cursos c ON m.curso = c.id
            INNER JOIN modalidades_de_cursos mc ON mc.id = m.modalidade
              WHERE m.ativo = true AND c.shortname = '{$shortnamecourse}' AND mc.nome = '{$modalidade}'";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      $matriz = $row["id"];
      return array("erro" => false, "description" => "Matriz encontrada", "matriz" => $matriz);
    }else{
      return array("erro" => true, "description" => "Nenhuma matriz foi encontrada", "more" => $this->conn->error);
    }
  }
}

?>
