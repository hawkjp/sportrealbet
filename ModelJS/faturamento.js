var div = 'divRetFaturamento';
var pagina = 'DAO/faturamentoDAO.php';

var amount = $('#amount').val();

function loadFaturamento($dias) {

    var obj = {
        acao: 'BUSCA',
        dias: $dias
    }
    execSeguranca(obj, div);
}

function loadFaturamentoSemana($semanas) {

    var obj = {
        acao: 'BUSCA_GERENTE',
        semanas: $semanas
    }
    execSeguranca(obj, 'divPerformanceFuncionario');
}

function loadFaturamentoGerente() {

    var obj = {
        acao: 'BUSCA_FUNCIONARIO',
    }
    execSeguranca(obj, 'divPerformanceFuncionario');
}

function loadFaturamentoPraca() {

    var obj = {
        acao: 'BUSCA_PRACA',
        semanas: 0
    }
    execSeguranca(obj);
    populaGridRelatorioPraca();
}

function buscaDatas() {

    var obj = {
        acao: 'BUSCA_DATAS'
    }
    execSeguranca(obj, 'selectSemanas');
    
}

function teste() {

    var obj = {
        acao: 'TESTE',
        semanas: 1
    }
    execSeguranca(obj, 'divPerformanceFuncionario');
}

function execSeguranca(obj, divAlt) {
    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function() {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + divAlt).html(msg);
        },
        success: function(retorno) {
            // alert(retorno);
            $('#' + divAlt).html('');
            $('#' + divAlt).html(retorno);
        },
        error: function() {
            $('#' + divAlt).html("Erro ao carregar pagina.");
        },
        complete: function() {
            $('#amount').val(amount);
        }
    });
}

function grafico(){
    var chartData = {
        labels: ["S", "M", "T", "W", "T", "F", "S"],
        datasets: [{
          data: [589, 445, 483, 503, 689, 692, 634],
        },
        {
          data: [639, 465, 493, 478, 589, 632, 674],
        }]
      };
    var chLine = document.getElementById("chLine");
    if (chLine) {
      new Chart(chLine, {
      type: 'line',
      data: chartData,
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: false
            }
          }]
        },
        legend: {
          display: false
        }
      }
      });
    }
}

function populaGridRelatorioPraca() {
    // Gráfico Jogos Realizados
    Highcharts.chart('divGraficoJogosRealizados', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Renda por praça'
        },
        subtitle: {
            text: ''
        },
        accessibility: {
            announceNewData: {
                enabled: true
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y: .f}'
                }
            }
        },
        series: [{
            name: "Jogos realizados",
            colorByPoint: false,
            data: [{
                    color: "#4E73DF",
                    name: "Praça 1",
                    y: 1500.20
                },
                {
                    color: "#4E73DF",
                    name: "Praça 2",
                    y: 1300
                },
                {
                    color: "#4E73DF",
                    name: "Praça 3",
                    y: 1800.96
                },
                {
                    color: "#4E73DF",
                    name: "Praça 4",
                    y: 600
                },
                {
                    color: "#4E73DF",
                    name: "Praça 5",
                    y: 83.22
                }
            ]
        }]
    });
}