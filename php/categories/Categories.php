<?php
/**
 * Created on ??/05/2018
 *
 * @category   Categories
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Categories {
  private $id;
  private $name;
  private $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }

  // This method only return categories wich has any course
  public function getCategories(){
    $sql = "SELECT DISTINCT cat.id, cat.name FROM mdl_course_categories cat
              INNER JOIN mdl_course c ON cat.id = c.category";
    $result = $this->conn->query($sql);
    $categories;
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $categories[] = $row;
      }
      return array("erro" => false, "description" => "Categories found", "categories" => $categories);
    }else{
      return array("erro" => false, "description" => "No categories with course found", "categories" => $categories);
    }
  }
}

?>
