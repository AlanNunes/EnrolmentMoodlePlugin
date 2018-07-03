<?php
// Class de matrículas da Pós-Graduação
Class EnrolmentsPosGraduacao extends Enrolments{

  // Pega todos os estudantes da Pós-Graduação EaD que estão matriculados em um curso há pelo menos {$time} de segundos
  public function getStudentsByTimeEnrolment($time){
    $sql = "SELECT * FROM enrolments en INNER JOIN matrizes ma ON ma.id = en.matriz INNER JOIN modalidades_de_cursos mo ON mo.id = ma.modalidade
            AND mo.nome = 'EAD' AND (unix_timestamp() - en.timecreated) > {$time}";
    $result = $this->conn->query($sql);
    if($result){
      if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
          $students[] = $row;
        }
        return array("erro" => false, "description" => "Foram encontrados alunos da Pós-Graduação EaD com mais de {$time} segundos de incrição em um curso.", "students" => $students);
      }else{
        return array("erro" => false, "description" => "Nenhum aluno da Pós-Graduação EaD foi encontrado com mais {$time} segundos de inscrição em um curso.", "students" => 0);
      }
    }else{
      return array("erro" => false, "description" => "Nenhum aluno da Pós-Graduação EaD foi encontrado com mais {$time} segundos de inscrição em um curso.", "students" => 0);
    }
  }

  // This method returns the 'shortname' of the next course that the user should be enrolled
  public function getNextCourse($shortnamecourse, $matriz){
    $sql = "SELECT * FROM modulos
            WHERE matriz = {$matriz} AND sortorder > (SELECT sortorder FROM modulos WHERE matriz = {$matriz} AND shortnamecourse = '{$shortnamecourse}')
            ORDER BY sortorder ASC LIMIT 1";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      $modulo = $result->fetch_assoc();
      return $modulo["shortnamecourse"];
    }
    return 0;
  }

  // Matricula o aluno no próximo course
  // Deleta ele do atual e põe ele no próximo
  public function enrolInNextCourse($username, $shortnamecourse, $matriz){
    $sql = "DELETE FROM enrolments WHERE username = '{$username}' AND shortnamecourse = '{$shortnamecourse}' AND matriz = {$matriz}";
    if($this->conn->query($sql)){
      $shortnameNextCourse = $this->getNextCourse($shortnamecourse, $matriz);
      if($shortnameNextCourse){
        $timecreated = time();
        $sql = "INSERT INTO enrolments (shortnamecourse, username, shortnamerole, matriz, timecreated)
                VALUES ('{$shortnameNextCourse}', '{$username}', DEFAULT, {$matriz}, {$timecreated})";
        if($this->conn->query($sql)){
          return array('erro' => false, 'description' => 'O aluno pulou para o próximo módulo com sucesso');
        }else{
          return array('erro' => true, 'description' => 'O aluno foi desinscrito do curso anterior porém não foi inscrito no posterior.', 'more' => $this->conn->erro);
        }
      }else{
        return array('erro' => false, 'description' => 'O aluno finalizou o curso, não existe mais módulos para cadastrar o mesmo.');
      }
    }else{
      return array('erro' => true, 'description' => 'O aluno não foi desinscrito do curso anterior e nem inscrito no posterior.', 'more' => $this->conn->erro);
    }
  }

  // This function returns all the students that are not subscribed in any course
  // Essa função verifica apenas os alunos que não estão inscritos em nenhum course no banco do moodle
  // Ou seja, é só para a Pós-Graduação porque os alunos da Pós podem estar inscritos no course no banco do moodle porém não estar inscrito na tabela de enrolments no banco de dados externo
  // Pois os alunos da Pós, ao término do curso, são retirados do banco externo e automaticamente desativado do banco do moodle, porém não é excluído
  public function getStudentsNotSubscribedInAnyCourse(){
    $sql = "SELECT DISTINCT username, lastname, firstname, email
    FROM {$this->table_prefix}user u WHERE id NOT IN
    (SELECT userid FROM {$this->table_prefix}user_enrolments)
    AND u.lastname LIKE '%POS%' AND u.lastname LIKE '%EAD%'";
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
