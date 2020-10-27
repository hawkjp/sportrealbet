var div = 'dvRetSeguranca';
var pagina = 'DAO/funcionarioDAO.php';

function cadastrarFuncionario() {

    var erro = false;
    var s = 'Preencha o(s) seguinte(s) Campo(s): <br> ';

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
        txtPorcentagem: $('#txtPorcentagem').val(),
        selGerente: document.getElementById("selGerente") != null ? $('#selGerente').val() : 0,
        hdnCod: $('#hdnCod').val(),
        acao: acao
    }

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
    if (obj.selGerente == null &&  obj.selGerente != 0){
        erro = true;
        s = s + '- Gerente <br>';
    }
    if (obj.txtLimiteDiario < obj.txtLimiteAposta){
        erro = true;
        s = s + '- Limite diario menor que limite de aposta unica <br>';
    }
    if (obj.txtPorcentagem > 30 ){
        erro = true;
        s = s + '- Limite da porcentagem: 30%  <br>';
    }
    if (obj.txtPorcentagem == '' &&  obj.txtPorcentagem < 0 ){
        erro = true;
        s = s + '- Porcentagem <br>';
    }

    if (erro) {
        $('#' + div).html("<div style='font-size:12px' class='alert alert-danger col-sm-3'>" + s + "</div>");
    } else {
        execSeguranca(obj);
    }
}

function alterarFuncionario() {

    var erro = false;
    var s = 'Preencha o(s) seguinte(s) Campo(s): <br> ';

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
        txtPorcentagem: $('#txtPorcentagem').val(),
        selGerente: document.getElementById("selGerente") != null ? $('#selGerente').val() : 0,
        hdnCod: $('#hdnCod').val(),
        acao: acao
    }

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
    if (obj.selGerente == null &&  obj.selGerente != 0){
        erro = true;
        s = s + '- Gerente <br>';
    }
    if (obj.txtLimiteDiario < obj.txtLimiteAposta){
        erro = true;
        s = s + '- Limite diario menor que limite de aposta unica <br>';
    }
    if (obj.txtPorcentagem > 30 ){
        erro = true;
        s = s + '- Limite da porcentagem: 30%  <br>';
    }
    if (obj.txtPorcentagem == '' &&  obj.txtPorcentagem < 0 ){
        erro = true;
        s = s + '- Porcentagem <br>';
    }

    if (erro) {
        $('#' + div).html("<div style='font-size:12px' class='alert alert-danger col-sm-3'>" + s + "</div>");
    } else {
        execSeguranca(obj);
    }
}

function buscaFuncionario(cod_funcionario) {
    var obj = {
        acao: 'BUSCA',
        cod_funcionario: cod_funcionario,
        hdnCod: $('#hdnCod').val()
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
            $('#funcionarioList').html(retorno);
            $('#' + div).html('');
            // if (cod_funcionario == 0) {
            //     populaGridFuncionario(retorno);
            //     populaGerente();
            // } else {
            //     populaCampos(retorno);
            // }

        },
        error: function () {
            $('#' + div).html("Erro ao carregar pagina.");
        }
    });
}

function buscaFuncionarioAlt(cod_funcionario){
    var obj = {
        acao: 'BUSCA_FUNC',
        cod_funcionario: cod_funcionario,
        hdnCod: $('#hdnCod').val()
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
            if (cod_funcionario == 0) {
                alert(retorno);
            } else {
                populaCampos(retorno);
                altB('a');
            }

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

function populaGridFuncionario(json) {
    var dataSet = [];
    if (json != 'null') {
        var obj = JSON.parse(json);
        $.each(obj, function (index, json) {

            let maskMoney = new Intl.NumberFormat("pt-BR", {
                style: "currency",
                currency: "BRL",
                minimumFractionDigits: 2
            });
            let lim_aposta_diarioF = maskMoney.format(json.lim_aposta_diario);
            let lim_aposta_unicaF = maskMoney.format(json.lim_aposta_unica);

            var arrTemp = [json.cod_funcionario,
            json.username,
            json.first_name,
            json.last_name,
            json.nome_praca,
                lim_aposta_diarioF,
                lim_aposta_unicaF
            ];
            dataSet.push(arrTemp);
        });
    }
    var columnDefs = [{
        title: "Id",
        className: "text-left",
        visible: false,
        searchable: false
    }, {
        title: "Usuario",
        className: "text-left"
    }, {
        title: "Nome",
        className: "text-left"
    }, {
        title: "Sobrenome",
        className: "text-left"
    }, {
        title: "Praça",
        className: "text-left"
    }, {
        title: "Limite diário",
        className: "text-left"
    }, {
        title: "Limite por aposta",
        className: "text-left"
    }];

    var myTable;
    myTable = $('#tbFuncionario').DataTable({
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
    $('#tbFuncionario tbody').on('click', 'tr', function () {
        var data = myTable.row(this).data();
        // var cod_funcionario = data[0];
        buscaFuncionario(data[0]);
        $('#hdnCod').val(data[0]);
        //ChamaPg('templates/seguranca/pgCadUsuario.php?cod_usuario=' + cod_usuario, 'divMain');
    });
}

function populaCampos(json) {
    // alert(json);
    var obj = JSON.parse(json);

    $('#txtUsuario').val(obj[0].username).prop("disabled", true);
    $('#txtNome').val(obj[0].first_name).prop("disabled", true);
    $('#txtSobrenome').val(obj[0].last_name).prop("disabled", true);
    $('#txtCpf').val(obj[0].cpf).prop("disabled", true);
    $('#txtPraca').val(obj[0].nome_praca);
    $('#txtTelefone').val(obj[0].telefone);
    $('#txtEmail').val(obj[0].email);
    $('#selGerente').val(obj[0].cod_gerente);
    $('#txtLimDia').val(obj[0].lim_aposta_diario);
    $('#txtLimApo').val(obj[0].lim_aposta_unica);
    $('#txtPorcentagem').val(obj[0].porcentagem);
    
    $('#hdnCod').val(obj[0].cod_funcionario);
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
    $('#txtPorcentagem').val('');

    $('#hdnCod').val('');
}

function altB(func){
    
    if(func == 'i'){
        $('#funcao').html("<div class='col-sm-3 mt-3'><a href='#' onclick='modalCadastrarFuncionario();' class='btn btn-primary btn-user btn-block' style='font-weight: bold;'>Incluir</a></div><div class='col-sm-3 mb-3 mt-3'><a href='#' data-dismiss='modal' class='btn btn-danger btn-user btn-block' style='font-weight: bold;'>Cancelar</a></div>");
    } else {
        $('#funcao').html("<div class='col-sm-3 mt-3'><a href='#' onclick='modalAlterarFuncionario();' class='btn btn-primary btn-user btn-block' style='font-weight: bold;'>Alterar</a></div><div class='col-sm-3 mb-3 mt-3'><a href='#' data-dismiss='modal' class='btn btn-danger btn-user btn-block' style='font-weight: bold;'>Cancelar</a></div>");
    }
}

function modalAlterarFuncionario() {
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
          alterarFuncionario();
      }
    });
}

function modalCadastrarFuncionario() {
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
          cadastrarFuncionario();
      }
    });
}

$('#btnCadastrarUsuario').click(function () {
    cadastrarUsuario();
});

$('#btnAlteraSenha').click(function () {
    alterarSenha();
});