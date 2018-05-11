<?php

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
}

?>
