<?php
include_once("conexao.php");
include_once("../FuncoesPHP/sessao.php");

$acao = $_POST['acao'];

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
            cadastrarGerente();
            break;
        case "BUSCA":
            buscarGerente($cod_gerente);
            break;
        case "BUSCA_LISTA":
            buscarListaGerentes();
            break;
        case "BUSCA_FUNC":
            buscarFuncionariosGerente($cod_gerente);
            break;
        case "ALTERAR":
            editarGerente($cod_gerente);
            break;
    }
}

function cadastrarGerente()
{
    $txtUsuario      = $_POST['txtUsuario'];
    $selGerente      = !isset($_POST['selGerente']) ? null : $_POST['selGerente'];
    $txtNome         = $_POST['txtNome'];
    $txtSobrenome    = $_POST['txtSobrenome'];
    $txtCpf          = $_POST['txtCpf'];
    $txtPraca        = $_POST['txtPraca'];
    $txtTelefone     = $_POST['txtTelefone'];
    $txtEmail        = $_POST['txtEmail'];
    $txtLimiteDiario = $_POST['txtLimiteDiario'];
    $txtLimiteAposta = $_POST['txtLimiteAposta'];

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
        , 4
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
                $sqlNewFunc = "INSERT INTO tb_gerente(
                     cod_gerente
                    ,nome_praca
                    ,lim_aposta_diario
                    ,lim_aposta_unica
                ) 
                VALUES(
                 '$cod_usuario'
                ,'$txtPraca'
                , $txtLimiteDiario
                , $txtLimiteAposta
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


function buscarGerente($cod_gerente)
{
    // echo (pegaCodFuncionario() . ' '  . pegaPerfil() . pegaId());
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "    SELECT username, first_name, last_name, nome_praca, lim_aposta_diario, lim_aposta_unica, u.email, u.cpf, u.telefone FROM tb_gerente g
                INNER JOIN tb_usuario u on g.cod_gerente = u.cod_usuario ";

        if ($cod_gerente != 0) {
            $sql = $sql . " WHERE u.cod_usuario = " . $cod_gerente;
        } else {
            switch (pegaPerfil()) {
                case 4:
                    $sql = $sql . " where cod_gerente = " . pegaId();
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

function buscarListaGerentes()
{
    $con =  new conexao();
    $open = $con->connect();
    try {
        $sql = "SELECT * FROM tb_usuario u 
            INNER JOIN tb_gerente ON cod_gerente = cod_usuario";
        $sql = $sql . " order by cod_usuario ";
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
                                        <span data-toggle="collapse" href="#tc<?php echo $row["cod_usuario"]; ?>" aria-expanded="false" aria-controls="tc<?php echo  $row["cod_usuario"]; ?>" id="ger<?php echo $row["cod_usuario"]; ?>" class="d-block cfm" onclick="showManagerTree(<?php echo $row["cod_usuario"]; ?>,'tbdy<?php echo $row["cod_usuario"]; ?>')">
                                            <i class="fa fa-user-tie pull-right"></i>
                                            <?php echo ' ' .  $row["username"]; ?>
                                        </span>
                                    </div>
                                    <div class="col-sm-3 col-12">
                                            <?php echo 'Limite: R$ ' .  number_format($row["lim_aposta_diario"], 2, ',', ''); ?>
                                    </div>
                                    <div class="col-sm-6 col-12 mt-2 mt-sm-0">
                                        <div class="btn-group " role="group">
                                            <button class="btn btn-primary btn-sm" onclick="buscaGerente('<?php echo $row["cod_usuario"]; ?>')">Editar</button>
                                            <button class="btn btn-danger ml-2 btn-sm" onclick="blockGerente('<?php echo $row["cod_usuario"]; ?>')">Bloquear</button>
                                        </div>
                                    </div>
                                </div>
                            </h5>
                            <div id="tc<?php echo $row["cod_usuario"]; ?>" class="collapse" aria-labelledby="heading-example">
                                <div class="card-body" id="tbdy<?php echo $row["cod_usuario"]; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo 0;
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
    $con->disconnect($open);
}


function buscarFuncionariosGerente($cod_gerente)
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT cod_funcionario, username, first_name, last_name, nome_praca, lim_aposta_diario, lim_aposta_unica, u.email, u.cpf, u.telefone FROM tb_funcionario f
            INNER JOIN tb_usuario u on f.cod_usuario = u.cod_usuario
            WHERE cod_gerente = " . $cod_gerente;

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                ?>
                    <div class='row ml-2 mt-2 mb-2 border-bottom'>
                        <div class='col-sm-4 align-items-center'>
                            <i class="fa fa-user pull-right"></i>
                            <span class='ml-2'><?php echo $row["first_name"] . " " . $row["last_name"] . " (" . $row["last_name"] . ")" ?></span>
                        </div>
                        <div class='col-sm-3 mb-2'>
                            <span class='ml-2'>Diario: R$ <?php echo number_format($row["lim_aposta_diario"], 2, ',', ''); ?></span>
                            <br />
                            <span class='ml-2'>Unica: R$ <?php echo number_format($row["lim_aposta_unica"], 2, ',', ''); ?></span>
                        </div>
                        <div class='col-sm-2 mb-2'>
                            <button onclick="buscaFuncionario('<?php echo $row["cod_funcionario"] ?>')" class="btn btn-success btn-user btn-block btn-sm">Listar</button>
                        </div>
                        <div class='col-sm-2 mb-4'>
                            <a onclick="historicoFuncionario('<?php echo $row["cod_funcionario"] ?>')" class="btn btn-danger btn-user btn-block btn-sm">Bloquear</a>
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


function editarGerente($cod_gerente)
{
    $praca              = $_POST['txtPraca'];
    $lim_aposta_diario  = $_POST['txtLimiteDiario'];
    $lim_aposta_unica   = $_POST['txtLimiteAposta'];
    $txtTelefone        = $_POST['txtTelefone'];
    $txtEmail           = $_POST['txtEmail'];
    $cpf                = $_POST['txtCpf'];
    $hdnCod             = $_POST['hdnCod'];

    if ($hdnCod != 0) {
        $con =  new conexao();
        $open = $con->connect();

        try {
            $sql = "UPDATE tb_gerente
                SET
                     nome_praca = '$praca'
                    ,lim_aposta_diario = '$lim_aposta_diario'
                    ,lim_aposta_unica = '$lim_aposta_unica'
                WHERE cod_gerente = $cod_gerente
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
                        WHERE cod_usuario = $cod_gerente
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