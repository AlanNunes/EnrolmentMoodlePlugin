<?php
/**
 * Created on ??/05/2018
 *
 * @category   Courses
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Courses {
  private $id;
  private $fullname;
  private $shortname;
  private $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }

  // This method return all courses relationed to the category specified
  public function getCoursesByCategory($category){
      $sql = "SELECT fullname, shortname FROM mdl_course WHERE category = {$category}";
      $result = $this->conn->query($sql);
      $courses = null;
      if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
          $courses[] = $row;
        }
        return array("erro" => false, "description" => "Courses returned with success", "courses" => $courses);
      }
      return array("erro" => true, "description" => "The method should have returned some course", "courses" => $courses);
  }

  // Creates a new course and return a load of information
  public function registerCourse($name, $shortname){
    $sql = "INSERT INTO cursos (nome, shortname) VALUES ('{$name}', '{$shortname}')";
    if($this->conn->query($sql)){
      return array('erro' => false, 'description' => 'The course was successfully registered', 'id' => $this->conn->insert_id);
    }else{
      return array('erro' => true, 'description' => 'The query wasnt executed successfully', 'more' => $this->conn->error);
    }
  }
}

?>
