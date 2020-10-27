function logar(obj){
    var s = '';
    var erro = false;

    var usuario = obj.usuario;
    var senha = obj.senha;

    if(usuario == ''){s = s + "- Usuario <br>"; erro = true}
    if(senha == ''){s = s + "- Senha <br>"; erro = true}

    if(erro){
        var msg = "<div style='font-size:12px' class='alert alert-danger'>Preencher os campos: <br>"+s+"</div>";
        $('#divRetorno').html(msg);
    }else{
        autenticacao(obj);
    }
}

function autenticacao(obj){
    $.ajax({
        url:"DAO/loginDAO.php",
        method:'post',
        cache:false,
        data:obj,
        beforeSend: function(){
            msg = "<div style='font-size:12px' class='alert alert-success'>Aguarde...</div>";
            $('#divRetorno').html(msg);
        },
        success:function(retorno){
            $('#divRetorno').html(retorno);
        },
        error: function(){
            $('#divRetorno').html("Erro ao carregar pagina.");
        }
    }); 
}
