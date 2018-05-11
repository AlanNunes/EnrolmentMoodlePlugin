<?php
require_once("../php/database/DataBase.php");
require_once("../php/categories/Categories.php");

// Instance of database
$db = new DataBase("moodle");
$conn = $db->getConnection();

// Instance of Categories
$categories = new Categories($conn);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cadastro de Matriz - Pós-EaD</title>
    <style>
      body {
        margin: 10px;
      }
    </style>
  </head>
  <body>
  <!-- <div class="col-lg-1 col-offset-6 centered"> -->
  <!-- <div class="container">
    <div class="row justify-content-md-center"> -->
      <form>
        <div class="form-row justify-content-center">
          <div class="form-group col-md-6">
              <label for="nome-matriz">Nome da Matriz:</label>
              <input type="text" class="form-control" id="nome-matriz" placeholder="Ex.: ENG PROD 2018.1">
          </div>
        </div>
        <div class="form-row justify-content-center">
          <div class="form-group col-md-6">
            <label for="cursos">Cursos:</label>
            <select id="cursos" class="form-control" onchange=getCoursesByCategory(this.value)>
              <option value="-1">(Selecione)</option>
              <?php
                $categoriesArray = $categories->getCategories();
                if(!$categoriesArray["erro"]){
                  $categoriesArray = $categoriesArray["categories"];
                  $size = sizeof($categoriesArray);
                  $i = 0;
                  for($i; $i < $size; $i++){
                    $id = $categoriesArray[$i]["id"];
                    $name = $categoriesArray[$i]["name"];
                    echo "<option value='{$id}'>{$name}</option>";
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-row justify-content-center">
          <div class="form-group col-md-6">
            <div class="cursos">
              <label for="cursos">Ordem dos módulos:</label>
              <div id="lista_de_cursos">
              </div>
            </div>
          </div>
        </div>
        <div class="form-row justify-content-center">
          <div class="form-group col-md-6">
            <button type="button" class="btn btn-primary btn-block">Pronto !</button>
          </div>
        </div>
      </form>
    <!-- </div>
  </div> -->
  <!-- </div> -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
  <script>
  $(document).ready(function(){

  });
  function getCoursesByCategory(id){
    document.getElementById("lista_de_cursos").innerHTML = '';
    var data = {
      "action": "getCoursesByCategory",
      "idCategory": id
    };
    data = $(this).serialize() + "&" + $.param(data);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../php/courses/Controller.php",
        data: data,
        success: function(data) {
          console.log(data);
          if(!data.erro){
            courses = data.courses;
            console.log(courses);
            size = courses.length;
            for(i = 0; i < size; i++){
              item = document.createElement("div");
              item.setAttribute("class", "item");
              item.setAttribute("ondrop", "drop(event)");
              item.setAttribute("ondragover", "allowDrop(event)");
              item.setAttribute("ondragstart", "drag(event)");
              item.setAttribute("draggable", "true");
              item.setAttribute("id", courses[i].shortname);
              nome = document.createTextNode(courses[i].fullname);
              item.appendChild(nome);
              item.data= courses[i].shortname;
              document.getElementById("lista_de_cursos").appendChild(item);
            }
          }
        },
        error: function(data){
          console.log('erro:');
          console.log(data);
        }
      });
  }

  // Método que retorna a ordem dos cursos com o shortname
  function getOrdemCursos(){
    cursos = document.getElementById("lista_de_cursos").childNodes;
    size = cursos.length;
    ordem = [size];
    for(i = 0; i < size; i++){
      ordem[i] = cursos[i].id;
    }
    return ordem;
  }

  // Método que retorna todos os dados do formulário
  function getFormValues(){
    var ordem = getOrdemCursos();
    var campos = {
      "nome-matriz":$("#nome-matriz").val(),
      "curso":$("#cursos").val()
    };
    var data = {
      "campos":campos,
      "ordem-matriz":ordem
    };

    return data;
  }

  // Função responsável por cadastrar uma nova matriz
  function cadastrarMatriz(){
    var data = getFormValues();
    data = $(this).serialize() + "&" + $.param(data);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../php/grades/Controller.php",
        data: 'action':'cadastrarMatriz', data,
        success: function(data) {
          console.log(data);
          if(data.erro){

          }else{
            console.log(data);
          }
        },
        error: function(data){
          console.log('erro: '+data);
        }
      });
  }

  // Funções para mover cursos de uma div à outra, drag and drop
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("id", ev.target.id);
    }

    function drop(ev) {
      event.preventDefault();
      var drop_target = event.target;
      var drag_target_id = ev.dataTransfer.getData('id');
      var drag_target = $("#"+drag_target_id)[0];
      var tmp = document.createElement('span');
      tmp.className='hide';
      drop_target.before(tmp);
      drag_target.before(drop_target);
      tmp.replaceWith(drag_target);
    }
  </script>
</html>
