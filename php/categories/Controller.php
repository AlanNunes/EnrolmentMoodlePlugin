<?php
/**
 * Created on ??/05/2018
 *
 * @category   Controller of Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
require_once('Categories.php');
require_once('../database/DataBase.php');
require_once('../config/Config.php');

switch ($_POST["action"]) {
  // It calls the function to register the Grade(Matriz, grade curricular)
  case 'getCursosCategories':
    getCursosCategories();
    break;

  case 'getCursosAndPeriodosByCategories':
    getCursosAndPeriodosByCategories();
    break;

  default:
    echo json_encode(array("erro" => true, "description" => "No process found for this action"));
    break;
}

// Retorna as categorias dos cursos
function getCursosCategories(){
  $db = new DataBase("moodle_prod_atual");
  $conn = $db->getConnection();

  $categories = new Categories($conn, Config::$table_prefix);
  $response = $categories->getCursosCategories();
  echo json_encode($response);
}

// Retorna todos os períodos do curso, junto com seus respectivos módulos
function getCursosAndPeriodosByCategories(){
  if(isset($_POST["idCategory"]) && !empty($_POST["idCategory"])){
    $id = $_POST["idCategory"];

    $db = new DataBase("moodle_prod_atual");
    $conn = $db->getConnection();

    $categories = new Categories($conn, Config::$table_prefix);
    $response = $categories->getCursosAndPeriodosByCategories($id);
    echo json_encode($response);
  }else{
    echo json_encode(array("erro" => true, "description" => "Dados insuficientes"));
  }
}

 ?>
