var div = 'dvRetTicket';
var pagina = 'DAO/ticketShowDAO.php';

function buscaTicketId() {
    if(document.getElementById("id_ticket").value == ''){
        Swal.fire({text: "Digite um ticket valido!", title: "Ops!", type: "error" })
    }
    else{
        var ticket = document.getElementById("id_ticket").value;
        buscaTicket(ticket);
    }
}

function buscaTicket(id_ticket) {
    var obj = {
        acao: 'BUSCA',
        id_ticket: id_ticket
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            if (retorno == 0) {
                $('#' + div).html('');
                $("#divAlertContent").html("<div class='alert alert-danger'>Ticket nao existe!</div>");
                document.getElementById('divAlert').removeAttribute("hidden")
            } else {
                $('#' + div).html('');
                $('#' + div).html(retorno);
                document.getElementById('ticket').removeAttribute("hidden")
                $("#ticketId").html("Ticket " + $("#ticketSearch").val())
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function showTicketPendente(id_ticket, divRet) {
    var obj = {
        acao: 'BUSCA',
        id_ticket: id_ticket
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            $('#' + div).html('');
            if (retorno == 0) {
                $("#divAlertContent").html("<div class='alert alert-danger'>Ticket nao existe!</div>");
                document.getElementById('divAlert').removeAttribute("hidden")
            } else {
                retorno += '<div class="container mt-4">' +
                    '<div class="row justify-content-md-center">' +
                    '<div class="col-sm-4 mt-3">' +
                    '<a onclick="aprovaTicketPendente(' + id_ticket + ');" class="btn btn-success btn-user btn-block" style="font-weight: bold;">' +
                    'Aprovar Ticket' +
                    '</a>' +
                    '</div>' +
                    '<div class="col-sm-4 mt-3">' +
                    '<a onclick="cancelaTicketPendente(' + id_ticket + ');" class="btn btn-danger btn-user btn-block" style="font-weight: bold;">' +
                    'Cancelar Ticket' +
                    '</a>' +
                    '</div>' +
                    '</div>'
                $('#' + divRet).html(retorno);
                $('#t' + divRet).prop("onclick", null);
                // $("#ticketId").html("Ticket " + $("#ticketSearch").val())
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function buscaTicketPendente() {
    var obj = {
        acao: 'BUSCA_LISTA_PEND'
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            $('#' + div).html('');
            if(retorno == 0){
                $('#ticketList').html("<div style='font-size:12px' class='alert alert-primary col-sm-3'>Não há tickets pendentes</div>");
            }
            else{
                $('#ticketList').html(retorno);
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function cancelaTicketPendente(id_ticket){
    var obj = {
        acao: 'CANCELAR',
        id_ticket: id_ticket
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            var obj = JSON.parse(retorno);
            if (obj.codigo == 0) {
                Swal.fire({text: "Jogo #" + id_ticket + " cancelado!", icon: "error" }).then(() => {
                    AlterarConteudoPagina('AprovarTicket.php');
                  })
            } 
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function aprovaTicketPendente(id_ticket) {
    var obj = {
        acao: 'APROVAR',
        id_ticket: id_ticket,
        atualiza: "false"
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            // alert(retorno);
            var obj = JSON.parse(retorno);
            // alert(obj.codigo);
            // alert(obj.mensagem);

            if (obj.codigo == 0) {
                Swal.fire({text: "Jogo #" + id_ticket + " aprovado!", 
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
                                baixarTicket(id_ticket);
                                AlterarConteudoPagina('AprovarTicket.php');
                            }else{
                                AlterarConteudoPagina('AprovarTicket.php');
                            }
                  })
            } else {
                if (obj.codigo == -1) {
                    Swal.fire({text: obj.mensagem, title: "Ops!", icon: "error" }).then(() => {
                        AlterarConteudoPagina('AprovarTicket.php');
                      })
                } else { 
                    Swal.fire({
                      title: 'Atencao',
                      text: obj.mensagem,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      cancelButtonText: 'Cancelar',
                      confirmButtonText: 'Confirmar'
                    }).then((result) => {
                        if (result.value) {
                            aprovaMudancaMult(id_ticket);
                        }else{
                            cancelaTicketPendente(id_ticket);
                        }
                    })
                }
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function baixarTicket(id_ticket) {
    var obj = {
        acao: 'BUSCA',
        id_ticket: id_ticket
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
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

// function printDiv() 
// {

//   var divToPrint=document.getElementById('dvRetTicket');
//   var newWin=window.open('','Print-Window');

//   newWin.document.open();
//   newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
//   newWin.document.close();

//   setTimeout(function(){newWin.close();},10);

// }

// function printPage(id) {
//     var html="<html>";
//     html+="<head>";
//     html+="<style type='text/css'>#content div.ads, #content img {display:none}    </style>";
//     // html+="<link rel='Stylesheet' type='text/css' href='vendor/fontawesome-free/css/all.min.css' media='print' />";
    
//     html+="<link href='ComponentesPadrao/bootstrap-4.4.1/dist/css/bootstrap.min.css' rel='stylesheet' type='text/css'></link>";
//     html+="</head>";
//     html+= document.getElementById(id).innerHTML;
//     html+="</html>";
    
//     var printWin = window.open('','','left=0,top=0,width=1,height=1,toolbar=0,scrollbars=0,status =0');
//     printWin.document.write(html);
//     printWin.document.close();
//     printWin.focus();
//     printWin.print();
//     printWin.close();
// }

function aprovaMudancaMult(id_ticket) {
    var obj = {
        acao: 'APROVAR',
        id_ticket: id_ticket,
        atualiza: "true"
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + div).html(msg);
        },
        success: function (retorno) {
            // alert(retorno);
            var obj = JSON.parse(retorno);
            // alert(obj.codigo);
            // alert(obj.mensagem);

            if (obj.codigo == 0) {
                Swal.fire({text: "Jogo #" + id_ticket + " aprovado!", title: "Boa sorte!", icon: "success" }).then(() => {
                    AlterarConteudoPagina('AprovarTicket.php');
                  })
            } else {
                Swal.fire({text: obj.mensagem, title: "Ops!", icon: "error" }).then(() => {
                    AlterarConteudoPagina('AprovarTicket.php');
                  })
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}