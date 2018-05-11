<?php
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
