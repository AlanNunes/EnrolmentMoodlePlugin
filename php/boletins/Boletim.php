<?php
/**
 * Created on 10/07/2018
 *
 * @category   Boletim
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Boletim {
  public $username;
  public $courseshortname;
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  /**
  * Retorna a média em um course(disciplina)
  *
  * @return float
  */
  public function getMedia()
  {
    $sql = "SELECT
            AVG(g.finalgrade) as mediafinal
            FROM mdl_user u
            JOIN mdl_grade_grades g ON g.userid = u.id
            JOIN mdl_grade_items gi ON g.itemid =  gi.id
            JOIN mdl_course c ON c.id = gi.courseid
            WHERE c.shortname = '{$this->courseshortname}'
            AND u.username = '{$this->username}'
            AND gi.itemname IS NOT NULL";
    $result = $this->conn->query($sql);
    if ($result->num_rows)
    {
      $boletim = $result->fetch_assoc();
      return $boletim['mediafinal'];
    }
    return 0;
  }
}
?>
