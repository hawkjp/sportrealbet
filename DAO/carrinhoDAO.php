<?php

include_once("conexao.php");
include_once("../FuncoesPHP/sessao.php");

$acao = $_POST['acao'];

if (isset($_POST['cod_funcionario'])) {
    $cod_funcionario = $_POST['cod_funcionario'];
} else {
    $cod_funcionario = 0;
}

if (isset($_POST['resultado'])) {
    $resultado = $_POST['resultado'];
} else {
    $resultado = 0;
}

if (isset($_POST['id_partida'])) {
    $id_partida = $_POST['id_partida'];
} else {
    $id_partida = 0;
}

switch ($acao) {
    case "INCLUIR":
        adicionarCarrinho($resultado, $id_partida);
        buscaCarrinho();
        break;
    case "DELETAR":
        removerCarrinho($id_partida);
        buscaCarrinho();
        break;
    case "LIMPAR":
        limparCarrinho();
        buscaCarrinho();
        break;
    case "BUSCA":
        buscaCarrinho();
        break;
    case "RETORNA":
        retornaCarrinho();
        break;
}

function consultaPartida($id_partida)
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT p.id_partida, p.datahora_partida, p.id_campeonato, c.nome_campeonato, 
                       id_time_casa, 
                       t_casa.nome_time as nome_time_casa, 
                       t_casa.id_pais_time as pais_time_casa, 
                       t_casa.url_logo_time as logo_time_casa, p.V1,
                       id_time_visitante, 
                       t_visitante.nome_time as nome_time_vis, 
                       t_visitante.id_pais_time as pais_time_vis, 
                       t_visitante.url_logo_time as logo_time_vis, p.V2,
                       p.DD, 
                       12V1, 12V2,
                       13MG, 13LG,
                       14MC, 14LC,
                       15V1, 15DD, 15V2,
                       16MG, 16LG,
                       17MC, 17LC,
                       18V1, 18DD, 18V2,
                       nome_V1, nome_DD, nome_V2,
                       nome_12, nome_13, nome_14,
                       nome_15, nome_16, nome_17,
                       nome_18
                FROM tb_partidas p 
                INNER JOIN tb_time t_casa ON p.id_time_casa = t_casa.id_time 
                INNER JOIN tb_time t_visitante ON p.id_time_visitante = t_visitante.id_time
                INNER JOIN tb_campeonato c ON p.id_campeonato = c.id_campeonato 
                LEFT JOIN tb_odds o ON p.id_partida = o.id_partida
                WHERE p.id_partida = $id_partida ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            while ($row = $result->fetch_object()) {
                foreach ($row as $key => $col) {
                    $col_array[$key] = $col;
                }
                $row_array[] =  $col_array;
            }

            return $row_array[0];
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}


function adicionarCarrinho($resultado, $id_partida)
{
    $partidaArray = consultaPartida($id_partida);

    $cartArray = array(
        'idPartida' => $partidaArray['id_partida'],
        'nomeAposta'  => $partidaArray['nome_' . substr($resultado, 0, 2)],
        'aposta'    => $resultado,
        'timeCasa'  => array(
            'idTimeCasa'   => $partidaArray['id_time_casa'],
            'nomeTimeCasa' => $partidaArray['nome_time_casa']
        ),
        'timeVisitante'  => array(
            'idTimeVisitante'   => $partidaArray['id_time_visitante'],
            'nomeTimeVisitante' => $partidaArray['nome_time_vis']
        ),
        'premio'     => $partidaArray[$resultado],
        'datahora'   => $partidaArray['datahora_partida'],
        'campeonato' => $partidaArray['nome_campeonato']
    );

    if (empty($_SESSION["carrinho"])) {
        $_SESSION["carrinho"][$id_partida] = $cartArray;
    } else {
        $array_keys = array_keys($_SESSION["carrinho"]);

        if (in_array($id_partida, $array_keys)) {
            if ($_SESSION["carrinho"][$id_partida]["aposta"] == $resultado) {
                removerCarrinho($id_partida);
            } else {
                removerCarrinho($id_partida);
                $_SESSION["carrinho"][$id_partida] = $cartArray;
            }
        } else {
            $_SESSION["carrinho"][$id_partida] = $cartArray;
        }
    }
}

function retornaCarrinho(){
    $cartArray = [];
    foreach (pegaCarrinho() as $aposta) {
        $cartArray[$aposta['idPartida']] = $aposta['aposta'];
    }
    echo json_encode($cartArray);
}

function removerCarrinho($idPartida)
{
    unset($_SESSION["carrinho"][$idPartida]);
    if (empty($_SESSION["carrinho"])) {
        unset($_SESSION["carrinho"]);
    }
}

function buscaOdds($idPartida)
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sqlOdds = "SELECT * FROM  tb_odds
        WHERE  id_partida = " . $idPartida;

        $result = mysqli_query($open, $sqlOdds);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            while ($row = $result->fetch_object()) {
                foreach ($row as $key => $col) {
                    $col_array[$key] = $col;
                }
                $row_array[] =  $col_array;
            }

            return $row_array;
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}


function limparCarrinho()
{
    unset($_SESSION["carrinho"]);
}

?>

<?php

function buscaCarrinho()
{
if (empty(pegaCarrinho())) {
    echo "<h3>Nenhuma aposta!</h3>";
} else {
    $total_price = 0;
    $multiplicadorFinal = 1.0;
?>
    <div class="row align-items-center justify-content-center ">
        <div class="col-md-12">
            <div id="dvRetCarrinhoSent">
            </div>
        </div>
    </div><input type='hidden' name='hdnCart' id='hdnCart' value=<?php echo isset($_SESSION["carrinho"]) ? count($_SESSION["carrinho"]) : 0 ?>>
    <div class="row align-items-center justify-content-center ">
        <div class="col-md-12">
            <div class="d-flex flex-grow-1 align-items-center justify-content-center p-3 item">
                <h3 class="card-title"><i class="fas fa-ticket-alt"></i> Cupom de aposta</h3>
            </div>
        </div>
    </div>
    <?php
    foreach (pegaCarrinho() as $aposta) {
    ?>
        <div class="card mb-2" id="aposta1">
            <ul class="list-group list-group-flush">
                <li class="list-group-item border">
                    <h5>Futebol - <?php echo $aposta["campeonato"] ?> </h2>
                        <h4><?php echo $aposta["timeCasa"]["nomeTimeCasa"] . " X " . $aposta["timeVisitante"]["nomeTimeVisitante"]; ?>
                    </h5>
                    <h4>Premio: <?php echo $aposta["premio"] ?></h5>
                        <h5><?php echo $aposta["nomeAposta"]; ?></h5>
                        <h5>Data/Hora: <?php echo date("d/m/Y H:i", strtotime($aposta['datahora'])); ?></h5>
                </li>
            </ul>
        </div>
    <?php
        $multiplicadorFinal *= $aposta["premio"];
    }
    ?>

    <div class="row align-items-center" style="padding-top: 20px;">
        <div class="col-md-12">
            <input type="text" name="amount" class="form-control border border-dark rounded .money" onkeyup="calculaRetorno(this.value)" id="amount" placeholder="Valor da aposta" required><br />
            <span>Quantidade de jogos: </span><span id="betsQty"><b><?php echo isset($_SESSION["carrinho"]) ? count($_SESSION["carrinho"]) : 0 ?></b></span><br />
            <span>Cotação: <b><a id="oddsTemp"><?php echo number_format($multiplicadorFinal, '2') ?></a>x</b></span><br /><br />
            <span>Possível retorno: <b><a id="possibleIncome">R$0,00</a></b></span><br />
            <span>Cambista paga: <b><a id="tax">R$0,00</a></b></span><br /><br />
        </div>
    </div>

    <div class="row align-items-center" style="padding-top: 20px;">
        <div class="col-md-12">
            <button class="btn btn-danger btn-block" onclick="limparCarrinho()">
                <li class="fas fa-trash-alt"></li> Limpar carrinho
            </button>
        </div>
    </div>

    <div class="row align-items-center" style="padding-top: 20px;">
        <div class="col-md-12">
            <button class="btn btn-success btn-block" onclick="completarJogo()">
                <li class="fas fa-check"></li> Fechar aposta!
            </button>
        </div>
    </div>

<?php
}
}
?>