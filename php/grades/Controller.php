<?php
/**
 * Created on ??/05/2018
 *
 * @category   Controller of Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
require_once('../categories/Categories.php');
require_once('../courses/Courses.php');
require_once('../database/DataBase_Moodle.php');
require_once('../database/DataBase_Externo.php');
require_once('Grades.php');

switch ($_POST["action"]) {
  // It calls the function to register the Grade(Matriz, grade curricular)
  case 'registerGrade':
    registerGrade();
    break;

  case 'getGrades':
    listGrades();
    break;

  default:
    echo json_encode(array("erro" => true, "description" => "No process found for this action"));
    break;
}

function registerGrade(){
  if(isset($_POST["data"]) && !empty($_POST["data"])){
    $data = $_POST["data"];

    // Verify fields
      $invalidFields = array();
      if(empty($data["nomeMatriz"])) array_push($invalidFields, "nomeMatriz");
      if(empty($data["cursos"])) array_push($invalidFields, "cursos");
      if(empty($data["modalidade"])) array_push($invalidFields, "modalidade");
      if(empty($data["categoryIdNumber"])) array_push($invalidFields, "cursos");
    // End
    if(!empty($invalidFields)){
      echo json_encode(array("erro" => true, "description" => "Opa! Parece que você esqueceu de preencher algum campo. Dê uma olhadinha!", "invalidFields" => $invalidFields));
    }else{
      $nome = $data["nomeMatriz"];
      $cursos = $data["cursos"];
      $modalidade = $data["modalidade"];
      // No Moodle, a categoria do curso vai ter um idnumber que identifica o curso
      // Em nosso Banco de Dados Externo temos uma tabela com todos os cursos e esses cursos tem como atributo 'shortname' o mesmo valor de 'idnumber' da categoria que identifica o curso
      $shortnamecourse = $data["categoryIdNumber"];

      $db = new DataBase_Externo();
      $conn = $db->getConnection();

      $grade = new Grades($conn);
      $courses = new Courses($conn);
      // Pega o id do curso, na tabela de cursos do Banco de Dados Externo
      // PS.: O shortname do curso na tabela do BD externo deve ser o mesmo de idnumber, da categoria que identifica
      // o curso, no Moodle
      $courseResponse = $courses->getCourseIdByShortName($shortnamecourse);
      if($courseResponse['erro']){
        echo json_encode($courseResponse);
      }else{
        $idCurso = $courseResponse['id'];
        // Registra uma nova matriz, com o nome, id do curso e a modalidade
        $responseGrade = $grade->registerGrade($nome, $idCurso, $modalidade);
        if($responseGrade['erro']){
          echo json_encode($responseGrade);
        }else{
          $matriz = $responseGrade['id'];
          // Registra todos os courses tendo uma matriz como referência
          // O parâmetro $cursos é uma array que já vem em ordem
          // Cada linha da array $cursos significa um novo período
          $responseModulos = $grade->registerModulos($matriz, $cursos);
          echo json_encode($responseModulos);
        }
      }
    }
  }else{
    echo json_encode(array("erro" => true, "description" => "It's missing data. Check if all the fields are filled"));
  }
}


function getGrades(){
  $db = new DataBase_Externo();
  $conn = $db->getConnection();

  $grade = new Grades($conn);
  $response = $grade->getGrades();

  echo json_encode($response);
}
 ?>
