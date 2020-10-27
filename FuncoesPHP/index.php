<?php
require_once("/funcoesPHP/sessao.php");
verificaSessao();
$perfil = pegaPerfil();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

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

  <!-- |INÍCIO| - Sweetalert -->
  <link href="ComponentesPadrao/bootstrap-sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css">
  <script src="ComponentesPadrao/bootstrap-sweetalert-master/dist/sweetalert.min.js"></script>
  <!-- |FIM| - Sweetalert -->
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- |INÍCIO| - Include do Menu -->
    <?php include 'Menu.php'; ?>
    <!-- |FIM| - Include do Menu -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form name="frmHome1" id="frmHome1" method="POST" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Ticket" aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <!-- class="btn btn-primary" -->
                <button class="btn btn-secondary" type="button" onclick="buscaTicket('00001')">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form name="frmHome2" id="frmHome2" method="POST" class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">0</span>
              </a>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">0</span>
              </a>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->

            <?php if ($perfil != 0) {  ?>
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="" id="userDropdown" role="button" data-toggle="modal" data-target="#logoutModal" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small">Usuário</span>
                  <i class="fa fa-user-circle"></i>
                </a>
              </li>
            <?php }else {  ?>
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="page-login.php" id="userDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small">Login</span>
                  <i class="fa fa-sign-in"></i>
                </a>
              </li>
            <?php } ?>
          </ul>

        </nav>
        <!-- End of Topbar -->


        <!-- |INÍCIO| - Conteúdo da página -->
        <div class="container-fluid">
          <div id="divConteudoPagina">
          </div>
        </div>
        <!-- |INÍCIO| - Conteúdo da página -->

      </div>
      <!-- End of Main Content -->

      <!-- |INÍCIO| - Rodapé -->
      <?php include 'Rodape.php'; ?>
      <!-- |FIM| - Rodapé -->

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
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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
  });
</script>