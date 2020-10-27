<?php
include_once("conexao.php");
session_start();

$acao = $_POST['acao'];
if (isset($_POST['cod_usuario'])) {
    $cod_usuario = $_POST['cod_usuario'];
} else {
    $cod_usuario = 0;
}

switch ($acao) {
    case "CAD":
        cadastrarUsuario();
        break;
    case "BUSCA":
        buscarUsuario($cod_usuario);
        break;
    case "ALTERAR":
        editarUsuario();
        break;
    case "ALTERAR_SENHA":
        alterarSenha();
        break;
}

function cadastrarUsuario()
{
    $txtUsuario     = $_POST['txtUsuario'];
    $txtNome        = $_POST['txtNome'];
    $txtSobrenome   = $_POST['txtSobrenome'];
    $rdPerfil       = $_POST['rdPerfil'];

    $con =  new conexao();
    $open = $con->connect();

    try {
        $senhaPadrao = "sistema123";
        $senha = hash("ripemd160", $senhaPadrao);

        $sql = "INSERT INTO tb_usuario(
            username
            ,first_name
            ,last_name
            ,senha
            ,perfil
        ) 
        VALUES(
        '$txtUsuario'
        ,'$txtNome'
        ,'$txtSobrenome'
        ,'$senha'
        ,$rdPerfil
        )";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            //ARRUMAR ESSA MENSAGEM
            echo "<div class='alert alert-success'><b>Usuário cadastrado com sucesso ! Senha é: " . $senhaPadrao . "</b></div>";
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function editarUsuario()
{
    $txtUsuario     = $_POST['txtUsuario'];
    $txtNome        = $_POST['txtNome'];
    $txtSobrenome   = $_POST['txtSobrenome'];
    $rdPerfil       = $_POST['rdPerfil'];
    $hdnCod         = $_POST['hdnCod'];

    if ($hdnCod != 0) {
        $con =  new conexao();
        $open = $con->connect();

        try {
            $sql = "UPDATE tb_usuario
                SET
                    username = '$txtUsuario'
                    ,first_name = '$txtNome'
                    ,last_name = '$txtSobrenome'
                    ,perfil = '$rdPerfil'
                WHERE cod_usuario = $hdnCod
            ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                //ARRUMAR ESSA MENSAGEM
                echo "<div class='alert alert-success'><b>Usuário editado com sucesso !</b></div>";
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        $con->disconnect($open);
    }
}


function buscarFuncionarios()
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT * FROM tb_usuario";
        $sql = $sql . " where perfil = 3 ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {

            while ($row = $result->fetch_object()) {
                foreach ($row as $key => $col) {
                    $col_array[$key] = utf8_encode($col);
                }
                $row_array[] =  $col_array;
            }

            echo json_encode($row_array);
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}


function buscarUsuario($cod_usuario)
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT * FROM tb_usuario";
        if ($cod_usuario != 0) {
            $sql = $sql . " where cod_usuario = $cod_usuario";
        }

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {

            while ($row = $result->fetch_object()) {
                foreach ($row as $key => $col) {
                    $col_array[$key] = utf8_encode($col);
                }
                $row_array[] =  $col_array;
            }

            echo json_encode($row_array);
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function alterarSenha()
{
    if (!isset($_SESSION['cod_usuario']) == true) {
        header('location:../index.php');
    } else {
        $con =  new conexao();
        $open = $con->connect();

        $txtSenhaAtual     =  hash("ripemd160", $_POST['txtSenhaAtual']);
        $txtSenhaNova      =  hash("ripemd160", $_POST['txtSenhaNova']);
        $id_user           = $_SESSION['cod_usuario'];

        $sql = "SELECT * FROM tb_usuario WHERE cod_usuario = $id_user AND senha = '$txtSenhaAtual'";

        try {
            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                if ($result->num_rows > 0) {

                    $sql2 = "UPDATE tb_usuario SET senha = '$txtSenhaNova' WHERE cod_usuario = $id_user";
                    try {
                        $result = mysqli_query($open, $sql2);
                        if (!$result) {
                            $erro = mysqli_error($open);
                            throw new $erro;
                        } else {
                            echo "<div class='alert alert-success'>Senha alterada !</div>";
                        }
                    } catch (Exception $e) {
                        echo ($e->getMessage());
                    }
                } else {
                    echo "<div class='alert alert-danger'>Senha atual incorreta !</div>";
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        $con->disconnect($open);
    }
}
