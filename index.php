<?php
require_once("FuncoesPHP/sessao.php");
verificaSessao();
$perfil = pegaPerfil();

$corBnt = "";
if ($perfil >= 3) {
  $corBnt = "btn-secondary";
} else {
  $corBnt = "btn-success";
}

$cor = "";
switch ($perfil) {
  case 3:
    $cor = "bg-funcionario";
    break;
  case 4:
    $cor = "bg-manager";
    break;
  case 5:
    $cor = "bg-admin";
    break;
  default:
    $cor = "bg-default";
    break;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

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
  <link href="css/index.css" rel="stylesheet" type="text/css">
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

  <!-- |INÍCIO| - Sweetalert -->
  <script src="ComponentesPadrao/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <!-- |FIM| - Sweetalert -->

  <!-- |INÍCIO| - api -->
  <script src="ModelJS/api.js"></script>
  <!-- |FIM| - api -->

  <script type="text/javascript" language="javascript" src="js/html2canvas.min.js"></script>
  <script type="text/javascript" language="javascript" src="js/html2canvas.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper" class="min-vh-100">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light <?php echo $cor ?> topbar fixed-top shadow">

          <div class="container-fluid">
              <a class="navbar-brand text-left" href="#">
                <img src="img/logo.png" style="height:50px" alt="SRB Logo">
              </a>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Nav Item -->
              <!-- <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle">
                  <input type="text" name="amountNav" class="form-control border border-dark rounded .money" onchange="calculaRetorno()" id="amountNav" placeholder="Valor da aposta" required="">
                </a>
              </li> -->

              <!-- Nav Item - Cart -->
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" data-toggle="modal" data-target="#cartModal" id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-shopping-cart fa-fw"></i>
                  <!-- Counter - Messages -->
                  <span class="badge badge-danger badge-counter" id="cartQty">0</span>
                </a>
              </li>

              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- Nav Item - User Information -->

              <?php if ($perfil != 0) {  ?>
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="" id="userDropdown" role="button" data-toggle="modal" data-target="#logoutModal" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-dark small">
                      <h6><?php pegaUsername(); ?></h6>
                    </span>
                    <i class="fa fa-user-circle"></i>
                  </a>
                </li>
              <?php } else {  ?>
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="page-login.php" id="userDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-dark small">
                      <h6>Login</h6>
                    </span>
                    <i class="fas fa-sign-in-alt fas-fw"></i>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </div>

        </nav>
        <!-- End of Topbar -->

        <!-- |INÍCIO| - Include do Menu -->
        <?php include 'Menu.php'; ?>
        <!-- |FIM| - Include do Menu -->


        <!-- |INÍCIO| - Include do Menu -->
        <?php include 'ExpressCart.php'; ?>
        <!-- |FIM| - Include do Menu -->

        <!-- |INÍCIO| - Conteúdo da página -->
        <div class="container-fluid bg-gray-900" style="margin-top: 190px;">
          <div id="divConteudoPagina">
          </div>
        </div>
        <!-- |INÍCIO| - Conteúdo da página -->

        <!-- |INÍCIO| - Rodapé -->
        <?php include 'Rodape.php'; ?>
        <!-- |FIM| - Rodapé -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Fazer logout?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Clique em logout para encerrar a sessao.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="DAO/logoutDAO.php">Logout</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<!-- Bootstrap core JavaScript-->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script>
  $(document).ready(function() {
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#myList li").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
    <?php
    if ($perfil == 3 || $perfil == 4) {
      echo "AlterarConteudoPagina('AprovarTicket.php');";
    } else {
      if ($perfil == 5) {
        echo "AlterarConteudoPagina('PainelADM.php');";
      } else {
        echo "AlterarConteudoPagina('JogosDia.php');";
      }
    }
    ?>
    //setInterval(function(){ executarRoboAPI(); }, 1000*60);
  });
</script>