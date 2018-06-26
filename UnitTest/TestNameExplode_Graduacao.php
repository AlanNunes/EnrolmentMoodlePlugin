<?php
/**
 * Created on 26/06/2018
 *
 * This tests the explode lastname of the user
 *
 * @category   Unit Test
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */

 include_once('../php/database/DataBase.php');
 include_once('../php/config/Config.php');
 include_once('../php/enrolments/Enrolments.php');
 include_once('../php/enrolments/EnrolmentsGraduacao.php');
 include_once('../php/enrolments/EnrolmentsPosGraduacao.php');

 $dbMoodle = new DataBase("moodle");

 $connMoodle = $dbMoodle->getConnection();

 $enrolmentsGraduacao = new EnrolmentsGraduacao($connMoodle, Config::$table_prefix);

 $studentsNotEnrolledsGraduacao = $enrolmentsGraduacao->getStudentsNotSubscribedInAnyCourse();

 foreach ($studentsNotEnrolledsGraduacao['students'] as $student)
 {
   // Checa somente o lastname do usuário com este username
   if ($student['username'] == '2018101794')
   {
     $lastname = $student['lastname'];
     list($city, $course, $horario, $tipo, $periodo) = explode("-", $student['lastname']);
     if( $course != 'EDU' )
     {
       $course = $city . '-' . $course . '-' . $horario[0];
     }
     else if ($course == 'EDU')
     {
       $course = $city . '-' . $course . '-' . $horario . '-' . $tipo[0];
     }
     $expected = 'BP-EDU-L-N';
     try {
       if ( $course === $expected )
       {
         echo true;
       }
       else
       {
         throw new Exception("Course and Expected doesn't match");
       }
     }
     catch (Exception $e)
     {
       echo $e;
     }

   }
 }

 ?>
