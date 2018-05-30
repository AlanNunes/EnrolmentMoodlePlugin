<?php
/**
 * Created on ??/04/2018
 *
 * @category   Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
include_once('../database/DataBase_Moodle.php');
include_once('../database/DataBase_Externo.php');
include_once('Enrolments.php');
include_once('EnrolmentsGraduacao.php');
include_once('EnrolmentsPosGraduacao.php');
include_once('../grades/Grades.php');
include_once('../modulos/Modulos.php');
include_once('../logs/Logs.php');

// Connect to External Database
$dbExternal = new DataBase_Externo();
$connExternal = $dbExternal->getConnection();

// Connect to Moodle
$dbMoodle = new DataBase_Moodle();
$connMoodle = $dbMoodle->getConnection();

// Create an instance for Enrolments
$enrolments = new Enrolments($connMoodle);
$enrolmentsGraduacao = new EnrolmentsGraduacao($connMoodle);
$enrolmentsPosGraduacao = new EnrolmentsPosGraduacao($connMoodle);
// Cria uma instância da Classe Grades(matrizes)
$grades = new Grades($connExternal);
// Create an instance of Modulos with connection to external database
$modulos = new Modulos($connExternal);
// Setting moodle folder name
$moodle_folder_name = 'moodle';
$moodle_path = $_SERVER['DOCUMENT_ROOT']."/".$moodle_folder_name."/";
echo $GLOBALS['moodle_path'] . PHP_EOL;

// Get all the students that are not enrolled in any course from POS-EAD
$studentsNotEnrolledsPosGrad = $enrolmentsPosGraduacao->getStudentsNotSubscribedInAnyCourse();
// TDD
var_dump($studentsNotEnrolledsPosGrad);
if($studentsNotEnrolledsPosGrad['students']){
  foreach($studentsNotEnrolledsPosGrad['students'] as $student){
    // Get basic information of the student
    $username = $student['username'];
    list($city, $course) = explode("-", $student['lastname']);
    // End getting basic information
    // Matricula aluno da Pós EAD no primeiro módulo, da matriz ativa atual
    matriculaAlunoPosEAD($student, $course);
  }
}

// *************************************************************************************************

// Get all the students that are not enrolled in any course from POS-EAD
$studentsNotEnrolledsGraduacao = $enrolmentsGraduacao->getStudentsNotSubscribedInAnyCourse();
// TDD
var_dump($studentsNotEnrolledsGraduacao);
if($studentsNotEnrolledsGraduacao['students']){
  foreach($studentsNotEnrolledsGraduacao['students'] as $student){
    // Get basic information of the student
    $username = $student['username'];
    if($student['lastname'] != ' '){
      list($city, $course) = explode("-", $student['lastname']);
      // Matricula aluno da Pós EAD no primeiro módulo, da matriz ativa atual
      matriculaAlunoGraduacao($student, $course);
    }
  }
}

// Create an instance for students of Pós-Graduação
// $enrolmentsPosGraduacao = new EnrolmentsPosGraduacao($connExternal);
$enrolmentsPosGraduacao->conn = $connExternal;
$studentsPosGradExpiredTime = $enrolmentsPosGraduacao->getStudentsByTimeEnrolment(60*1);
echo '<p>Estudantes da Pós-Graduação com mais de {60*1} segundos inscritos num curso</p>';
var_dump($studentsPosGradExpiredTime);

// Just go into the IF scope if there is some students not enrolled in any course
if($studentsPosGradExpiredTime['students']){
  foreach($studentsPosGradExpiredTime['students'] as $student){
    // It switches the database connection to 'External Database'
    $enrolments->conn = $GLOBALS['connExternal'];
    // It switches the database connection to 'External Database'
    // $enrolments->conn = $connExternal;
    echo "<h1>shortnamecourse: ".$student['shortnamecourse']."</h1>";
    $enrolmentsPosGraduacao->enrolInNextCourse($student['username'], $student['shortnamecourse'], $student['matriz']);
    // Execute the sync.php
    exec("php ".$GLOBALS['moodle_path'].'/enrol/database/cli/sync.php');
  }
}

function matriculaAlunoPosEAD($student, $course){
  // Create an instance for Enrolments
  $enrolments = new Enrolments($GLOBALS['connMoodle']);
  // Cria uma instância da Classe Grades(matrizes)
  $grades = new Grades($GLOBALS['connExternal']);
  // Create an instance of Modulos with connection to external database
  $modulos = new Modulos($GLOBALS['connExternal']);
  // Cria uma instância de Logs
  $logs = new Logs($GLOBALS['connExternal']);

  echo "<h1>Aluno é da Pós-EaD:</h1> <h3>{$student['username']}</h3>";
  $username = $student['username'];
  $matrizResponse = $grades->getMatrizByCourseAndModalidade($course, 'EAD');
  if(!$matrizResponse["erro"]){
    $matriz = $matrizResponse['matriz']; // Pega a matriz que o aluno deve ser inscrito na matricula
    // Pega o 'shortname' do curso que aparece em primeiro na lista da matriz
    $modulosResponse = $modulos->getShortNameCourseByGrade($matriz);
    $shortnamecourse = $modulosResponse["shortnamecourse"];
    // It switches the connection of enrolments from moodle to the external database's connection
    $enrolments->conn = $GLOBALS['connExternal'];
    // Enroll the student the course and catch the response of it's process
    $enrolmentResponse = $enrolments->enrolStudentInCourse($username, $shortnamecourse, $matriz);
    $logs->saveLog($enrolmentResponse['description'], $enrolmentResponse['erro'], $enrolmentResponse['more']);
    // Execute the sync.php
    exec("php ".$GLOBALS['moodle_path'].'/enrol/database/cli/sync.php');
    echo json_encode($enrolmentResponse);
  }else{
    // Houve algum erro ao buscar a matriz
    // save in log the error
    $logs->saveLog($matrizResponse['description'], $matrizResponse['erro'], $matrizResponse['more']);
  }
}

function matriculaAlunoGraduacao($student, $course){
  // Create an instance for Enrolments
  $enrolments = new Enrolments($GLOBALS['connExternal']);
  // Cria uma instância da Classe Grades(matrizes)
  $grades = new Grades($GLOBALS['connExternal']);
  // Create an instance of Modulos with connection to external database
  $modulos = new Modulos($GLOBALS['connExternal']);
  // Cria uma instância de Logs
  $logs = new Logs($GLOBALS['connExternal']);

  echo "<h1>Aluno é da Graduação:</h1> <h3>{$student['username']}, {$course}</h3>";
  $username = $student['username'];
  $matrizResponse = $grades->getMatrizByCourseAndModalidade($course, 'PRESENCIAL');
  if(!$matrizResponse["erro"]){
    $matriz = $matrizResponse['matriz']; // Pega a matriz que o aluno deve ser inscrito na matricula
    echo "matriz graduação: {$matriz}";
    $modulosResponse = $modulos->getModulosByMatrizId($matriz);
    if($modulosResponse['erro']){
      $logs->saveLog($modulosResponse['description'], $modulosResponse['erro'], $modulosResponse['more']);
      echo json_encode($modulosResponse);
    }else{
      $modulosArray = $modulosResponse['modulos'];
      // Esta função matricula o aluno em todos os módulos passados como array, a identificação dos módulos, ou courses, são por shortname
      $enrolmentResponse = $enrolments->enrolAlunoByModulosShortName($username, $modulosArray, $matriz);
      if($enrolmentResponse['erro']){
        $logs->saveLog($enrolmentResponse['description'], $enrolmentResponse['erro'], $enrolmentResponse['more']);
        echo json_encode($enrolmentResponse);
      }else{
        $logs->saveLog($enrolmentResponse['description'], $enrolmentResponse['erro'], $enrolmentResponse['more']);
        echo json_encode($enrolmentResponse);
      }
    }
    // Execute the sync.php
    $teste = exec("php ".$GLOBALS['moodle_path'].'/enrol/database/cli/sync.php');
    echo $teste;
    // echo json_encode($enrolmentResponse);
  }else{
    // Houve algum error ao buscar a matriz
    // save in log the error
    $logs->saveLog($matrizResponse['description'], $matrizResponse['erro'], $matrizResponse['more']);
    echo json_encode($matrizResponse);
  }
}

?>
