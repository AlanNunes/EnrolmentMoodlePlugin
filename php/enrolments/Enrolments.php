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
  /**
  * @var integer Id da matrícula
  */
  private $id;
  /**
  * @var integer Momento o qual o aluno foi matriculado
  */
  private $timecreated;
  /**
  * @var integer Id do estudante
  */
  private $userid;
  /**
  * @var string Prefixo das tabelas
  */
  public $table_prefix;
  /**
  * Conexão do Banco de Dados
  */
  public $conn;

  public function __construct($conn, $table_prefix){
    $this->conn = $conn;
    $this->table_prefix = $table_prefix;
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
    $sql = "SELECT c.id,c.shortname, c.sortorder, ue.timecreated
    FROM moodle.{$this->table_prefix}user_enrolments ue
    INNER JOIN moodle.{$this->table_prefix}enrol e ON ue.enrolid = e.id
    INNER JOIN moodle.{$this->table_prefix}course c ON c.id = e.courseid
    AND c.shortname = '$shortname'";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      // Return the data
      return $row;
    }
    // Return a error
    return 1;
  }

  // This function returns all the students that are not subscribed in any couse
  public function getStudentsNotSubscribedInAnyCourse(){
    $sql = "select distinct username, lastname from {$this->table_prefix}user where {$this->table_prefix}user.id
    not in (select userid from {$this->table_prefix}user_enrolments)";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      while($student = $result->fetch_assoc()){
        $students[] = $student;
      }
      return array("erro" => false, "description" => "There is some students
      that are not subscribed in any course.", "students" => $students);
    }else{
      return array("erro" => false, "description" => "All the students are at
      least subscribed in one course.", "students" => 0);
    }
  }

  // Enrol a student in a course - in the external database
  // Matricula um aluno no banco de dados externo
  public function enrolStudentInCourse($username, $shortnamecourse, $matriz){
    $timecreated = time();
    $sql = "INSERT INTO enrolments (shortnamecourse, username, shortnamerole,
      matriz, timecreated) VALUES ('{$shortnamecourse}', '{$username}', DEFAULT,
         {$matriz}, {$timecreated})";
    if($this->conn->query($sql)){
      return array("erro" => false, "description" => "O aluno foi matriculado no
       curso com sucesso");
    }else{
      return array("erro" => true, "description" => "O aluno não foi matriculado
       no curso", "more" => $this->conn->error);
    }
  }

  // This function gets all the students who are enrolled in a course for more
  // than a time, specified in the paramater $time
  // Esta função recolhe todos os alunos que estão matriculados em um curso por
  // mais de um tempo específico, passado como parâmetro $time
  public function getStudentsByTimeEnrolment($time){
    $sql = "SELECT ue.timecreated, u.username FROM moodle.{$this->table_prefix}user_enrolments ue
     INNER JOIN moodle.{$this->table_prefix}enrol e ON ue.enrolid = e.id
            INNER JOIN moodle.{$this->table_prefix}course c ON c.id = e.courseid
            INNER JOIN moodle.{$this->table_prefix}user u ON u.id = ue.userid
              AND (unix_timestamp() - ue.timecreated) > {$time}";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $students[] = $row;
      }
      return array("erro" => false, "description" => "Foram encontrado alunos
      com mais de {$time} segundos de incrição em um curso.", "students" => $students);
    }else{
      return array("erro" => false, "description" => "Nenhum aluno foi encontrado
       com mais {$time} segundos de inscrição em um curso.", "students" => 0);
    }
  }

  // Matricula o aluno em todos os módulos passados na array, o módulo irá ser
  // identificado por shortname
  public function enrolAlunoByModulosShortName($username, $modulos, $matriz){
    $size = sizeof($modulos);
    $sql = "";
    for( $i = 0; $i < $size; $i++ ){
      $timecreated = time();
      $shortnamecourse = $modulos[$i];
      $sql .= "INSERT INTO enrolments (shortnamecourse, username, shortnamerole,
         matriz, timecreated) VALUES ('{$shortnamecourse}', '{$username}',
           'student', {$matriz}, {$timecreated});";
    }
    if($this->conn->multi_query($sql)){
      return array("erro" => false, "description" => "O aluno '{$username}'
      foi matriculado com sucesso nas disciplinas(courses), referente à matriz '{$matriz}'");
    }else{
      return array("erro" => true, "description" => "O aluno '{$username}'
      não foi matriculado nas disciplinas(courses), referente à matriz '{$matriz}'",
      "more" => $this->conn->error);
    }
  }
}
?>
