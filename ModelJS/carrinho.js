var divCarrinho = 'dvRetCarrinho';
var paginaCarrinho = 'DAO/carrinhoDAO.php';

var amount = $('#amount').val();

function incluirCarrinho(resultado, idPartida) {

    var obj = {
        acao: 'INCLUIR',
        id_partida: idPartida,
        resultado: resultado
    }
    execSegurancaCarrinho(obj);

    if ($('#' + resultado + idPartida).hasClass('highlight')) {
        $("div[id$='" + idPartida + "']").removeClass('highlight');
    } else {
        $("div[id$='" + idPartida + "']").removeClass('highlight');
        $('#' + resultado + idPartida).toggleClass('highlight');
    }
}

function buscaCarrinho() {

    $("#odds-temp-addon").html("1.00x");
    $("#amountCart").html("");
    var obj = {
        acao: 'BUSCA'
    }
    execSegurancaCarrinho(obj);

}

function limparCarrinho() {

    var obj = {
        acao: 'LIMPAR'
    }
    execSegurancaCarrinho(obj);
    $("#odds-temp-addon").html("1.00x");
    $("#amountCart").html("");
    $(".highlight").removeClass('highlight');
}

function execSegurancaCarrinho(obj) {
    $.ajax({
        url: paginaCarrinho,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + divCarrinho).html(msg);
        },
        success: function (retorno) {
            $('#' + divCarrinho).html('');
            $('#' + divCarrinho).html(retorno);
            $('#cartQty').html($('#betsQty').text());
        },
        error: function () {
            $('#' + divCarrinho).html("Erro ao carregar pagina.");
        },
        complete: function () {
            if (typeof $('#oddsTemp').html() !== 'undefined') {
                $('#odds-temp-addon').html($('#oddsTemp').html() + 'x');
            }

            $('#amount').val(amount);
            
            if ($('#amountCart').val() != '') {
                calculaRetorno($('#amountCart').val());
                $('#amount').val($('#amountCart').val());
            }
        }
    });
}

async function cadastraTicket() {

    var s = '';
    var erro = false;
    var obj = {
        acao: 'CADASTRAR',
        valor: $('#amount').val(),
        mult: $('#oddsTemp').text(),
        nome: ''
    }

    let valorF = parseFloat($('#amount').val().replace('.', '').replace(',', '.'));
    if (valorF < 5) {
        erro = true;
        s = s + '- Valor mínimo por aposta: R$5,00\n';
    }
    if ($('#hdnCart').val() < 2) {
        erro = true;
        s = s + '- Minimo de 2 jogos por aposta\n';
    }

    if (erro) {
        Swal.fire({
            text: s,
            title: "Atencao",
            icon: "warning",
            confirmButtonClass: "btn-success",
            confirmButtonText: "Ok"
        })
    } else {
        Swal.fire({
            title: 'Nome do cliente:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
        }).then((result) => {
            obj.nome = result.value;
            enviaTicket(obj)
        })
    }
}

function enviaTicket(obj) {

    let div = "dvRetCarrinhoSent"

    $.ajax({
        url: 'DAO/ticketShowDAO.php',
        method: 'post',
        cache: false,
        async: false,
        data: obj,
        beforeSend: function () {
            $('#' + div).html("<div style='font-size:12px' class='alert alert-primary col-sm-3'>Aguarde...</div>");
        },
        success: function (retorno) {
            $('#' + div).html('');
            var obj = JSON.parse(retorno);
            if (obj.codigo == 0) {
                Swal.fire(obj.titulo, "Você realizou um jogo no total de R$ " + $('#amount').val(), "success").then(() => {
                    limparCarrinho();
                    AlterarConteudoPagina('ConsultarTicket.php?ticket=' + obj.ticket);
                })
            } else if (obj.codigo == 1) {
                Swal.fire({
                    text: "Jogo #" + obj.ticket + " aprovado!",
                    title: "Boa sorte!",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Ok',
                    confirmButtonText: 'Baixar ticket'
                }).then((result) => {
                    limparCarrinho();
                    if (result.value) {
                        // alert ("1");
                        baixarTicket(obj.ticket);
                        AlterarConteudoPagina('ConsultarTicket.php?ticket=' + obj.ticket);
                    } else {
                        AlterarConteudoPagina('ConsultarTicket.php?ticket=' + obj.ticket);
                    }
                })
            }
        },
        error: function () {
            $('#' + div).html("Erro ao cadastrar ticket. Tente novamente.");
        }
    });
}

function calculaRetorno(valor) {

    var money = new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    var moneyFormat = new Intl.NumberFormat("pt-BR", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    let odds = parseFloat($('#oddsTemp').text().replace(',', ''));

    // let amount = parseFloat($('#amount').val().replace(',', ''))/100;
    let amount = parseFloat(valor.replace(',', '')) / 100;

    if (isNaN(amount)) {
        $('#possibleIncome').text("R$0,00");
        $('#amount').val('');
        alert('Valor inválido');
    } else {
        if (odds * amount > 30000) {
            $('#possibleIncome').text(money.format(27000));
            $('#tax').text(money.format(3000));
            $('#amount').val(moneyFormat.format(amount));
            $('#expIncome').val(money.format(30000));
            $('#amountCart').val(moneyFormat.format(amount));
            Swal.fire({
                title: "Atenção",
                text: "Não pagamos premios maiores que 30000 reais",
                icon: "warning",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Ok, eu entendo",
                closeOnConfirm: false
            });
        } else {

            $('#possibleIncome').text(money.format(odds * amount));
            $('#tax').text(money.format(odds * amount * 0.9));
            $('#amount').val(moneyFormat.format(amount));
            $('#expIncome').val(money.format(odds * amount));
            $('#amountCart').val(moneyFormat.format(amount));
        }
    }
}

function toggleHighlight() {

    var obj = {
        acao: 'RETORNA'
    }

    $.ajax({
        url: paginaCarrinho,
        method: 'post',
        cache: false,
        data: obj,
        success: function (retorno) {
            var obj = JSON.parse(retorno);
            $.each(obj, function (index, json) {
                $("div[id$='" + index + "']").removeClass('highlight');
                $('#' + json + index).toggleClass('highlight');
            });
        },
        error: function () {
            $('#' + divCarrinho).html("Erro ao carregar pagina.");
        },
    });
}

$('#btnCadastrarCampos').click(function () {
    cadastrarCampos();
});

function baixarTicket(id_ticket) {
    var obj = {
        acao: 'BUSCA',
        id_ticket: id_ticket
    }

    $.ajax({
        url: 'DAO/ticketShowDAO.php',
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            // alert(retorno);
            if (retorno == 0) {
                $('#' + div).html('');
                $("#divAlertContent").html("<div class='alert alert-danger'>Ticket nao existe!</div>");
                document.getElementById('divAlert').removeAttribute("hidden")
            } else {
                $('#' + div).html('');
                $('#' + div).html(retorno);
                document.getElementById('ticket').removeAttribute("hidden")
                DownloadAsImage("printableArea", id_ticket);
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function downloadURI(uri, name) {
    var link = document.createElement("a");

    link.download = name;
    link.href = uri;
    document.body.appendChild(link);
    link.click();
    clearDynamicLink(link);
}

function DownloadAsImage(div, id) {
    var element = $("#" + div)[0];
    html2canvas(element).then(function (canvas) {
        var myImage = canvas.toDataURL();
        downloadURI(myImage, "tck" + id + ".png");
    });
}
