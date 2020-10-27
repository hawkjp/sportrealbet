<?php //echo hash("ripemd160","1")
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <link rel="icon" href="img/soccer.png">
  <title>SPORT REAL BET</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- |INÍCIO| - jQuery -->
  <script src="ComponentesPadrao/jQuery3.4.1/jquery-3.4.1.min.js"></script>
  <!-- |FIM| - jQuery -->

  <!-- |INÍCIO| - Bootstrap -->
  <link href="ComponentesPadrao/bootstrap-4.4.1/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <script src="ComponentesPadrao/bootstrap-4.4.1/dist/js/bootstrap.min.js"></script>
  <!-- |FIM| - Bootstrap -->

  <!-- |INÍCIO| - Angular -->
  <script src="ComponentesPadrao/angular-1.7.9/angular.min.js"></script>
  <!-- |FIM| - Angular -->

  <!-- |INÍCIO| - DataTables -->
  <link href="ComponentesPadrao/DataTables/datatables.min.css" rel="stylesheet" type="text/css">
  <script src="ComponentesPadrao/DataTables/datatables.min.js"></script>
  <!-- |FIM| - DataTables -->

  <!-- |INÍCIO| - Highcharts -->
  <script src="ComponentesPadrao/Highcharts-8.0.0/code/highcharts.js"></script>
  <script src="ComponentesPadrao/Highcharts-8.0.0/code/modules/exporting.js"></script>
  <script src="ComponentesPadrao/Highcharts-8.0.0/code/modules/export-data.js"></script>
  <script src="ComponentesPadrao/Highcharts-8.0.0/code/modules/accessibility.js"></script>
  <!-- |FIM| - Highcharts -->

  <!-- |INÍCIO| - JS para realizar o login -->
  <script src="ModelJS/login.js"></script>
  <!-- |FIM| - JS para realizar o login -->

</head>
<style type="text/css">
  body {
    background-image: url("img/fute.jpg");
    background-position:48% 45%;
  }

  .card {
    background-color: rgba(245, 245, 245, 0.9);
  }W

  .card-header,
  .card-footer {
    opacity: 0.9
  }
</style>

<body class="fix-header fix-sidebar">
  <div class="container">
    <div class="col-sm-5 offset-sm-3">
      <div class="card card-login mx-auto mt-5 shadow">
        <div class="card-header" align="center"><b>Login</b></div>
        <div class="card-body">
          <form action="app/main.php" name="frmLogin" id="frmLogin" method="post">
            <div class="form-group">
              <div class="form-label-group">
                <label for="inputEmail"><b>Usuário</b></label>
                <input type="text" class="form-control" placeholder="Usuário" name="txtUsuario" id="txtUsuario">
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <label for="inputPassword"><b>Senha</b></label>
                <input type="password" class="form-control" placeholder="Senha" name="txtSenha" id="txtSenha">
              </div>
            </div>
            <button type="button" id="btnSignIn" class="btn btn-success btn-flat btn-block m-b-30 m-t-30">Sign in</button>
          </form>
        </div>
        <div id="divRetorno"></div>
      </div>
    </div>
  </div>

</body>

</html>

<script type="text/javascript">
  $('#btnSignIn').click(function() {
    logar({
      usuario: $('#txtUsuario').val(),
      senha: $('#txtSenha').val()
    });
  });

  function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58)) return true;
    else {
      if (tecla == 8 || tecla == 0) return true;
      else return false;
    }
  }
</script>