<?php
require_once("FuncoesPHP/sessao.php");
verificaSessao();
$perfil = pegaPerfil();

?>

<div class="row mt-4">
  <div class="col-lg-12">
    <div class="card mb-4 shadow">
      <div class="card-header">
        <span style="font-weight: bold;">Funcionários</span>
      </div>
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-sm-2">
            <a href="#" data-target="#funcionarioModal" data-toggle="modal" class="btn btn-primary btn-user btn-block" style="font-weight: bold;" onclick="altB('i')">
              Incluir
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 mb-3">
            <input type="text" id="funcionarioFilter" class="form-control" onkeyup="filterFunction()" placeholder="Pesquisar funcionário...">
          </div>
        </div>
        <div class="row" id="funcionarioList">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal funcionario/gerente -->

<div class="modal fade" id="funcionarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="dvRetUsuario">
          <div class="p-0">

            <div align="center">
              <div id="dvRetSeguranca"></div>
            </div>
            <form name="frmCadUsuario" id="frmCadUsuario" method="post" class="form-horizontal">
              <input type='hidden' name='hdnCod' id='hdnCod' value='0'>
              <div class="form-row">
                <div class="col-sm-6">
                  Usuário:<span class="text-danger">*</span>
                  <input type="text" name="txtUsuario" id="txtUsuario" class="form-control input-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-6">
                  Nome:<span class="text-danger">*</span>
                  <input type="text" name="txtNome" id="txtNome" class="form-control input-sm">
                </div>
                <div class="col-sm-6">
                  Sobrenome:<span class="text-danger">*</span>
                  <input type="text" name="txtSobrenome" id="txtSobrenome" class="form-control input-sm">
                </div>
                <div class="col-sm-6">
                  CPF:<span class="text-danger">*</span>
                  <input type="text" name="txtCpf" id="txtCpf" class="form-control input-sm">
                </div>
                <div class="col-sm-6">
                  Praça:<span class="text-danger">*</span>
                  <input type="text" name="txtPraca" id="txtPraca" class="form-control input-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-6">
                  Telefone:<span class="text-danger">*</span>
                  <input type="text" name="txtTelefone" id="txtTelefone" class="form-control input-sm">
                </div>
                <div class="col-sm-6">
                  Email:<span class="text-danger">*</span>
                  <input type="text" name="txtEmail" id="txtEmail" class="form-control input-sm">
                </div>
              </div>

              <div class="form-row mt-4">
                <div class="col-sm-6">
                  Limite diário(R$):<span class="text-danger">*</span>
                  <input type="text" name="txtLimDia" id="txtLimDia" class="form-control input-sm">
                </div>
                <div class="col-sm-6">
                  Limite por aposta(R$):<span class="text-danger">*</span>
                  <input type="text" name="txtLimApo" id="txtLimApo" class="form-control input-sm">
                </div>
                <div class="col-sm-6">
                  Porcentagem gerente:<span class="text-danger">*</span>
                  <input type="text" name="txtPorcentagem" id="txtPorcentagem" class="form-control input-sm">
                </div>
              </div>
              <div class="form-row mb-sm-0" id="funcao">
              </div>
            </form>
          </div>
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
  
  $('#funcionarioModal').on('hidden.bs.modal', () => {
    limpaCampos();
  });
</script>