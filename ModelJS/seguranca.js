var div = 'dvRetSeguranca';
var pagina = 'DAO/segurancaDAO.php';

function cadastrarUsuario() {

    var erro = false;
    var s = '';

    if ($('#hdnCod').val() == 0) {
        acao = 'CAD';
    } else {
        acao = 'ALTERAR'
    }

    var obj = {
        txtUsuario: $('#txtUsuario').val(),
        txtNome: $('#txtNome').val(),
        txtSobrenome: $('#txtSobrenome').val(),
        hdnCod: $('#hdnCod').val(),
        rdPerfil: document.frmCadUsuario.rdPerfil.value,
        acao: acao
    }

    if (obj.rdPerfil == '') { erro = true; s = s + '- Perfil <br>'; }
    if (obj.txtUsuario == '') { erro = true; s = s + '- Usuario <br>'; }
    if (obj.txtNome == '') { erro = true; s = s + '- Nome <br>'; }
    if (obj.txtSobrenome == '') { erro = true; s = s + '- Sobrenome <br>'; }




    if (erro) {
        $('#' + div).html("<div style='font-size:12px' class='alert alert-danger col-sm-3'>" + s + "</div>");
    } else {
        execSeguranca(obj);
    }
}

function execSeguranca(obj) {
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
            $('#' + div).html(retorno);
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}


function buscaUsuario(cod_usuario) {
    var obj = {
        acao: 'BUSCA',
        cod_usuario: cod_usuario
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
            if (cod_usuario == 0) {
                populaGridUsuario(retorno);
            } else {
                populaCampos(retorno);
            }

        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function populaCampos(json) {
    var obj = JSON.parse(json);

    $('#txtUsuario').val(obj[0].username);
    $('#txtNome').val(obj[0].first_name);
    $('#txtSobrenome').val(obj[0].last_name);

    if (obj[0].perfil == 1) {
        $('#rdPerfil1').prop("checked", true);
    }
    if (obj[0].perfil == 2) {
        $('#rdPerfil2').prop("checked", true);
    }
    if (obj[0].perfil == 3) {
        $('#rdPerfil3').prop("checked", true);
    }
    if (obj[0].perfil == 4) {
        $('#rdPerfil4').prop("checked", true);
    }
}

function populaGridUsuario(json) {
    var dataSet = [];
    if (json != 'null') {
        var obj = JSON.parse(json);
        $.each(obj, function (index, json) {
            var arrTemp = [json.cod_usuario, json.username, json.first_name, json.last_name];
            dataSet.push(arrTemp);
        });
    }
    var columnDefs = [{
        title: "Código",
        width: "10%",
        className: "text-center"
    }, {
        title: "Usuario",
        className: "text-left"
    }, {
        title: "Nome",
        className: "text-left"
    }, {
        title: "Sobrenome",
        className: "text-left"
    }];

    var myTable;
    myTable = $('#tbUsuario').DataTable({
        "sPaginationType": "full_numbers",
        data: dataSet,
        "AutoWidth": true,
        columns: columnDefs,
        dom: 'frtip',
        select: 'single',
        responsive: true,
        altEditor: true,
        language: {
            lengthMenu: "Display _MENU_ records per page",
            zeroRecords: "Não foram encontrados dados",
            info: "Exibindo _PAGE_ de _PAGES_",
            infoEmpty: "Não foram encontrados dados",
            infoFiltered: "(Filtrado _MAX_ registros)",
            search: "Buscar:",
            paginate: {
                first: "Primeiro",
                last: "Último",
                next: "Próximo",
                previous: "Anterior"
            }
        }
    });
    $('#tbUsuario tbody').on('click', 'tr', function () {
        var data = myTable.row(this).data();
        var cod_usuario = data[0];
        ChamaPg('templates/seguranca/pgCadUsuario.php?cod_usuario=' + cod_usuario, 'divMain');
    });
}

function alterarSenha() {
    var erro = false;
    var s = '';
    var acao = 'ALTERAR_SENHA';
    var obj = {
        txtSenhaAtual: $('#txtSenhaAtual').val(),
        txtSenhaNova: $('#txtSenhaNova').val(),
        acao: acao
    }

    if (obj.txtSenhaAtual == '') { erro = true; s = s + '- Senha Atual <br>'; }
    if (obj.txtSenhaNova == '') { erro = true; s = s + '- Nova Senha <br>'; }

    if (erro) {
        $('#' + div).html("<div style='font-size:12px' class='alert alert-danger col-sm-3'>" + s + "</div>");
    } else {
        execSeguranca(obj);
    }
}

$('#btnCadastrarUsuario').click(function () {
    cadastrarUsuario();
});

$('#btnAlteraSenha').click(function () {
    alterarSenha();
});


