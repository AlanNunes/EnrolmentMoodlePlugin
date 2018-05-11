<?php
require_once('Courses.php');
require_once('../database/DataBase.php');

switch ($_POST["action"]) {
  // It calls the function to register the Grade(Matriz, grade curricular)
  case 'getCoursesByCategory':
    getCoursesByCategory();
    break;

  default:
    echo json_encode(array("erro" => true, "description" => "No process found for this action"));
    break;
}

function getCoursesByCategory(){
  if(isset($_POST["idCategory"]) && !empty($_POST["idCategory"])){
    $idCategory = $_POST["idCategory"];

    $db = new DataBase("moodle");
    $conn = $db->getConnection();

    $courses = new Courses($conn);
    $coursesList = $courses->getCoursesByCategory($idCategory);

    echo json_encode($coursesList);
  }
}
 ?>
