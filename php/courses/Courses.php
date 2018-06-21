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
  private $table_prefix;
  private $conn;

  public function __construct($conn, $table_prefix){
    $this->conn = $conn;
    $this->table_prefix = $table_prefix;
  }

  // This method return all courses relationed to the category specified
  public function getCoursesByCategory($category){
      $sql = "SELECT fullname, shortname FROM {$this->table_prefix}course WHERE category = {$category}";
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

  // Busca o id do curso com o shortname passado, e retorna seus respectivos valores
  public function getCourseIdByShortName($shortname){
    $sql = "SELECT id FROM cursos WHERE shortname = '{$shortname}'";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      $id = $row['id'];
      return array('erro' => false, 'description' => 'O curso id do curso {$shortname} foi encontrado, {$id}.', 'id' => $id);
    }else{
      return array('erro' => true, 'description' => 'Nenhum curso foi encontrado', 'more' => $this->conn->error);
    }
  }
}

?>
