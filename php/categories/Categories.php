<?php
/**
 * Created on ??/05/2018
 *
 * @category   Categories
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
Class Categories {
  private $id;
  private $name;
  private $table_prefix;
  private $conn;

  public function __construct($conn, $table_prefix){
    $this->conn = $conn;
    $this->table_prefix = $table_prefix;
  }

  // This method only return categories wich has any course
  public function getCategories(){
    $sql = "SELECT DISTINCT cat.id, cat.name, cat.idnumber FROM {$this->table_prefix}course_categories cat
              INNER JOIN {$this->table_prefix}course c ON cat.id = c.category";
    $result = $this->conn->query($sql);
    $categories;
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $categories[] = $row;
      }
      return array("erro" => false, "description" => "Categories found", "categories" => $categories);
    }else{
      return array("erro" => false, "description" => "No categories with course found", "categories" => $categories);
    }
  }

  // Pega todas as categorias que possuem pelo menos um curso
  // Pelo padrão do UGB, essas categorias selecionadas vão ser sempre os períodos
  // Recolhe todos os períodos(categorias)
  public function getPeriodosParents(){
    $sql = "SELECT DISTINCT cat.parent FROM {$this->table_prefix}course_categories cat
            INNER JOIN {$this->table_prefix}course c ON cat.id = c.category";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $periodos[] = $row;
      }
      return array("erro" => false, "description" => "Períodos encontrados.", "periodos" => $periodos);
    }else{
      return array("erro" => true, "description" => "Nenhum período encontrado.", "periodos" => 0);
    }
  }

  // Pega a categoria referente ao curso
  // Por exemplo Sistemas de Informação, Engenharia Civil
  // Ele pega a categoria do curso
  public function getCursosCategories(){
    $responsePeriodos = $this->getPeriodosParents();
    if(!$responsePeriodos['erro']){
      $periodos = $responsePeriodos['periodos'];
      foreach($periodos as $periodo){
        $parent = $periodo['parent'];
        $sql = "SELECT id, name, idnumber FROM {$this->table_prefix}course_categories WHERE id = {$parent}";
        $result = $this->conn->query($sql);
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
          $cursos[] = $row;
        }
      }
      return array("erro" => false, "description" => "Categorias dos cursos foram encontradas.", "cursos" => $cursos);
    }else{
      return array("erro" => true, "description" => "Nenhuma categoria do curso encontrada.", "cursos" => 0);
    }
  }

  // Pega todos os cursos e períodos da categoria do curso que for passada como parâmetro
  public function getCursosAndPeriodosByCategories($id){
    $sql = "SELECT cat.id as categoryid, cat.name as categoryname, c.fullname, c.id as idcourse, c.shortname FROM {$this->table_prefix}course_categories cat
            INNER JOIN {$this->table_prefix}course c ON c.category = cat.id WHERE parent= {$id} ORDER BY cat.id";
    $result = $this->conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $periodos[] = $row;
      }
      return array("erro" => false, "description" => "Períodos e cursos encontrados.", "periodos" => $periodos);
    }else{
      return array("erro" => true, "description" => "Nenhum período e curso foram encontrados.", "periodos" => 0);
    }
  }
}

Class CategoriesGraduacao extends Categories{

}

?>
