<?php
require_once("FuncoesPHP/sessao.php");
verificaSessao();
$perfil = pegaPerfil();
?>

<select class="browser-default custom-select mb-4 mt-4 col-sm-2" onchange="loadFaturamento(this.value)">
  <option value="1" selected>Hoje</option>
  <option value="7">7 dias</option>
  <option value="30">30 dias</option>
  <option value="60">60 dias</option>
</select>

<div id="divRetFaturamento">
</div>
<!-- 
<div class="row">
  <div class="col-lg-12">
    <div class="card mb-4 shadow">
      <div class="card-header">
        <span style="font-weight: bold;">Jogos por dia</span>
      </div>
      <div class="card-body">
        <div class="container">
          <canvas id="chLine"></canvas>
        </div>
      </div>
    </div>
  </div>
</div> -->

  <div class="row">
    <div class="col-lg-12">
      <div class="card mb-4">
        <div class="card-header">
          <span style="font-weight: bold;">Visao por funcionario</span>
        </div>
        <div class="card-body">

          <select class="browser-default custom-select mb-4 col-sm-2" onchange="loadFaturamentoSemana(this.value)" id="selectSemanas">
            <option value="0" selected>Semana atual</option>
            <option value="1"></option>
            <option value="2">30 dias</option>
            <option value="3">60 dias</option>
            <option value="4">60 dias</option>
            <option value="5">60 dias</option>
          </select>
          <div id="divPerformanceFuncionario">
          </div>
        </div>
      </div>
    </div>
  </div>

<script src="ModelJS/faturamento.js?random=<?php echo rand(); ?>"></script>
<script src='ComponentesPadrao/Chart.js/chart.js/dist/Chart.min.js'></script>
<script>
  $(document).ready(function() {
    loadFaturamento(1);
    loadFaturamentoSemana(0);
    buscaDatas();
  });
</script>