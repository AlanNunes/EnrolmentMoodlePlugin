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

  // This method return all grades and all courses relationed to it
  public function getGrades(){
    $sql = "SELECT mat.id as idMat, mat.nome as nomeMat, m.matriz as matriz, m.shortnamecourse as idCourse, m.sortorder FROM matrizes mat
    INNER JOIN modulos m ON mat.id = m.matriz";
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
    $sqlNewGrade = "INSERT INTO matrizes (nome) VALUES ('{$nomeMatriz}')"; // Sql to insert a new Grade(matriz)
    if($this->conn->query($sqlNewGrade)){
      $idGrade = $this->conn->insert_id; // Gets the id of the grade(matriz, grade curricular) that was created
      $size = sizeof($cursos);
      for($i = 0; $i < $size; $i++){
        $sortorder = $i; // Sets the number order
        $shortname = $cursos[$i]; // Sets the shortname of the course
        if($i == 0){
          $sqlModulos = "INSERT INTO modulos (matriz, sortorder, shortnamecourse) VALUES ({$idGrade}, {$sortorder}, '{$shortname}')";
        }else{
          $sqlModulos .= ",({$idGrade}, {$sortorder}, '{$shortname}')";
        }
      }
      // Execute the multi query
      if($this->conn->multi_query($sqlModulos)){
        return array("erro" => false, "description" => "Grade and courses were created successfully.");
      }else{
        return array("erro" => true, "description" => "Grade was created but courses weren't inserted into the grade.");
      }
    }else{
      return array("erro" => true, "description" => "Grade was not created.");
    }
  }
}

?>
