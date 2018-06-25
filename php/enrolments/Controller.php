<?php
/**
 * Created on ??/04/2018
 *
 * @category   Enrolments
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
include_once('../database/DataBase.php');
include_once('../config/Config.php');
include_once('Enrolments.php');
include_once('EnrolmentsGraduacao.php');
include_once('EnrolmentsPosGraduacao.php');
include_once('../grades/Grades.php');
include_once('../modulos/Modulos.php');
include_once('../mails/Outlook_Mails.php');

/**
* Define o path do sync
*/
$sync_path = 'C:\wamp\www\moodle\enrol\database\cli\sync.php';
/**
* DataBase Connection
*
* Connect to the external database
*/
$dbExternal = new DataBase("external_enrolment");
$connExternal = $dbExternal->getConnection();

/**
* DataBase Connection
*
* Connect to moodle database
*/
$dbMoodle = new DataBase("moodle");
$connMoodle = $dbMoodle->getConnection();

/**
* Enrolments
*
* Create an instance for Enrolments
*/
$enrolments = new Enrolments($connMoodle, Config::$table_prefix);
/**
* Enrolments
*
* Create an instance for Enrolments from Graduação
*/
$enrolmentsGraduacao = new EnrolmentsGraduacao($connMoodle, Config::$table_prefix);
/**
* Enrolments
*
* Create an instance for Enrolments from Pós-Graduação
*/
$enrolmentsPosGraduacao = new EnrolmentsPosGraduacao($connMoodle, Config::$table_prefix);
/**
* Grades
*
* Create an instance for Grades(matrizes)
*/
$grades = new Grades($connExternal);
/**
* Módulos
*
* Create an instance for Módulos
*/
$modulos = new Modulos($connExternal);

/**
* Enrolments
*
* @var array students not subscribed in any course
* Get all the students that are not enrolled in any course from POS-EAD
* The response is an array containing
*/
$studentsNotEnrolledsPosGrad = $enrolmentsPosGraduacao->getStudentsNotSubscribedInAnyCourse();

var_dump($studentsNotEnrolledsPosGrad); // TDD

/**
* Check if exists students not subscribed in any course
*/
if($studentsNotEnrolledsPosGrad['students']){
  foreach($studentsNotEnrolledsPosGrad['students'] as $student){
    $username = $student['username'];
    /**
    * Save student's data in '$city' and '$course'
    * It assumes that the student's lastnme has this default: 'CITY-COURSE'
    * Example: 'VR-SIS'
    */
    list($city, $modalidade, $course) = explode("-", $student['lastname']);
    /**
    * Enrolments
    *
    * Matricula aluno da Pós EAD no primeiro módulo, da matriz ativa atual
    *
    * @param array $student Array structure that contains student basic info
    * @param string $course Contains the course's shortname, example 'SIS'
    */
    matriculaAlunoPosEAD($student, $course);
  }
}

// ****************************************************************************

/**
* Enrolments
*
* @var array students not subscribed in any course
* Get all the students that are not enrolled in any course from Graduação
* The response is an array containing
*/
$studentsNotEnrolledsGraduacao = $enrolmentsGraduacao->getStudentsNotSubscribedInAnyCourse();

var_dump($studentsNotEnrolledsGraduacao); // TDD

/**
* Check if exists students not subscribed in any course
*/
if($studentsNotEnrolledsGraduacao['students']){
  foreach($studentsNotEnrolledsGraduacao['students'] as $student){
    $username = $student['username'];
    /**
    * As moodle creates a default user called 'guest' with a blank space in it's
    * lastname, this conditional checks for it, and prevent erros
    */
    if($student['lastname'] != ' '){
      /**
      * Save student's data in '$city', '$course', '$horario', '$tipo', '$periodo'
      * It assumes that the student's lastnme has this
      * default: 'CITY-COURSE-SCHEDULE-TYPE-PERIODO'
      * Example: 'VR-SIS-N18.1-REG-1PER'
      */
      list($city, $course, $horario, $tipo, $periodo) = explode("-", $student['lastname']);
      // Pega somente o número do período
      $periodo = preg_replace("/[^0-9]/", '', $periodo);
      /**
      * Enrolments
      *
      * Matricula aluno da Pós EAD no primeiro módulo, da matriz ativa atual
      *
      * @param array $student Array structure that contains student basic info
      * @param string $course Contains the course's shortname, example 'SIS'
      * @param integer $periodo It has the período number
      */
      matriculaAlunoGraduacao($student, $course, $periodo);
    }
  }
}

/**
* Change connection's to external database
*/
$enrolmentsPosGraduacao->conn = $connExternal;
/**
* Gets all the students that has the time expired by the paramater
* @param integer Limit time of enrolment
*/
$studentsPosGradExpiredTime = $enrolmentsPosGraduacao->getStudentsByTimeEnrolment(60*5);
echo '<p>Estudantes da Pós-Graduação com mais de {60*1} segundos inscritos num curso</p>'; // TDD
var_dump($studentsPosGradExpiredTime); // TDD

/**
* It checks if there's any student at limit time of enrolment
*/
if($studentsPosGradExpiredTime['students']){
  foreach($studentsPosGradExpiredTime['students'] as $student){
    // It switches the database connection to 'External Database'
    $enrolments->conn = $GLOBALS['connExternal'];
    echo "<h1>shortnamecourse: ".$student['shortnamecourse']."</h1>"; // TDD
    /**
    * Enrolments
    *
    * Enrol the student in the next course(módulo)
    * @param string username Student's username
    * @param string shortnamecourse Course's shortname
    * @param integer matriz Matriz(Grade) to be enrolled
    * @return array It returns a colletions of information about the process
    */
    $enrolmentsPosGraduacao->enrolInNextCourse($student['username'], $student['shortnamecourse'], $student['matriz']);
    /**
    * Execute the sync
    */
    exec("php ".$GLOBALS['sync_path']);
  }
}

/**
* Enrolments
*
* Enrols the student from Pós-Graduação in a course
* @param array $student Basic student information
* @param integer $course Id of the course to be enrolled
*/
function matriculaAlunoPosEAD($student, $course){
  /**
  * Enrolments
  *
  * Create an instance for Enrolments
  */
  $enrolments = new Enrolments($GLOBALS['connMoodle'], Config::$table_prefix);
  /**
  * Grades - Matrizes
  *
  * Create an instance for Grades(matrizes)
  */
  $grades = new Grades($GLOBALS['connExternal']);
  /**
  * Módulos - Courses
  *
  * Create an instance for Módulos(courses)
  */
  $modulos = new Modulos($GLOBALS['connExternal']);

  echo "<h1>Aluno é da Pós-EaD:</h1> <h3>{$student['username']}</h3>"; // TDD
  $username = $student['username'];
  /**
  * Get student firstname
  * It assumes that the student firstname has the default: Firstname Surname
  */
  list($firstname) = explode(" ", $student['firstname']);
  $email = $student['email'];
  /**
  * Grades
  *
  * Pega o id da matriz de acordo com o curso e a modalidade
  * @param string $course Shortname do curso
  * @param string Modalidade do curso
  * @return array Returns a colletion of description about the process
  */
  $matrizResponse = $grades->getMatrizByCourseAndModalidade($course, 'EAD');
  if(!$matrizResponse["erro"]){
    $matriz = $matrizResponse['matriz']; // Pega a matriz que o aluno deve ser inscrito na matricula
    $modulosResponse = $modulos->getShortNameCourseByGrade($matriz); // Pega o 'shortname' do curso que aparece em primeiro na lista da matriz
    $shortnamecourse = $modulosResponse["shortnamecourse"];
    // It switches the connection of enrolments from moodle to the external database's connection
    $enrolments->conn = $GLOBALS['connExternal'];
    /**
    * Enrolments
    *
    * Enroll the student in the course - módulo
    * @param string $username Student username
    * @param string $shortnamecourse Course shortname
    * @param integer $matriz Grade's id - Id da matriz
    * @return array Returns a colletion of description about the process
    */
    $enrolmentResponse = $enrolments->enrolStudentInCourse($username, $shortnamecourse, $matriz);
    if(!$enrolmentResponse['erro']){
      // Envia e-mail
      $mail = new Outlook_Mails();
      $mail->to = $student['email'];
      $greeting = welcome(date('H'));
      $mail->saudacao = "{$greeting} <strong>{$firstname}</strong> !";
      $mail->msg = "<br/>Você foi matriculado(a) no curso {$course} e já possui acesso ao NEAD.<br/><strong>Att.</strong>";
      $mail->setConfig();
      // $mail->sendMail();
      // Fim de envio de e-mail
    }
    // Execute the sync.php
    exec("php ".$GLOBALS['sync_path']);
    echo json_encode($enrolmentResponse); // TDD
    return $enrolmentResponse;
  }else{
    // Houve algum erro ao buscar a matriz
    return $matrizResponse;
  }
}

function matriculaAlunoGraduacao($student, $course, $periodo){
  // Create an instance for Enrolments
  $enrolments = new Enrolments($GLOBALS['connExternal'], Config::$table_prefix);
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
        $mail->msg = "<br/>Você foi matriculado(a) no curso {$course} e já possui acesso ao NEAD.<br/><strong>Att.</strong>";
        $mail->setConfig();
        // $mail->sendMail();
        // Fim de envio de e-mail
        echo json_encode($enrolmentResponse);
      }
    }
    // Execute the sync.php
    exec("php ".$GLOBALS['sync_path']);
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
