var div = 'dvRetSeguranca';
var pagina = 'DAO/gerenteDAO.php';

function validaCampos(obj) {
    
    var erro = false;
    var s = 'Preencha o(s) seguinte(s) Campo(s): <br> ';

    if (obj.txtUsuario == '') {
        erro = true;
        s = s + '- Usuario <br>';
    }
    if (obj.txtNome == '') {
        erro = true;
        s = s + '- Nome <br>';
    }
    if (obj.txtSobrenome == '') {
        erro = true;
        s = s + '- Sobrenome <br>';
    }
    if (obj.txtCpf == '') {
        erro = true;
        s = s + '- CPF <br>';
    }
    if (obj.txtPraca == '') {
        erro = true;
        s = s + '- Praça <br>';
    }
    if (obj.txtTelefone == '') {
        erro = true;
        s = s + '- Telefone <br>';
    }
    if (obj.txtEmail == '') {
        erro = true;
        s = s + '- Email <br>';
    }
    if (obj.txtLimiteDiario == '') {
        erro = true;
        s = s + '- Limite diário <br>';
    }
    if (obj.txtLimiteAposta == '') {
        erro = true;
        s = s + '- Limite por aposta <br>';
    }
    if (obj.txtLimiteAposta == '') {
        erro = true;
        s = s + '- Limite por aposta <br>';
    }

    if (erro) {
        $('#' + div).html("<div style='font-size:12px' class='alert alert-danger col-sm-3'>" + s + "</div>");
    } else {
        return true;
    }
}

function cadastrarGerente() {

    acao = 'CAD';
    var obj = {
        txtUsuario: $('#txtUsuario').val(),
        txtNome: $('#txtNome').val(),
        txtSobrenome: $('#txtSobrenome').val(),
        txtCpf: $('#txtCpf').val(),
        txtPraca: $('#txtPraca').val(),
        txtTelefone: $('#txtTelefone').val(),
        txtEmail: $('#txtEmail').val(),
        txtLimiteDiario: $('#txtLimDia').val(),
        txtLimiteAposta: $('#txtLimApo').val(),
        cod_gerente: $('#hdnCod').val(),
        acao: acao
    }

    if(validaCampos(obj)){
        execSeguranca(obj);
    }

}

function alterarGerente() {

    acao = 'ALTERAR'
    var obj = {
        txtUsuario: $('#txtUsuario').val(),
        txtNome: $('#txtNome').val(),
        txtSobrenome: $('#txtSobrenome').val(),
        txtCpf: $('#txtCpf').val(),
        txtPraca: $('#txtPraca').val(),
        txtTelefone: $('#txtTelefone').val(),
        txtEmail: $('#txtEmail').val(),
        txtLimiteDiario: $('#txtLimDia').val(),
        txtLimiteAposta: $('#txtLimApo').val(),
        hdnCod: $('#hdnCod').val(),
        acao: acao
    }

    if(validaCampos(obj)){
        execSeguranca(obj);
    }

}

function buscaGerente(cod_gerente) {
    var obj = {
        acao: 'BUSCA',
        cod_gerente: cod_gerente
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
            if (cod_gerente == 0) {
                alert(retorno);
            } else {
                populaCampos(retorno);
                altB('a');
                $('#hdnCod').val('1');
            }

        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function buscaGerentes() {
    var obj = {
        acao: 'BUSCA_LISTA'
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
            $('#managerList').html(retorno);
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function populaGerente() {

    var obj = {
        acao: 'BUSCA_GERENTES'
    }

    var $dropdown = $("#selGerente");

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
            // $('#' + div).html(retorno);
            $('#' + div).html('');
            if(retorno != 0){
                if (retorno != 'null') {
                    var obj = JSON.parse(retorno);
                    $.each(obj, function (index, json) {
                        $dropdown.append($("<option />").val(json.cod_usuario).text(json.username));
                    });
                }
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
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

function showManagerTree(cod_gerente,divRet) {
    var obj = {
        acao: 'BUSCA_FUNC',
        cod_gerente: cod_gerente
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#' + divRet).html(msg);
        },
        success: function (retorno) {
            $('#' + divRet).html('');
            if (retorno == 0) {
                alert("<div class='alert alert-danger'>Gerente nao tem funcionarios!</div>");
            } else {
                $('#' + divRet).html(retorno);
                $('#t' + divRet).prop("onclick", null);
            }
        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function modalAlterarGerente() {
    Swal.fire({
      title: 'Deseja alterar o cadastro?',
      text: "",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Confirmar'
    }).then((result) => {
      if (result.value) {
        alterarGerente();
      }
    });
}

function modalCadastrarGerente() {
    Swal.fire({
      title: 'Deseja realizar o cadastro?',
      text: "",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Confirmar'
    }).then((result) => {
      if (result.value) {
        cadastrarGerente();
      }
    });
}

function populaCampos(json) {
    var obj = JSON.parse(json);

    $('#txtUsuario').val(obj[0].username);
    $('#txtNome').val(obj[0].first_name);
    $('#txtSobrenome').val(obj[0].last_name);
    $('#txtCpf').val(obj[0].cpf);
    $('#txtPraca').val(obj[0].nome_praca);
    $('#txtTelefone').val(obj[0].telefone);
    $('#txtEmail').val(obj[0].email);
    $('#txtLimDia').val(obj[0].lim_aposta_diario);
    $('#txtLimApo').val(obj[0].lim_aposta_unica);

    $('#hdnCod').val(obj[0].cod_gerente);
    $('#funcionarioModal').modal();
}

function limpaCampos() {

    $('#txtUsuario').val('').prop("disabled", false);
    $('#txtNome').val('').prop("disabled", false);
    $('#txtSobrenome').val('').prop("disabled", false);
    $('#txtCpf').val('').prop("disabled", false);
    $('#txtPraca').val('');
    $('#txtTelefone').val('');
    $('#txtEmail').val('');
    $('#selGerente').val('');
    $('#txtLimDia').val('');
    $('#txtLimApo').val('');

    $('#hdnCod').val('');
}


function altB(func){
    
    if(func == 'i'){
        $('#funcao').html("<div class='col-sm-3 mt-3'><a href='#' onclick='modalCadastrarGerente();' class='btn btn-primary btn-user btn-block' style='font-weight: bold;'>Incluir</a></div><div class='col-sm-3 mb-3 mt-3'><a href='#' data-dismiss='modal' class='btn btn-danger btn-user btn-block' style='font-weight: bold;'>Cancelar</a></div>");
    } else {
        $('#funcao').html("<div class='col-sm-3 mt-3'><a href='#' onclick='modalAlterarGerente();' class='btn btn-primary btn-user btn-block' style='font-weight: bold;'>Alterar</a></div><div class='col-sm-3 mb-3 mt-3'><a href='#' data-dismiss='modal' class='btn btn-danger btn-user btn-block' style='font-weight: bold;'>Cancelar</a></div>");
    }
    
}
$('#btnCadastrarUsuario').click(function () {
    cadastrarUsuario();
});

$('#btnAlteraSenha').click(function () {
    alterarSenha();
});