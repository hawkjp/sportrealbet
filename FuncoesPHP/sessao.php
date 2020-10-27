<?php
date_default_timezone_set('America/Los_Angeles');
session_start();

function verificaSessao()
{
	if (!isset($_SESSION)) {
		session_start();
		/*
		verificar depois
		$_SESSION['carrinho'] = [];
		*/
		$_SESSION['carrinho'] = 0;
	}
	if (!isset($_SESSION['cod_usuario']) == true) {
		//header('location:../index.php');
	} else {
		$usuarioS = $_SESSION['usuario'];
		$first_nameS = $_SESSION['first_name'];
		$last_nameS = $_SESSION['last_name'];
		$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : 0 ;
	}
}

function pegaNmSessao()
{
	$first_nameS = $_SESSION['first_name'];
	$last_nameS = $_SESSION['last_name'];

	echo $first_nameS . ' ' . $last_nameS;
}

function pegaUsername()
{
	$usuario = $_SESSION['usuario'];

	echo $usuario;
}

function pegaId()
{
	if (isset($_SESSION['cod_usuario'])){
		return $_SESSION['cod_usuario'];
	}
	else {
		return 0;
	}
}

function pegaPerfil()
{
	if (isset($_SESSION['cod_usuario'])) {
		return $_SESSION['perfil'];
	} else {
		return 0;
	}
}

function pegaCodFuncionario()
{
	if ($_SESSION['perfil'] == 3) {
		return $_SESSION['cod_funcionario'];
	} else {
		return 0;
	}
}

function pegaCodGerente()
{
	if ($_SESSION['perfil'] == 3 || $_SESSION['perfil'] == 4) {
		return $_SESSION['cod_gerente'];
	} else {
		return 0;
	}
}

function finalizaSessao()
{
	try {
		session_destroy();
	} catch (Exception $e) {
		header('location:../index.php');
	}
}

function pegaCarrinho()
{
	return isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
}


function pegaPartidaCarrinho($idPartida)
{
	return isset($_SESSION['carrinho'][$idPartida]) ? $_SESSION['carrinho'][$idPartida] : [];
}