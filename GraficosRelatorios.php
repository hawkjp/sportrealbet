<div class="row">            
  <div class="col-lg-12 mt-4">
    <div class="card mb-4">
      <div class="card-header">                  
        <span style="font-weight: bold;"></span>
      </div>
      <div class="card-body">
        <div id="divGraficoJogosRealizados">
        </div>
      </div>
    </div>
  </div>
</div>

<div id="divRetFaturamento">
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header">                  
        <span style="font-weight: bold;"></span>
      </div>
      <div class="card-body">
        <div id="divGraficoCampeonatosComMaisJogosRealizados">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header">                  
        <span style="font-weight: bold;"></span>
      </div>
        <div class="card-body">
          <table id="tableEstatisticasTimes" class="table table-condensed" width="100%">
            <thead>
                <tr align="center">
                  <th>Time</th>
                  <th>Campeonato</th>
                  <th>Vitória(s)</th>
                  <th>Empate(s)</th>
                  <th>Derrota(s)</th>
                  <th>Total de Jogos</th>
                </tr>
            </thead>
            <tbody>
              <tr align="center">
                <td>Paris Saint-Germain</td>
                <td>Francês</td>
                <td>4</td>
                <td>0</td>
                <td>1</td>
                <td>5</td>
              </tr>
              <tr align="center">
                <td>Manchester United</td>
                <td>Inglês</td>
                <td>3</td>
                <td>1</td>
                <td>1</td>
                <td>5</td>
              </tr>
              <tr align="center">
                <td>Real Madrid</td>
                <td>Espanhol</td>
                <td>2</td>
                <td>2</td>
                <td>1</td>
                <td>5</td>
              </tr>
              <tr align="center">
                <td>Liverpool</td>
                <td>Inglês</td>
                <td>2</td>
                <td>1</td>
                <td>2</td>
                <td>5</td>
              </tr>
              <tr align="center">
                <td>Flamengo</td>
                <td>Brasileiro</td>
                <td>4</td>
                <td>1</td>
                <td>0</td>
                <td>5</td>
              </tr>
            </tbody>  
            <tfoot>
                <tr align="center">
                  <th>Time</th>
                  <th>Campeonato</th>
                  <th>Vitória(s)</th>
                  <th>Empate(s)</th>
                  <th>Derrota(s)</th>
                  <th>Total de Jogos</th>
                </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
</div>        

<script src="/ModelJS/faturamento.js?random=<?php echo rand(); ?>"></script>
<script>

$(document).ready(function() {
  loadFaturamentoPraca();
});

// Campeonatos com mais jogos realizados
Highcharts.chart('divGraficoCampeonatosComMaisJogosRealizados', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Campeonatos com mais jogos realizados'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Mais jogos',
        colorByPoint: true,
        data: [{
        		color: '#FFFF00',
            name: 'Brasileiro',
            y: 40.00
        }, {
        		color: '#666666',
            name: 'Inglês',
            y: 10.00
        }, {
        		color: '#CC0000',
            name: 'Espanhol',
            y: 20.00
        }, {
        		color: '#000099',
            name: 'Francês',
            y: 10.00
        }, {
        		color: '#009900',
            name: 'Italiano',
            y: 15.00
        }, {
        		color: '#0099CC',
            name: 'Outros',
            y: 5.00
        }]
    }]
});

// $('.highcharts-credits').remove(); 
removeAllByClassName = function (className) {
  function findToRemove() {
      var sets = document.getElementsByClassName(className);
      if (sets.length > 0) {
          sets[0].remove();
          findToRemove();
      }
  }
  findToRemove();
};

removeAllByClassName('highcharts-credits');

$(document).ready(function(){
    $('#tableEstatisticasTimes').DataTable({
			buttons: [
				 'copyHtml5', 'pdfHtml5', 'csvHtml5', { extend: 'excelHtml5',
						filename: 'EstatisticasTimes'}
			],
      "sDom": 'lftipr',
      "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
      "pageLength": 50,
      "oLanguage": {
        "sInfo": "Página _PAGE_ de _PAGES_.<br />Registros _START_ ao _END_ de _TOTAL_ registros.", // legenda com total de páginas e registros
        "sLengthMenu": "Total de _MENU_ registros por página.", // combo contendo a quantidade de registros
        "sInfoEmpty": "", // legenda para nenhum registro encontrado
        "sInfoFiltered": "Filtro aplicado em _MAX_ registros.", // legenda quando filtro aplicado
        "sZeroRecords": "Nenhum registro encontrado.", // título para quando não encontrar registros
        "sSearch": "Procurar: ", // Legenda campo Search
        "oPaginate": {
          "sPrevious": "Anterior", // legenda para botão Previous
          "sNext": "Próximo" // legenda para botão Next
        }
      }
    });
});
</script>