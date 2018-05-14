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

// Connect to the External Database
$dbExternal = new DataBase("external_enrolment");
$connExternal = $dbExternal->getConnection();

// Connect to the Moodle
$dbMoodle = new DataBase("moodle");
$connMoodle = $dbMoodle->getConnection();

$enrolment = new Enrolments($connExternal);
$shortnameCourse = $enrolment->getCurrentEnrolmentShortNameCourse('jacare');
// Switch connection to 'Moodle'
$enrolment->conn = $connMoodle;
$infoEnrolment = $enrolment->getEnrolmentInfo($shortnameCourse);

var_dump($infoEnrolment);
$today = time();

if( ($today - $infoEnrolment["timecreated"]) > 60*5){
  $enrolment->conn = $connExternal;
  $enrolment->enrolInNextCourse('jacare', $shortnameCourse);
}
?>
