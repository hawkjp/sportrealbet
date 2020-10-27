<?php
session_start();
include_once("conexao.php");
include_once("../FuncoesPHP/funcoes.php");


$con =  new conexao();
$open = $con->connect();

$usuario = $_POST['usuario'];
$senha = hash("ripemd160", $_POST['senha']);

try {

	$sql = "select * from tb_usuario where username = '$usuario' and senha = '$senha'";

	$result = mysqli_query($open, $sql);
	if (!$result) {
		$erro = mysqli_error($open);
		throw new $erro;
	} else {
		//Sucesso
		if ($result->num_rows > 0) {
			echo "<div class='alert alert-primary'>Redirecionando ao sistema...</div>";

			$arrayDados = $result->fetch_array(MYSQLI_ASSOC);

			$cod_usuario = $arrayDados['cod_usuario'];
			$_SESSION['usuario'] = $arrayDados['username'];
			$_SESSION['cod_usuario'] = $arrayDados['cod_usuario'];
			$_SESSION['first_name'] = $arrayDados['first_name'];
			$_SESSION['last_name'] = $arrayDados['last_name'];
			$_SESSION['perfil'] = $arrayDados['perfil'];
			$_SESSION['cod_funcionario'] = 0;
			$_SESSION['cod_gerente'] = 0;

			if ($arrayDados['perfil'] == 3) {
				try {

					$con2 =  new conexao();
					$open2 = $con2->connect();
					$sql = "select * from tb_funcionario where cod_usuario = '$cod_usuario'";
					// echo ($sql);
					$result2 = mysqli_query($open2, $sql);
					if (!$result2) {
						$erro = mysqli_error($open2);
						throw new $erro;
					} else {
						if ($result2->num_rows > 0) {
							$arrayDadosF = $result2->fetch_array(MYSQLI_ASSOC);

							$_SESSION['cod_funcionario'] = $arrayDadosF['cod_funcionario'];
							$_SESSION['cod_gerente'] = $arrayDadosF['cod_gerente'];
						}
					}
					$con2->disconnect($open2);
				} catch (Exception $e) {
					echo ($e->getMessage());
					session_destroy();
				}
			}
			else{
				if ($arrayDados['perfil'] == 4) {
					$_SESSION['cod_gerente'] == $arrayDados['cod_usuario'];
				}
			}
			/*func da lib funcoes.php*/
			redirecionaPHP_ECHO('index.php');
		} else {
			echo "<div class='alert alert-danger'>Usuário ou senha incorretos!</div>";
		}
	}
} catch (Exception $e) {
	echo ($e->getMessage());
	session_destroy();
}

$con->disconnect($open);
