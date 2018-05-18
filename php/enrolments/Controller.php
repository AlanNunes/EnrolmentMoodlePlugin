<?php
/**
 * Created on ??/04/2018
 *
 * @category   Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
include_once('DataBase.php');
include_once('Enrolments.php');
include_once('../grades/Grades.php');
include_once('../modulos/Modulos.php');

// Connect to the External Database
$dbExternal = new DataBase("external_enrolment");
$connExternal = $dbExternal->getConnection();

// Connect to the Moodle
$dbMoodle = new DataBase("moodle");
$connMoodle = $dbMoodle->getConnection();

// Create an instance for Enrolments
$enrolments = new Enrolments($connMoodle);
// Get all the students that are not enrolled in any course
$studentsNotEnrolleds = $enrolments->getStudentsNotSubscribedInAnyCourse();

// TDD
var_dump($studentsNotEnrolleds);

foreach($studentsNotEnrolleds['students'] as $student){
  // Get basic information of the student
  $username = $student['username'];
  $lastname = $student['lastname'];
  // This conditional is necessary because moodle creates a user called 'guest' without a lastname
  if($student['lastname'] != ' '){
    list($city, $course) = explode("-", $student['lastname']);
  }
  // End getting basic information

  // Check if the student is from 'Pós-Graduação'
  if(stripos($lastname, "POS-EAD")){
    echo "<h1>Aluno é da Pós-EaD:</h1> <h3>{$student['username']}</h3>";
    $grades = new Grades($connExternal);
    $matrizResponse = $grades->getMatrizByCourseAndModalidade($course, 'EAD');
    if(!$matrizResponse["erro"]){
      $matriz = $matrizResponse['matriz']; // Pega a matriz que o aluno deve ser inscrito na matricula
      // Create an instance of Modulos with connection to external database
      $modulos = new Modulos($connExternal);
      // Pega o 'shortname' do curso que aparece em primeiro na lista da matriz
      $modulosResponse = $modulos->getShortNameCourseByGrade($matriz);
      $shortnamecourse = $modulosResponse["shortnamecourse"];
      // It switches the connection of enrolments from moodle to the external database's connection
      $enrolments->conn = $connExternal;
      // Enroll the student the course and catch the response of it's process
      $enrolmentResponse = $enrolments->enrolStudentInCourse($username, $shortnamecourse, $matriz);
      // Execute the sync.php
      exec('php C:\wamp\www\moodle\enrol\database\cli\sync.php');
      echo json_encode($enrolmentResponse);
    }else{
      // Houve algum erro ao buscar a matriz
      // save in log the error
    }
  }else{
    // Alunos que não são da Pós-EaD
  }
}

// Just go into the IF scope if there is some students not enrolled in any course
if($studentsNotEnrolleds){
  // It switches the database connection to 'External Database'
  $enrolments->conn = $connExternal;
  // Get the current course that the student is enrolled
  $shortnameCourse = $enrolments->getCurrentEnrolmentShortNameCourse('rosenclever');
  // It switches the database connection to 'Moodle Database'
  $enrolments->conn = $connMoodle;
  $infoEnrolment = $enrolments->getEnrolmentInfo($shortnameCourse);

  var_dump($infoEnrolment);
  $today = time();

  if( ($today - $infoEnrolment["timecreated"]) > 60*1){
    // It switches the database connection to 'External Database'
    $enrolments->conn = $connExternal;
    $enrolments->enrolInNextCourse('rosenclever', $shortnameCourse);
  }
}

?>
