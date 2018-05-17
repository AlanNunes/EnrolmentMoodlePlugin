<?php
/**
 * Created on ??/04/2018
 *
 * @category   Grades
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Enrolments {
  private $id;
  private $timecreated;
  private $userid;
  public $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }

  // Get the shortname of the current enrolment's student
  public function getCurrentEnrolmentShortNameCourse($username){
    $sql = "SELECT shortnamecourse FROM enrolments WHERE username = '$username'";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      // Return the shortname
      return $row["shortnamecourse"];
    }
    // Return a error
    return 1;
  }

  // Get the time when the student was enrolled in the course
  public function getEnrolmentInfo($shortname){
    $sql = "SELECT c.id,c.shortname, c.sortorder, ue.timecreated FROM moodle.mdl_user_enrolments ue INNER JOIN moodle.mdl_enrol e ON ue.enrolid = e.id INNER JOIN moodle.mdl_course c ON c.id = e.courseid AND c.shortname = '$shortname'";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      // Return the data
      return $row;
    }
    // Return a error
    return 1;
  }

  // This method returns the 'shortname' of the next course that the user should be enrolled
  public function getNextCourse($shortnameCourse){
    $sql = "SELECT shortname FROM moodle.mdl_course WHERE sortorder < (SELECT sortorder FROM moodle.mdl_course WHERE shortname = '$shortnameCourse') AND shortname != 'UGB/FERP' ORDER BY sortorder DESC LIMIT 1";
    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();
    return $row["shortname"];
  }

  public function enrolInNextCourse($username, $shortnameCourse){
    $sql = "DELETE FROM enrolments WHERE username = '$username' AND shortnamecourse = '$shortnameCourse'";
    if($this->conn->query($sql)){
      echo "Student removed from: " . $shortnameCourse;
      $shortnameCourseNext = $this->getNextCourse($shortnameCourse);
      if(!empty($shortnameCourseNext)){
        $sql = "INSERT INTO enrolments (shortnamecourse, username, shortnamerole) VALUES ('$shortnameCourseNext', '$username', DEFAULT)";
        if($this->conn->query($sql)){
          echo "Student added to: " . $shortnameCourseNext;
        }
      }
      exec('php C:\wamp\www\moodle\enrol\database\cli\sync.php');
    }
    return 1;
  }

  // This function returns all the students that are not subscribed in any couse
  public function getStudentsNotSubscribedInAnyCourse(){
    $sql = "select distinct username, lastname from moodle.mdl_user where mdl_user.id not in (select userid from moodle.mdl_user_enrolments)";
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

  // Enrol a student in a course - in the external database
  public function enrolStudentInCourse($username, $shortnamecourse, $matriz){
    $sql = "INSERT INTO (shortnamecourse, username, shortnamerole, matriz) VALUES ('{$shortnamecourse}', '{$username}', 'DEFAULT',{$matriz})";
    if($this->conn->query($sql)){
      return array("erro" => false, "description" => "O aluno foi matriculado no curso com sucesso");
    }else{
      return array("erro" => true, "description" => "O aluno não foi matriculado no curso", "more" => $this->conn->error);
    }
  }
}
?>
