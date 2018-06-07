<?php
/**
 * Created on ??/04/2018
 *
 * @category   Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
include_once('../database/DataBase.php');
include_once('Enrolments.php');
include_once('EnrolmentsGraduacao.php');
include_once('EnrolmentsPosGraduacao.php');
include_once('../grades/Grades.php');
include_once('../modulos/Modulos.php');
include_once('../mails/Outlook_Mails.php');

// Connect to the External Database
$dbExternal = new DataBase("external_enrolment");
$connExternal = $dbExternal->getConnection();

// Connect to the Moodle
$dbMoodle = new DataBase("moodle");
$connMoodle = $dbMoodle->getConnection();

// Create an instance for Enrolments
$enrolments = new Enrolments($connMoodle);
$enrolmentsGraduacao = new EnrolmentsGraduacao($connMoodle);
$enrolmentsPosGraduacao = new EnrolmentsPosGraduacao($connMoodle);
// Cria uma instância da Classe Grades(matrizes)
$grades = new Grades($connExternal);
// Create an instance of Modulos with connection to external database
$modulos = new Modulos($connExternal);

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
      list($city, $course, $horario, $tipo, $periodo) = explode("-", $student['lastname']);
      $periodo = preg_replace("/[^0-9]/", '', $periodo);
      // Matricula aluno da Pós EAD no primeiro módulo, da matriz ativa atual
      matriculaAlunoGraduacao($student, $course, $periodo);
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
    exec('php C:\wamp\www\moodle\enrol\database\cli\sync.php');
  }
}

function matriculaAlunoPosEAD($student, $course){
  // Create an instance for Enrolments
  $enrolments = new Enrolments($GLOBALS['connMoodle']);
  // Cria uma instância da Classe Grades(matrizes)
  $grades = new Grades($GLOBALS['connExternal']);
  // Create an instance of Modulos with connection to external database
  $modulos = new Modulos($GLOBALS['connExternal']);

  echo "<h1>Aluno é da Pós-EaD:</h1> <h3>{$student['username']}</h3>";
  $username = $student['username'];
  list($firstname) = explode(" ", $student['firstname']);
  $email = $student['email'];
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
    if(!$enrolmentResponse['erro']){
      // Envia e-mail
      $mail = new Outlook_Mails();
      $mail->to = $student['email'];
      $greeting = welcome(date('H'));
      $mail->saudacao = "{$greeting} <strong>{$firstname}</strong> !";
      $mail->msg = "<br/>Você foi matriculada no curso {$course} e já possui acesso ao NEAD.<br/><strong>Att.</strong>";
      $mail->setConfig();
      // $mail->sendMail();
      // Fim de envio de e-mail
    }
    // Execute the sync.php
    exec('php C:\wamp\www\moodle\enrol\database\cli\sync.php');
    echo json_encode($enrolmentResponse);
  }else{
    // Houve algum erro ao buscar a matriz
    // save in log the error
  }
}

function matriculaAlunoGraduacao($student, $course, $periodo){
  // Create an instance for Enrolments
  $enrolments = new Enrolments($GLOBALS['connExternal']);
  // Cria uma instância da Classe Grades(matrizes)
  $grades = new Grades($GLOBALS['connExternal']);
  // Create an instance of Modulos with connection to external database
  $modulos = new Modulos($GLOBALS['connExternal']);

  echo "<h1>Aluno é da Graduação:</h1> <h3>{$student['username']}, {$course}</h3>";
  $username = $student['username'];
  list($firstname) = explode(" ", $student['firstname']);
  $email = $student['email'];
  $matrizResponse = $grades->getMatrizByCourseAndModalidade($course, 'PRESENCIAL');
  if(!$matrizResponse["erro"]){
    $matriz = $matrizResponse['matriz']; // Pega a matriz que o aluno deve ser inscrito na matricula
    echo "matriz graduação: {$matriz}";
    $modulosResponse = $modulos->getModulosByMatrizIdAndPeriodo($matriz, $periodo);
    if($modulosResponse['erro']){
      echo json_encode($modulosResponse);
    }else{
      $modulosArray = $modulosResponse['modulos'];
      // Esta função matricula o aluno em todos os módulos passados como array, a identificação dos módulos, ou courses, são por shortname
      $enrolmentResponse = $enrolments->enrolAlunoByModulosShortName($username, $modulosArray, $matriz);
      if($enrolmentResponse['erro']){
        $descricaoErro = $enrolmentResponse['description'];
        $sqlErro = $enrolmentResponse['more'];
        // Envia e-mail para os administradores reportando o erro
        $mail = new Outlook_Mails();
        $mail->to = "alannunes@ugb.edu.br";
        $greeting = welcome(date('H'));
        $mail->subject = "NEAD - UGB/FERP";
        $mail->saudacao = "{$greeting} <strong>EQUIPE NEAD</strong> !";
        $mail->msg = "O aluno com o username {$username} tentou ser matriculado na matriz {$matriz} porém o processo falhou.<br/>
                      <strong>Descrição</stron>:{$descricaoErro}.<br/><strong>SQL's description</strong>: {$sqlErro}";
        $mail->setConfig();
        $mail->sendMail();
        $mail->to = "gustavobrandao@ugb.edu.br";
        $mail->sendMail();
        // Fim de envio de e-mail reportando o erro
        echo json_encode($enrolmentResponse);
      }else{
        // Envia e-mail
        $mail = new Outlook_Mails();
        $mail->to = $student['email'];
        $greeting = welcome(date('H'));
        $mail->saudacao = "{$greeting} <strong>{$firstname}</strong> !";
        $mail->subject = "NEAD - UGB/FERP";
        $mail->msg = "<br/>Você foi matriculada no curso {$course} e já possui acesso ao NEAD.<br/><strong>Att.</strong>";
        $mail->setConfig();
        // $mail->sendMail();
        // Fim de envio de e-mail
        echo json_encode($enrolmentResponse);
      }
    }
    // Execute the sync.php
    exec('php C:\wamp\www\moodle\enrol\database\cli\sync.php');
    // echo json_encode($enrolmentResponse);
  }else{
    // Houve algum erro ao buscar a matriz
    // save in log the error
    echo json_encode($matrizResponse);
  }
}

function welcome($h){
   if($h < 12){
     return "Bom dia";
   }elseif($h > 11 && $h < 18){
     return "Boa tarde";
   }elseif($h > 17){
    return "Boa noite";
   }
}

?>
