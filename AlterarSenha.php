<?php
require_once("FuncoesPHP/sessao.php");
$id = pegaId(); ?>
<div class="row">
	<div class="col-lg-12">
		<div class="card mb-4 mt-4 shadow">
			<div class="card-header">
				<span style="font-weight: bold;">Alterar Senha</span>
			</div>
			<div class="card-body">
				<div class="row">
					<form name="frmAlteraSenha" id="frmAlteraSenha" method="POST" class="form-horizontal" autocomplete="off">
						<input type="hidden" name="hdnId" id="hdnId" value="<?php echo $_SESSION['cod_usuario']; ?>">
						<div class="form-row">
							<div class="col-sm-3">
								Informe a nova senha:
								<input type="password" name="txtNovaSenha" id="txtNovaSenha" class="form-control">
							</div>
							<div class="col-sm-3">
								<br>
								<button type="button" class="btn btn-primary btn-block" style="font-weight: bold;" onclick="mudaSenha()">
									Salvar nova senha
								</button>
							</div>
						</div>
						<div class="form-row mt-2">
							<div class="col-sm-6">
								<div id="dvRetj"></div>
							</div>
						</div>

					</form>
				</div>
				<div class="row" id="ticketList">
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	function mudaSenha() {
		if ($("#txtNovaSenha").val() == "") {
			$("#dvRetj").html("<div class='alert alert-danger'>A senha não pode estar em branco.</div>");
		} else {
			$.ajax({
				url: "DAO/senhaDAO.php",
				data: {
					senha: $("#txtNovaSenha").val(),
					idUsuario: $("#hdnId").val()
				},
				method: 'post',
				cache: false,
				beforeSend: function() {
					$("#dvRetj").html("Aguarde....");
				},
				success: function(retorno) {
					$("#dvRetj").html(retorno);
					$("#txtNovaSenha").val("");
				},
				error: function() {
					$("#dvRetj").html("Erro ao atualizar senha, tente novamente.");
					$("#txtNovaSenha").val();
				}
			});
		}
	}
</script>