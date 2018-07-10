<?php
/**
 * Created on ??/05/2018
 *
 * @category   Controller of Courses
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
require_once('Courses.php');
require_once('../database/DataBase.php');
require_once('../config/Config.php');

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

    $db = new DataBase("moodle_prod_atual");
    $conn = $db->getConnection();

    $courses = new Courses($conn, Config::$table_prefix);
    $coursesList = $courses->getCoursesByCategory($idCategory);

    echo json_encode($coursesList);
  }
}
 ?>
