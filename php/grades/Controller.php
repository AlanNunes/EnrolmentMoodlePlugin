<?php
require_once('../categories/Categories.php');
require_once('../courses/Courses.php');
require_once('../database/DataBase.php');
require_once('Grades.php');

switch ($_POST["action"]) {
  // It calls the function to register the Grade(Matriz, grade curricular)
  case 'registerGrade':
    registerGrade();
    break;

  case 'listGrades':
    listGrades();
    break;

  default:
    echo json_encode(array("erro" => true, "description" => "No process found for this action"));
    break;
}

function registerGrade(){
  if(isset($_POST["data"]) && !empty($_POST["data"])){
    $data = $_POST["data"];

    $db = new DataBase("external_enrolment");
    $conn = $db->getConnection();

    $grade = new Grades($conn);
    $response = $grade->registerGrade($data);

    echo json_encode($response);
  }else{
    echo json_encode(array("erro" => true, "description" => "Missing data"));
  }
}
 ?>
