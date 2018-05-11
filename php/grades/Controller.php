<?php
require_once('../categories/Categories.php');
require_once('../courses/Courses.php');
require_once('../database/DataBase.php');
require_once('Grades.php');

switch ($_GET["action"]) {
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

}
 ?>
