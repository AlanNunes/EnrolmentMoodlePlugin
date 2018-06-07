<?php
// Class de matrículas da Pós-Graduação
Class EnrolmentsGraduacao extends Enrolments{


  public function getStudentsNotSubscribedInAnyCourse(){
    $sql = "select distinct username, lastname, firstname, email from moodle.mdl_user u where u.id not in (select userid from moodle.mdl_user_enrolments ue
            INNER JOIN moodle.mdl_enrol en ON en.id = ue.enrolid INNER JOIN moodle.mdl_course c ON c.id = en.courseid
              INNER JOIN external_enrolment.enrolments enr ON enr.shortnamecourse = c.shortname) AND u.lastname NOT LIKE '%POS-EAD%'";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      while($student = $result->fetch_assoc()){
        $students[] = $student;
      }
      return array("erro" => false, "description" => "There is some students tha are not subscribed in any course.", "students" => $students);
    }else{
      return array("erro" => false, "description" => "All the students are at least subscribed in one course.", "students" => 0);
    }
  }
}
?>
