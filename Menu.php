<!-- class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"-->
<nav class="navbar navbar-expand navbar-light bg-submenu topbar fixed-top shadow" style="top:70px;">
  <div class="container-fluid">
    <a href="#" onclick="AlterarConteudoPagina('JogosDia.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
          <i class="fas fa-calendar-check"></i>
        </span>
        <span class="text-light  text-truncate">Jogos do dia</span>
      </div>
    </a>
    <a href="#" onclick="AlterarConteudoPagina('Futebol.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
          <i class="far fa-futbol"></i>
        </span>
        <span class="text-light  text-truncate">Futebol</span>
      </div>
    </a>
    <!--
    <a href="#" onclick="AlterarConteudoPagina('AoVivo.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
          <i class="fas fa-stopwatch"></i>
        </span>
        <span class="text-light text-truncate">Ao vivo</span>
      </div>
    </a> -->
    
    <a href="#" onclick="AlterarConteudoPagina('ConsultarTicket.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
          <i class="fas fa-search"></i>
        </span>
        <span class="text-light text-truncate">Buscar Ticket</span>
      </div>
    </a>
  <?php if ($perfil >= 3) {  ?>
    <a href="#" onclick="AlterarConteudoPagina('AprovarTicket.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
        <i class="fas fa-cash-register"></i>
        </span>
        <span class="text-light text-truncate">Aprovar Ticket</span>
      </div>
    </a>
    <a href="#" onclick="AlterarConteudoPagina('Faturamento.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
        <i class="fas fa-money-check-alt"></i>
        </span>
        <span class="text-light text-truncate">Faturamento</span>
      </div>
    </a>
    <a href="#" onclick="AlterarConteudoPagina('AlterarSenha.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
          <i class="fas fa-key"></i>
        </span>
        <span class="text-light text-truncate">Alterar senha</span>
      </div>
    </a>

    <?php } if ($perfil >= 4) {  ?>
    
    <a href="#" onclick="AlterarConteudoPagina('GraficosRelatorios.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
        <i class="fas fa-chart-line"></i>
        </span>
        <span class="text-light text-truncate">Relatórios</span>
      </div>
    </a>
    <a href="#" onclick="AlterarConteudoPagina('Funcionario.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
        <i class="fas fa-user"></i>
        </span>
        <span class="text-light text-truncate">Funcionários</span>
      </div>
    </a>

    <?php } if ($perfil >= 5) {  ?>
    
      <a href="#" onclick="AlterarConteudoPagina('PainelADM.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
        <i class="fas fa-user-tie"></i>
        </span>
        <span class="text-light text-truncate">Gerentes</span>
      </div>
    </a>
    
    <?php } ?>

    <a href="#" onclick="AlterarConteudoPagina('Regulamento.php');">
      <div class="d-flex flex-grow-1 flex-column align-items-center p-3">
        <span style="color: Silver;">
          <i class="fa fa-book"></i>
        </span>
        <span class="text-light text-truncate">Regulamento</span>
      </div>
    </a>
  </div>
</nav>
<!-- End of Topbar -->

<script>
  function AlterarConteudoPagina(pagina) {

    var div = 'divConteudoPagina';
    $.ajax({
      type: 'POST',
      url: pagina,
      cache: false,
      success: function(retorno) {
        $('#' + div).html(retorno);
      },
      beforeSend: function() {
        $('#' + div).html('<i class="fa fa-refresh"></i>');
      },
      error: function() {
        $('#' + div).html('');
      }
    });
  }

  var w = window.innerWidth;
  if (w < 765) {
    //$("#accordionSidebar").addClass("toggled");
  }
</script>