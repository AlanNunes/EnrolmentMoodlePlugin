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

 $enrolmentsPosGraduacao = new EnrolmentsPosGraduacao($connMoodle, Config::$table_prefix);

 $studentsNotEnrolledsPosGrad = $enrolmentsPosGraduacao->getStudentsNotSubscribedInAnyCourse();

 foreach ($studentsNotEnrolledsPosGrad['students'] as $student)
 {
   // Checa somente o lastname do usuário com este username
   if ($student['username'] == '2018101793')
   {
     $lastname = $student['lastname'];
     list($city, $tipoCurso, $course, $modalidade) = explode("-", $student['lastname']);
     $course = $tipoCurso . "-" . $course . "-" . $modalidade;

     $expected = 'POS-EIL-EAD';

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
