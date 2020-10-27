<?php
require_once("FuncoesPHP/sessao.php");
verificaSessao();
$perfil = pegaPerfil();

?>
<div class="row">
  <div class="col-lg-12">
    <div class="card mb-4 shadow">
      <div class="card-header">
        <span style="font-weight: bold;">Funcionários</span>
      </div>
      <div class="card-body">
        <div class="col-lg-12">
          <div class="p-0">
            <form name="frmCadUsuario" id="frmCadUsuario" method="post" class="form-horizontal">
              <input type='hidden' name='hdnCod' id='hdnCod' value='0'>
              <div class="form-row">
                <div class="col-sm-3">
                  Usuário:<span class="text-danger">*</span>
                  <input type="text" name="txtUsuario" id="txtUsuario" class="form-control input-sm">
                </div>
                <div class="col-sm-3">
                  Nome:<span class="text-danger">*</span>
                  <input type="text" name="txtNome" id="txtNome" class="form-control input-sm">
                </div>
                <div class="col-sm-3">
                  Sobrenome:<span class="text-danger">*</span>
                  <input type="text" name="txtSobrenome" id="txtSobrenome" class="form-control input-sm">
                </div>
                <div class="col-sm-3">
                  CPF:<span class="text-danger">*</span>
                  <input type="text" name="txtCpf" id="txtCpf" class="form-control input-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-3">
                  Praça:<span class="text-danger">*</span>
                  <input type="text" name="txtPraca" id="txtPraca" class="form-control input-sm">
                </div>
                <div class="col-sm-3">
                  Telefone:<span class="text-danger">*</span>
                  <input type="text" name="txtTelefone" id="txtTelefone" class="form-control input-sm">
                </div>
                <div class="col-sm-3">
                  Email:<span class="text-danger">*</span>
                  <input type="text" name="txtEmail" id="txtEmail" class="form-control input-sm">
                </div>
              </div>
              <div class="form-row mt-4">
                <div class="col-sm-3">
                  Limite diário(R$):<span class="text-danger">*</span>
                  <input type="text" name="txtLimDia" id="txtLimDia" class="form-control input-sm">
                </div>
                <div class="col-sm-3">
                  Limite por aposta(R$):<span class="text-danger">*</span>
                  <input type="text" name="txtLimApo" id="txtLimApo" class="form-control input-sm">
                </div>
              </div>
              <div class="form-row mb-sm-0">
                <div class="col-sm-2 mt-3">
                  <a href="#" onclick="modalCadastrarFuncionario();" class="btn btn-primary btn-user btn-block" style="font-weight: bold;">
                    Cadastrar
                  </a>
                </div>
                <div class="col-sm-2 mb-3 mt-3">
                  <a href="#" onclick="limpaCampos();" class="btn btn-danger btn-user btn-block" style="font-weight: bold;">
                    Limpar
                  </a>
                </div>
              </div>
            </form>
          </div>
          <hr>

          <div class="row">
            <div class="col-lg-12">
              <form name="frmPesqUsuario" id="frmPesqUsuario" method="post" class="form-horizontal">
                <div align="center">
                  <div id="dvRetSeguranca"></div>
                </div>
                <table id="tbFuncionario" cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped">
                </table>
              </form>
            </div>
          </div>
          <hr>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="ModelJS/funcionario.js"></script>
<script>
  $(document).ready(function() {
    buscaFuncionario(0);
  });
</script>