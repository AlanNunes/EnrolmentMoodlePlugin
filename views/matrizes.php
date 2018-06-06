<?php
require_once("../php/database/DataBase.php");
require_once("../php/categories/Categories.php");
require_once("../php/modalidades_de_cursos/Modalidades_de_Cursos.php");

// Instance of Moodle database
$db = new DataBase("moodle");
$connMoodle = $db->getConnection();

// Instance of External database
$db = new DataBase("external_enrolment");
$connExternal = $db->getConnection();

// Instance of Categories
$categories = new Categories($connMoodle);

// Instance of Modalidades de Cursos
$modalidades = new Modalidades_de_Cursos($connExternal);
?>
<!doctype html>
<html lang="pt">
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
              <label for="nomeMatriz">Nome da Matriz:</label>
              <input type="text" class="form-control" id="nomeMatriz" placeholder="Ex.: Sistemas de Informação (2018)">
          </div>
        </div>
        <div class="form-row justify-content-center">
          <div class="form-group col-md-6">
              <label for="modalidade">Modalidade:</label>
              <select class="form-control" id="modalidade">
                <option value=''>(Selecione)</option>
                <?php
                  $response = $modalidades->getModalidades();
                  if($response['modalidades']){
                    foreach ($response['modalidades'] as $modalidade) {
                      $id = $modalidade['id'];
                      $nome = $modalidade['nome'];
                      echo "<option value='{$id}'>{$nome}</option>";
                    }
                  }
                 ?>
              </select>
          </div>
        </div>
        <div class="form-row justify-content-center">
          <div class="form-group col-md-6">
            <label for="cursos">Cursos:</label>
            <select id="cursos" class="form-control" onchange=getCursosAndPeriodosByCategories(this.value)>
              <option value=''>(Selecione)</option>
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
            <button type="button" class="btn btn-primary btn-block" onclick="cadastrarMatriz()">Pronto !</button>
          </div>
        </div>
      </form>
    <!-- </div>
  </div> -->
  <!-- </div> -->

  <!-- Modals of Feedbacks -->

  <div class="modal fade" id="modal-feedback" tabindex="-1" role="dialog" aria-labelledby="title-modal-feedback" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title-modal-feedback"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="content-modal-feedback">
        </div>
        <div class="modal-footer" id="footer-modal-feedback">
        </div>
      </div>
    </div>
  </div>

  <!-- End Modals of Feedbacks -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
  <script>
  $(document).ready(function(){
    getCursosCategories();
  });

  // Lista todas as categorias de uma determinada modalidade
  function getCursosCategories(){
    document.getElementById("cursos").innerHTML = '';
    var data = {
      "action": "getCursosCategories"
    };
    data = $(this).serialize() + "&" + $.param(data);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../php/categories/Controller.php",
        data: data,
        success: function(data) {
          console.log(data);
          if(!data.erro){
            cursos = data.cursos;
            size = cursos.length;
            for(i = 0; i < size; i++){
              opt = document.createElement('option');
              opt.setAttribute('value', cursos[i].id);
              opt.setAttribute('data-idnumber', cursos[i].idnumber);
              nome = document.createTextNode(cursos[i].name);
              opt.appendChild(nome);
              document.getElementById("cursos").appendChild(opt);
            }
          }
        },
        error: function(data){
          console.log('erro:');
          console.log(data);
        }
      });
  }
  function getCursosAndPeriodosByCategories(id){
    document.getElementById("lista_de_cursos").innerHTML = '';
    var data = {
      "action": "getCursosAndPeriodosByCategories",
      "idCategory": id
    };
    data = $(this).serialize() + "&" + $.param(data);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../php/categories/Controller.php",
        data: data,
        success: function(data) {
          console.log(data);
          if(!data.erro){
            console.log(listPeriodosAndCursos(data.periodos));
            // courses = data.courses;
            // console.log(courses);
            // size = courses.length;
            // for(i = 0; i < size; i++){
            //   item = document.createElement("div");
            //   item.setAttribute("class", "item");
            //   item.setAttribute("ondrop", "drop(event)");
            //   item.setAttribute("ondragover", "allowDrop(event)");
            //   item.setAttribute("ondragstart", "drag(event)");
            //   item.setAttribute("draggable", "true");
            //   item.setAttribute("id", courses[i].shortname);
            //   nome = document.createTextNode(courses[i].fullname);
            //   item.appendChild(nome);
            //   item.data= courses[i].shortname;
            //   document.getElementById("lista_de_cursos").appendChild(item);
            // }
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
    // Pega todas as divs de Períodos, dentro delas estão os courses - disciplinas
    periodos = document.getElementById("lista_de_cursos").childNodes;
    periodosSize = periodos.length;
    cursosOrdem = new Array();
    for( i = 0; i < periodosSize; i++ ){
      cursos = periodos[i].childNodes;
      cursosSize = cursos.length;
      cursosOrdem[i] = new Array();
      // O j começa no 1 porque sempre tem uma label na posição 1, é padrão
      for( j = 1; j < cursosSize; j++ ){
        cursosOrdem[i].push(cursos[j].data);
      }
    }
    return cursosOrdem;
  }

  // Método que retorna todos os dados do formulário
  function getFormValues(){
    idNumber = document.getElementById('cursos').item(document.getElementById('cursos').selectedIndex).dataset.idnumber;
    var data = {
      "nomeMatriz":$("#nomeMatriz").val(),
      "modalidade":$("#modalidade").val(),
      "cursos":getOrdemCursos(),
      "categoryIdNumber":idNumber
    };

    return data;
  }

  // Função responsável por cadastrar uma nova matriz
  function cadastrarMatriz(){
    var data = getFormValues();
    // data = $(this).serialize() + "&" + $.param(data);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../php/grades/Controller.php",
        data: {'action':'registerGrade', "data":data},
        success: function(data) {
          $("input").removeClass("is-invalid");
          $("input").addClass("is-valid");
          $("select").removeClass("is-invalid");
          $("select").addClass("is-valid");
          if(data.erro){
            console.log(data);
            if(data.invalidFields){
              for(i = 0; i < data.invalidFields.length; i++){
                $("#"+data.invalidFields[i]).removeClass("is-valid");
                $("#"+data.invalidFields[i]).addClass("is-invalid");
              }
            }
          }else{
            console.log(data);
          }
          showFeedbackModal("Cadastro de Matrizes", data.description, "Beleza !", "btn btn-primary", [{name: "data-dismiss", value: "modal"}]);
        },
        error: function(data){
          console.log(data);
        }
      });
  }

    // Function that shows a feedback to the user, inner a modal layout
      function showFeedbackModal(titulo, conteudo, botaoConteudo, botaoClass, botaoAtributos){
        $("#title-modal-feedback").html(titulo);
        $("#content-modal-feedback").html(conteudo);
        btn = document.createElement("button");
        btn.innerHTML = botaoConteudo;
        if(botaoClass) btn.className = botaoClass;
        for(i = 0; i < botaoAtributos.length; i++){
          botaoAtributos = botaoAtributos || [];
          console.log(botaoAtributos[i].name);
          btn.setAttribute(botaoAtributos[i].name, botaoAtributos[i].value);
        }
        $("#footer-modal-feedback").html(btn);
        $("#modal-feedback").modal('show');
      }
    // Fim Function

    function listPeriodosAndCursos(periodos){
      size = periodos.length;
      div = document.createElement('div');
      label = document.createElement('label');
      label.setAttribute('for', periodos[0].shortname);
      texto = document.createTextNode(periodos[0].categoryname);
      label.appendChild(texto);
      div.appendChild(label);
      for(i = 0; i < size; i++){
        try{
          item = document.createElement("div");
          item.setAttribute("class", "item");
          item.setAttribute("ondrop", "drop(event)");
          item.setAttribute("ondragover", "allowDrop(event)");
          item.setAttribute("ondragstart", "drag(event)");
          item.setAttribute("draggable", "true");
          item.setAttribute("id", periodos[i].idcourse);
          nome = document.createTextNode(periodos[i].fullname);
          item.appendChild(nome);
          item.data= periodos[i].shortname;
          div.appendChild(item);
          if(periodos[i].categoryid == periodos[i+1].categoryid){
            console.log('IF');
          }else{
            console.log('ELSE: '+periodos[i].fullname);
            div = document.createElement('div');
            label = document.createElement('label');
            label.setAttribute('for', periodos[i+1].shortname);
            texto = document.createTextNode(periodos[i+1].categoryname);
            label.appendChild(texto);
            div.appendChild(label);
          }
          document.getElementById('lista_de_cursos').appendChild(div);
        }catch(e){
          console.log(e);
        }
      }
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
