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

    <!-- Table where the Grades will be listed -->
    <table class="table" id="table-grades">
      <thead>
        <tr>
          <th scope="col">Nome</th>
          <th scope="col">Shortname</th>
          <th scope="col">Editar</th>
          <th scope="col">Excluir</th>
        </tr>
      </thead>
      <tbody id="content-table-grades">
      </tbody>
    </table>
    <!-- End Table -->

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
    listGrades();
  });
    function listGrades(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../php/grades/Controller.php",
        data: {'action':'getGrades'},
        success: function(data) {
          if(!data.erro){
            size = data.grades.length;
            for(i = 0; i < size; i++){
              tr = document.createElement("tr");
              nome = document.createElement("td");
              editBtn = document.createElement("img");
              deletBtn = document.createElement("button");

              nome.innerHTML = data.grades.nome;
              editBtn.setAttribute("class", "btn btn-p")
            }
          }
          showFeedbackModal("Controle de Matrizes", data.description, "Beleza !", "btn btn-primary", [{name: "data-dismiss", value: "modal"}]);
        },
        error: function(data){
          console.log(data);
        }
      });
    }
  </script>
</html>
