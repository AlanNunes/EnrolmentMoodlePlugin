<?php
/**
 * Created on 18/05/2018
 *
 * @category   Modulos
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Modulos {
  private $id; // Primary Key for Modulos
  private $matriz; // Foreign key from Grades(matrizes)
  private $sortorder; // Number order
  private $shortnamecourse; // Unique shortname's course
  private $conn; // Connection to database

  public function __construct($conn){
    $this->conn = $conn;
  }

  // Get shortname's module by grade(matriz) as foreign key
  public function getShortNameCourseByGrade($matriz){
    $sql = "SELECT shortnamecourse FROM modulos WHERE matriz = {$matriz} ORDER BY sortorder LIMIT 1";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      $shortnamecourse = $row['shortnamecourse'];
      return array('erro' => false, 'description' => 'Modules were found successfully.', 'shortnamecourse' => $shortnamecourse);
    }else{
      return array('erro' => true, 'description' => 'Modules were not found.');
    }
  }
}

?>
