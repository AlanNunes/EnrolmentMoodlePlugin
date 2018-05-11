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
    $sql = "SELECT mat.id as idMat, mat.nome as nomeMat, m.matriz as matriz, m.course as idCourse, m.sortorder FROM matrizes mat
    INNER JOIN modulos m ON mat.id = m.matriz";
    $result = $this->conn->query($sql);
    $grades[];
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
}

?>
