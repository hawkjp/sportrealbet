<?php
include_once("conexao.php");
session_start();

$id =  $_POST['idUsuario'];
$senha = hash("ripemd160", $_POST['senha']);

$sql = "update tb_usuario set senha = '$senha' where cod_usuario = $id";

$con =  new conexao();
$open = $con->connect();

try {
    $result = mysqli_query($open, $sql);

    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        echo "<div class='alert alert-success'>Senha alterada com sucesso!</div>";
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}
$con->disconnect($open);
