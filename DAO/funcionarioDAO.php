<?php
include_once("conexao.php");
include_once("../FuncoesPHP/sessao.php");
// session_start();

$acao = $_POST['acao'];

if (isset($_POST['cod_funcionario'])) {
    $cod_funcionario = $_POST['cod_funcionario'];
} else {
    $cod_funcionario = 0;
}

if (isset($_POST['hdnCod'])) {
    $hdnCod = $_POST['hdnCod'];
} else {
    $hdnCod = 0;
}

if (isset($_POST['cod_gerente'])) {
    $cod_gerente = $_POST['cod_gerente'];
} else {
    $cod_gerente = 0;
}

if (pegaPerfil() < 4) {
    throw new Exception("<div class='alert alert-danger'>Usuario n'ao tem permissao!</div>");
} else {
    switch ($acao) {
        case "CAD":
            cadastrarFuncionario();
            break;
        case "BUSCA":
            buscarFuncionarios($cod_funcionario);
            break;
        case "BUSCA_GERENTES":
            buscarGerentes();
            break;
        case "ALTERAR":
            editarFuncionarios();
            break;
        case "BUSCA_FUNC":
            buscarFuncionario($cod_funcionario);
            break;            
    }
}

function cadastrarFuncionario()
{
    $selGerente      = $_POST['selGerente'];
    $txtLimiteDiario = $_POST['txtLimiteDiario'];

    $codGerente = 0;

    if (pegaPerfil() == 4) {
        $codGerente = pegaId();
        $validacao = validarCadastro($codGerente, $txtLimiteDiario);
    
        if ($validacao == "0") {
            insertFuncionario($codGerente);
        } else {
            echo $validacao;
        }
    } else {
        insertFuncionario($selGerente);
    }
}

function insertFuncionario($codGerente)
{
    $txtUsuario      = $_POST['txtUsuario'];
    $txtNome         = $_POST['txtNome'];
    $txtSobrenome    = $_POST['txtSobrenome'];
    $txtCpf          = $_POST['txtCpf'];
    $txtPraca        = $_POST['txtPraca'];
    $txtTelefone     = $_POST['txtTelefone'];
    $txtEmail        = $_POST['txtEmail'];
    $txtLimiteDiario = $_POST['txtLimiteDiario'];
    $txtLimiteAposta = $_POST['txtLimiteAposta'];
    $txtPorcentagem  = $_POST['txtPorcentagem'];

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
            ,cpf
            ,telefone
            ,email
        ) 
        VALUES(
         '$txtUsuario'
        ,'$txtNome'
        ,'$txtSobrenome'
        ,'$senha'
        , 3
        ,'$txtCpf'
        ,'$txtTelefone'
        ,'$txtEmail'
        )";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            $cod_usuario = mysqli_insert_id($open);
            try {
                $sqlNewFunc = "INSERT INTO tb_funcionario(
                     cod_usuario
                    ,cod_gerente
                    ,nome_praca
                    ,lim_aposta_diario
                    ,lim_aposta_unica
                    ,porcentagem
                ) 
                VALUES(
                 '$cod_usuario'
                ,'$codGerente'
                ,'$txtPraca'
                , $txtLimiteDiario
                , $txtLimiteAposta
                , $txtPorcentagem
                )";
                $result = mysqli_query($open, $sqlNewFunc);
                if (!$result) {
                    $erro = mysqli_error($open);
                    throw new $erro;
                } else {
                    echo "<div class='alert alert-success'><b>Usuário cadastrado com sucesso ! Senha é: " . $senhaPadrao . "</b></div>";
                }
            } catch (Exception $e) {
                echo ($e->getMessage());
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function editarFuncionarios()
{
    $praca              = $_POST['txtPraca'];
    $lim_aposta_diario  = $_POST['txtLimiteDiario'];
    $lim_aposta_unica   = $_POST['txtLimiteAposta'];
    $txtTelefone        = $_POST['txtTelefone'];
    $txtEmail           = $_POST['txtEmail'];
    $cpf                = $_POST['txtCpf'];
    $hdnCod             = $_POST['hdnCod'];
    $txtPorcentagem     = $_POST['txtPorcentagem'];


    if ($hdnCod != 0) {
        $con =  new conexao();
        $open = $con->connect();

        try {
            $sql = "UPDATE tb_funcionario
                SET
                     nome_praca = '$praca'
                    ,lim_aposta_diario = '$lim_aposta_diario'
                    ,lim_aposta_unica = '$lim_aposta_unica'
                    ,porcentagem = '$txtPorcentagem'
                WHERE cod_funcionario = $hdnCod
            ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {

                $con2 =  new conexao();
                $open2 = $con2->connect();
                try {
                    $sql2 = "UPDATE tb_usuario
                        SET
                             cpf = '$cpf'
                            ,telefone  = '$txtTelefone'
                            ,email = '$txtEmail'
                        WHERE cod_usuario = (SELECT cod_usuario FROM tb_funcionario
                                WHERE cod_funcionario = $hdnCod)
                    ";
                    $result2 = mysqli_query($open2, $sql2);
                    if (!$result2) {
                        $erro = mysqli_error($open2);
                        throw new $erro;
                    } else {
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
                echo "<div class='alert alert-success'><b>Funcionário editado com sucesso!</b></div>";
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        $con->disconnect($open);
    }
}


function buscarFuncionario($cod_funcionario)
{
    // echo (pegaCodFuncionario() . ' '  . pegaPerfil() . pegaId());
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "    SELECT username, first_name, last_name, nome_praca, lim_aposta_diario, lim_aposta_unica, u.email, u.cpf, u.telefone, cod_funcionario, porcentagem FROM tb_funcionario f
                INNER JOIN tb_usuario u on f.cod_usuario = u.cod_usuario ";

        if ($cod_funcionario != 0) {
            $sql = $sql . " WHERE u.cod_usuario = " . $cod_funcionario;
        } else {
            switch (pegaPerfil()) {
                case 4:
                    $sql = $sql . " where cod_gerente = " . pegaId();
                    break;
                case 3:
                    $sql = $sql . " where cod_funcionario = " . pegaCodFuncionario();
                    break;
            }
        }

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_object()) {
                    foreach ($row as $key => $col) {
                        $col_array[$key] = utf8_encode($col);
                    }
                    $row_array[] =  $col_array;
                }
                echo json_encode($row_array);
            } else {
                throw new Exception("<div class='alert alert-danger'>Nao ha gerente cadastrados!</div>");
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function buscarFuncionarios($cod_funcionario)
{
    // echo (pegaCodFuncionario() . ' '  . pegaPerfil() . pegaId());
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT u.cod_usuario, cod_funcionario, username, first_name, last_name, nome_praca, lim_aposta_diario, lim_aposta_unica, u.email, u.cpf, u.telefone FROM tb_funcionario f
                INNER JOIN tb_usuario u on f.cod_usuario = u.cod_usuario ";

        if ($cod_funcionario != 0) {
            $sql = $sql . " where cod_funcionario = " . $cod_funcionario;
        } else {
            switch (pegaPerfil()) {
                case 4:
                    $sql = $sql . " where cod_gerente = " . pegaId();
                    break;
                case 3:
                    $sql = $sql . " where cod_funcionario = " . pegaCodFuncionario();
                    break;
            }
        }

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-lg-12">
                    <div class="card  mb-3">
                        <h5 class="card-header justify-content-center">
                            <div class="row">
                                <div class="col-sm-3 col-12 mb-2">
                                    <span data-toggle="collapse" href="#tc<?php echo $row["cod_usuario"]; ?>" aria-expanded="false" aria-controls="tc<?php echo  $row["cod_usuario"]; ?>" id="fun<?php echo $row["cod_usuario"]; ?>" class="d-block cfm" >
                                        <i class="fa fa-user pull-right"></i>
                                        <?php echo ' ' .  $row["username"]; ?>
                                    </span>
                                </div>
                                <div class='col-sm-3 mb-2'>
                                    <span class='ml-2'>Diario: R$ <?php echo number_format($row["lim_aposta_diario"], 2, ',', ''); ?></span>
                                    <br />
                                    <span class='ml-2'>Unica: R$ <?php echo number_format($row["lim_aposta_unica"], 2, ',', ''); ?></span>
                                </div>
                                <div class="col-sm-6 col-12 mt-2 mt-sm-0">
                                    <div class="btn-group " role="group">
                                        <button class="btn btn-primary btn-sm" onclick="buscaFuncionarioAlt('<?php echo $row["cod_usuario"]; ?>')">Editar</button>
                                        <button class="btn btn-danger ml-2 btn-sm" onclick="blockFuncionario('<?php echo $row["cod_usuario"]; ?>')">Bloquear</button>
                                    </div>
                                </div>
                            </div>
                        </h5>
                    </div>
                </div>
            <?php
                }
            } else {
                throw new Exception("<div class='alert alert-danger'>Nao ha funcionarios cadastrados!</div>");
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function buscarGerentes()
{

    if (pegaPerfil() <= 4) {
        echo 0;
    } else {

        $con =  new conexao();
        $open = $con->connect();

        try {

            $sql = "SELECT cod_usuario, username FROM tb_usuario u
                WHERE perfil = 4 ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {

                if (mysqli_num_rows($result) > 0) {
                    while ($row = $result->fetch_object()) {
                        foreach ($row as $key => $col) {
                            $col_array[$key] = utf8_encode($col);
                        }
                        $row_array[] =  $col_array;
                    }
                    echo json_encode($row_array);
                } else {
                    throw new Exception("<div class='alert alert-danger'>Nao ha gerentes cadastrados!</div>");
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        $con->disconnect($open);
    }
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

        $sql = "SELECT * FROM tb_funcionario WHERE cod_usuario = $id_user AND senha = '$txtSenhaAtual'";

        try {
            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                if ($result->num_rows > 0) {

                    $sql2 = "UPDATE tb_funcionario SET senha = '$txtSenhaNova' WHERE cod_usuario = $id_user";
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


function validarCadastro($cod_gerente, $limite)
{
    $limiteGerente = 0.0;
    $limiteUtilizadoGerente = 0.0;
    
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT lim_aposta_diario FROM tb_gerente u
                WHERE cod_gerente = $cod_gerente ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    $limiteGerente = $row["lim_aposta_diario"];
                }
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    try {

        $sql = "SELECT SUM(lim_aposta_diario) AS limite_utilizado FROM tb_funcionario f
                WHERE cod_gerente = $cod_gerente ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    $limiteUtilizadoGerente = $row["limite_utilizado"] == null ? 0 : $row["limite_utilizado"];
                }
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);

    if ($limiteUtilizadoGerente + $limite > $limiteGerente) {
        return "<div class='alert alert-danger'><b>Ultrapassado o limite: R$ " . strval($limiteGerente) . ". Contate um administrador.</b></div>";
    }
    
    return "0";

}
