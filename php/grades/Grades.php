<?php
// Matrizes
Class Grades {
  private $id;
  private $fullname;
  private $shortname;
  private $conn;

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

  // This method return all the modules(módulo, disciplina, matéria) from a grade(matriz, grade curricular)
  // public function getModules($idGrade){
  //   $sql = "SELECT * FROM modulos WHERE matriz = {$idGrade}";
  //   $result = $this->conn->query($sql);
  //   $modules[];
  //   if($result->num_rows > 0){
  //     while($row = $result->fetch_assoc()){
  //       $modules[] = $row;
  //     }
  //     return array("erro" => false, "description" => "All modules was listed successfully", "modules" => $modules);
  //   }else{
  //     return array("erro" => false, "description" => "Modules were not found", "modules" => $modules);
  //   }
  // }

  // This function register a new Grade(matriz, grade curricular)
  public function registerGrade($data){
    $nomeMatriz = $data["nomeMatriz"];
    $cursos = $data["curso"];

    $sqlNewGrade = "INSERT INTO matrizes (nome) VALUES ('{$nomeMatriz}')";
    if($this->conn->query($sqlNewGrade)){
      $idGrade = $this->conn->insert_id;
      $size = sizeof($cursos);
      $sqlModulos = "";
      for($i = 0; $i < $size; $i++){
        $sortorder = $i; // Sets the number order
        $shortname = $cursos[$i]; // Sets the shortname of the course
        $sqlModulos .= "INSERT INTO modulos (matriz, sortorder, shortnamecourse) VALUES ({$idGrade}, {$sortorder}, '{$shortname}')";
      }
      if($this->conn->multi_query($sqlModulos)){
        return array("erro" => false, "description" => "Grade and courses were created successfully.");
      }else{
        return array("erro" => true, "description" => "Grade was created but courses weren't.");
      }
    }else{
      return array("erro" => true, "description" => "Grade was not created.");
    }
  }
}

?>
