<?php
//include_once("sessao.php");
include_once("conexao.php");
include_once("../FuncoesPHP/sessao.php");


$acao = $_POST['acao'];

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
} else {
    $id_ticket = 0;
}
if (isset($_POST['valor'])) {
    $valor = $_POST['valor'];
} else {
    $valor = 0;
}
if (isset($_POST['mult'])) {
    $mult = $_POST['mult'];
} else {
    $mult = 0;
}

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
} else {
    $nome = '';
}

if (isset($_POST['atualiza'])) {
    $atualiza = $_POST['atualiza'];
} else {
    $atualiza = "false";
}

switch ($acao) {
    case "BUSCA":
        buscarTicket($id_ticket);
        break;
    case "BUSCA_PEND":
        buscarTicketPendente();
        break;
    case "BUSCA_LISTA_PEND":
        buscarListaTicketPendente();
        break;
    case "CADASTRAR":
        $retorno = cadastrarTicket($valor, $mult, $nome);
        if(pegaPerfil() >= 3){
            try{
                $id_ticket = $retorno["ticket"];
                $retorno = aprovarTicket($retorno["ticket"], true);
                $retorno["codigo"] = 1;
                $retorno["titulo"] = "Jogo #$id_ticket realizado e aprovado!"; 
                $retorno["ticket"] = $id_ticket; 
                echo json_encode($retorno);
            }
            catch (Exception $e) {
                echo ($e->getMessage());
            }
        } else {
            echo json_encode($retorno);
        }
        break;
    case "CANCELAR":
        echo json_encode(cancelarTicket($id_ticket));
        break;
    case "APROVAR":
        echo json_encode(aprovarTicket($id_ticket, $atualiza));
        break;
}

function aprovarTicket($id_ticket, $atualiza)
{
    $funcionario = pegaId();

    $retorno = array(
        'codigo' => 0,
        'mensagem'  => ''
    );

    if (!(pegaPerfil() == 5)) {
        if(verificaPartidasAndamento($id_ticket)){
            cancelarTicket($id_ticket);
            $retorno["codigo"] = -1;
            $retorno["mensagem"] = "Aposta com partida em andamento ou adiado, ticket #$id_ticket cancelado";
        } else {
            $retorno = verificarAprovacao($id_ticket, $funcionario);
        }
    } else {
        if(verificaPartidasAndamento($id_ticket)){
            cancelarTicket($id_ticket);
            $retorno["codigo"] = -1;
            $retorno["mensagem"] = "Aposta com partida em andamento ou adiado, ticket #$id_ticket cancelado";
        }
    }

    if ($retorno["codigo"] == 0) {

        $con =  new conexao();
        $open = $con->connect();

        try {
            $sql = "UPDATE tb_ticket
                SET
                     status_ticket = 'Aprovado'
                    ,id_funcionario = '$funcionario'
                    ,datahora_efetivacao_pagamento = CURRENT_TIMESTAMP
                WHERE id_ticket = $id_ticket
            ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                $retorno["codigo"] = 0;
                $retorno["mensagem"] = "Ticket Aprovado";
                // echo json_encode($retorno);
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        $con->disconnect($open);
    } elseif ($retorno["codigo"] > 0 && $atualiza == "true") { 

        $con =  new conexao();
        $open = $con->connect();

        try {
            $ticketDB = buscaTicketBD($id_ticket);

            foreach($ticketDB as $partida){
                $sql = "UPDATE tb_aposta a
                           SET a.num_multiplicador = " . $partida[$partida["aposta"]]
                         . " WHERE a.id_aposta = " . $partida["id_aposta"];
                         
                $result = mysqli_query($open, $sql);
                if (!$result) {
                    $erro = mysqli_error($open);
                    throw new $erro;
                }
            }
            
            $premio = $retorno["codigo"] * $ticketDB[0]["valor_apostado"];

            $sql = "UPDATE tb_ticket
                SET
                     status_ticket = 'Aprovado'
                    ,id_funcionario = '$funcionario'
                    ,valor_multiplicador = " . $retorno["codigo"]
                . " ,datahora_efetivacao_pagamento = CURRENT_TIMESTAMP
                    ,premio = $premio
                WHERE id_ticket = $id_ticket
            ";
          
            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                $retorno["codigo"] = 0;
                $retorno["mensagem"] = "Ticket Aprovado";
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }
    }
    else {
        // echo 3;
        // echo json_encode($retorno);
    }
    return $retorno;
}

function buscarTicket($id_ticket)
{
    $ticketDB = buscaTicketBD($id_ticket);
    $dadosTicket = $ticketDB[0];
    $numJogo = 0;
    $multiplicadorFinal = 1.0;
    

?>
            <div id="printableArea" print>
                <div class="container mb-4 " id='ticketHeader'>
                    <div class='container mt-4 mb-4'>
                        <h4 class='card-title'>Ticket #<?php echo $id_ticket ?> </h4>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-fill bd-highlight ">
                                    Colaborador <h4><?php echo is_null($dadosTicket["id_funcionario"]) ? "-" : $dadosTicket["id_funcionario"] ?> </h4>
                            </div>
                            <div class="p-2 flex-fill bd-highlight ">
                                    Cliente <h4> <?php echo is_null($dadosTicket["nome_apostador"]) ? "-" : $dadosTicket["nome_apostador"] ?> </h4>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-fill bd-highlight ">
                                    Data cadastro<h4><?php echo date("d/m/Y H:i", strtotime($dadosTicket['datahora_registro_sistema'])) ?> </h4>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-fill bd-highlight ">
                                    Data Aprovacao<h4><?php echo is_null($dadosTicket["datahora_efetivacao_pagamento"]) ? "-" : date("d/m/Y H:i", strtotime($dadosTicket['datahora_efetivacao_pagamento'])) ?> </h4>
                            </div>
                        </div>
                    </div>
                </div>
<?php
    foreach($ticketDB as $partida){

        $valorApostado = $partida["valor_apostado"];
        $statusTicket = $partida["status_ticket"];
        $numJogo++;
?>
                    <div class="container" id='ticketBody'>
                        <div class='card justify-content-start'>
                            <div class='card-body'>
                                <h4 class='card-title'>Jogo <?php echo $numJogo ?> </h4>
                                <div class='container mt-4'>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-fill bd-highlight ">
                                            Data da partida: <h6><?php echo date("d/m/Y H:i", strtotime($partida['datahora_partida'])) ?> </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-fill bd-highlight ">
                                            Partida: <h6><?php echo $partida["nome_time_casa"] ?> X <?php echo $partida["nome_time_vis"] ?> </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-fill bd-highlight ">
                                            Aposta: <h6><?php echo ($partida['nome_' . substr($partida["aposta"], 0, 2)]) ?> </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-fill bd-highlight ">
                                        Multiplicador: <h6><?php echo ($partida["num_multiplicador"]) ?> </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-fill bd-highlight ">
                                        Campeonato: <h6><?php echo ($partida["nome_campeonato"]) ?> </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-fill bd-highlight ">
                                                Status da aposta: 
                                                <?php switch ($partida["resultado"]) {
                                                    case 'Finalizado':
                                                    case 'Ganhou!':
                                                ?>
                                                        <span style='font-style: italic; font-weight: bold; color: #1CC88A;'><?php echo '  ' . $partida["resultado"] ?></span>
                                                    <?php
                                                        break;
                                                    case 'A iniciar':
                                                    ?>
                                                        <span style='font-style: italic; font-weight: bold; color: #ffcc00;'><?php echo '  ' . $partida["resultado"] ?></span>
                                                    <?php
                                                        break;
                                                    case 'Cancelado':
                                                    case 'Perdeu':
                                                    ?>
                                                        <span style='font-style: italic; font-weight: bold; color: #e60000;'><?php echo '  ' . $partida["resultado"] ?></span>
                                                <?php break;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php
        $multiplicadorFinal *= $partida["num_multiplicador"];
    }
    $multiplicadorFinal = number_format($multiplicadorFinal, '2')
?>
                <div class="container mb-4 mt-4" id='ticketFooter'>
                    <div class='container'>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex bd-highlight ">
                            Valor apostado: <h6> R$<?php echo number_format($valorApostado, 2, ',', '.') ?> </h6>
                            </div>
                            <div class="p-2 flex bd-highlight ">
                            Multiplicador final*: <h6> <?php echo $multiplicadorFinal ?> x</h6>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex bd-highlight ">
                            Possivel retorno : <h6> R$<?php echo number_format(($multiplicadorFinal * $valorApostado > 30000 ? 27000 : ($multiplicadorFinal * $valorApostado)), 2, ',', '.') ?></h6>
                            </div>
                            <div class="p-2 flex bd-highlight ">
                            Cambista paga: <h6> R$<?php echo number_format(($multiplicadorFinal * $valorApostado > 30000 ? 27000 : ($multiplicadorFinal * $valorApostado) * 0.9), 2, ',', '.') ?></h6>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-fill bd-highlight ">
                                    Status da aposta: 
                                    <?php switch ($statusTicket) {
                                                                case 'Aprovado':
                                                                    $statusTicket = $statusTicket . ', partida(s) em andamento';
                                                                case 'Premiado!':
                                                            ?>
                                                <span style='font-style: italic; font-weight: bold; color: #1CC88A;'><?php echo $statusTicket ?></span>
                                            <?php
                                                                    break;
                                                                case 'Pendente pagamento':
                                                                case 'Adiado':
                                            ?>
                                                <span style='font-style: italic; font-weight: bold; color: #ffcc00;'><?php echo $statusTicket ?></span>
                                            <?php
                                                                    break;
                                                                case 'Cancelado':
                                                                case 'Perdeu':
                                            ?>
                                                <span style='font-style: italic; font-weight: bold; color: #e60000;'><?php echo $statusTicket ?></span>
                                        <?php break;
                                                        }
                                        ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
}
            function buscarListaTicketPendente()
            {
                $con =  new conexao();
                $open = $con->connect();

                try {
                    $sql = "SELECT * FROM tb_ticket t 
                                     WHERE status_ticket = 'Pendente pagamento' ";

                    $sql = $sql . " ORDER BY id_ticket DESC ";

                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <div class="col-lg-6">
                                    <div class="card  mb-3">
                                        <h5 class="card-header">
                                            <span data-toggle="collapse" href="#tc<?php echo $row["id_ticket"]; ?>" aria-expanded="false" aria-controls="tc<?php echo  $row["id_ticket"]; ?>" id="ttc<?php echo $row["id_ticket"]; ?>" class="d-block cft" onclick="showTicketPendente(<?php echo $row["id_ticket"]; ?>,'tbdy<?php echo $row["id_ticket"]; ?>')">
                                                <i class="fa fa-ticket-alt pull-right"></i>
                                                <?php echo '#' .  $row["id_ticket"]; ?>
                                            </span>
                                        </h5>
                                        <div id="tc<?php echo $row["id_ticket"]; ?>" class="collapse" aria-labelledby="heading-example">
                                            <div class="card-body" id="tbdy<?php echo $row["id_ticket"]; ?>">
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

            function buscarTicketPendente()
            {
                $con =  new conexao();
                $open = $con->connect();

                try {
                    $sql = "SELECT * FROM tb_ticket t 
                                    WHERE status_ticket = 'Pendente pagamento' ";

                    $sql = $sql . " order by id_ticket ";

                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = $result->fetch_object()) {
                                foreach ($row as $key => $col) {
                                    $col_array[$key] = $col;
                                }
                                $row_array[] =  $col_array;
                            }

                            echo json_encode($row_array);
                            // echo "TT";
                        } else {
                            echo 0;
                        }
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }

                $con->disconnect($open);
            }

function cadastrarTicket($valor, $mult, $nome)
{
    $retorno = array(
        'codigo' => 0,
        'mensagem'  => ''
    );

    $idAposta = 0;
    $idTicket = 0;
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);
    $erro = '';
    $premio = $valor * $mult;
    $con =  new conexao();
    $open = $con->connect();
    try {
        $sqlNewTicket = "INSERT INTO tb_ticket(
                          valor_apostado
                         ,valor_multiplicador
                         ,status_ticket
                         ,premio
                         ,nome_apostador
                         ) 
        VALUES(
         '$valor'
        ,'$mult'
        ,'Pendente pagamento'
        , $premio
        ,'$nome'
        )";
        $result = mysqli_query($open, $sqlNewTicket);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            $idTicket = mysqli_insert_id($open);

            foreach ($_SESSION["carrinho"] as $aposta) {
                $idPartida         = $aposta["idPartida"];
                $resultado         = $aposta["aposta"];
                $num_multiplicador = $aposta["premio"];

                try {
                    $sqlNewAposta = "INSERT INTO tb_aposta(
                                   id_partida
                                  ,aposta
                                  ,resultado
                                  ,num_multiplicador
                              ) 
                              VALUES(
                              '$idPartida'
                              ,'$resultado'
                              ,'A iniciar'
                              ,'$num_multiplicador'
                              )";

                    $result = mysqli_query($open, $sqlNewAposta);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        $idAposta = mysqli_insert_id($open);
                        try {
                            $sqlNewTicketAposta = "INSERT INTO tb_ticket_aposta(
                                                 id_ticket
                                                ,id_aposta
                                            ) 
                                            VALUES(
                                            '$idTicket'
                                            ,'$idAposta'
                                            )";
                            $result = mysqli_query($open, $sqlNewTicketAposta);
                            if (!$result) {
                                $erro = mysqli_error($open);
                                throw new $erro;
                            }
                        } catch (Exception $e) {
                            echo ($e->getMessage());
                        }
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);

    $retorno["codigo"] = 0;
    $retorno["titulo"] = "Jogo #$idTicket realizado!"; 
    $retorno["ticket"] = $idTicket; 

    return $retorno;
}

function cancelarTicket($id_ticket){

    $retorno = array(
        'codigo' => 0,
        'mensagem'  => ''
    );

    $funcionario = pegaId();
    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "SELECT *
        FROM tb_ticket
       WHERE id_ticket = $id_ticket
         AND status_ticket <> 'Pendente pagamento'
      ";
        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) == 0) {
                try {
                    $sql = "UPDATE tb_ticket
                   SET
                       status_ticket = 'Cancelado'
                      ,id_funcionario = '$funcionario'
                    WHERE id_ticket = $id_ticket
                ";
                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        $retorno["codigo"] = 0;
                        $retorno["mensagem"] = "Ticket $id_ticket cancelado!";
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
            } else {
                $retorno["codigo"] = -1;
                $retorno["mensagem"] = "Erro ao cancelar o Ticket!";
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);

    return $retorno;
}

function verificaPartidasAndamento($id_ticket){
    
    $con =  new conexao();
    $open = $con->connect();

    $sql = "SELECT p.resultado
        FROM tb_ticket t
        LEFT JOIN tb_ticket_aposta ta on t.id_ticket = ta.id_ticket 
        RIGHT JOIN tb_aposta a on a.id_aposta = ta.id_aposta
        INNER JOIN tb_partidas p on a.id_partida = p.id_partida
        WHERE t.id_ticket = $id_ticket AND p.resultado = '0'
    ";

    $result = mysqli_query($open, $sql);
    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        if (mysqli_num_rows($result) == 0) {
            // Caso jogos ja tenham começado ou estejam adiados, não permite aprovar
            $con->disconnect($open);
            return true;
        }
        else{
            return false;
        }
    }
    
    $con->disconnect($open);
}

function verificarAprovacao($id_ticket, $usuario){

    $retorno = array(
        'codigo' => 0,
        'mensagem'  => ''
    );

    $con =  new conexao();
    $open = $con->connect();

    $multTicket = 0.0;
    $valorAposta = 0.0;
    $lim_aposta_unica = 0.0;
    $lim_aposta_diario = 0.0;
    $apostas_dia = 0.0;

    try {
        // Obter valor apostado no ticket
    $sql = "SELECT valor_apostado, valor_multiplicador
      FROM tb_ticket
     WHERE id_ticket = $id_ticket
    ";
        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    $multTicket = $row["valor_multiplicador"];
                    $valorAposta = $row["valor_apostado"];
                }
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    if (pegaPerfil() == 4) {
        // Valida limite do gerente
        try {
            // Obter valores apostados no dia pelos funcionarios e pelos gerentes
            $sql = "SELECT SUM(valor_apostado) AS valor_dia
          FROM tb_ticket
         WHERE (id_funcionario IN (SELECT cod_funcionario FROM tb_funcionario WHERE cod_gerente = 13)
            OR id_funcionario = 13)    
           AND DATE(datahora_efetivacao_pagamento) = CURDATE()
              ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $apostas_dia = $row["valor_dia"] == null ? 0 : $row["valor_dia"];
                    }
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        try {
            // Obter limites cadastrados para o gerente
            $sql = "SELECT lim_aposta_diario, lim_aposta_unica
          FROM tb_gerente
         WHERE cod_gerente = $usuario
                ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $lim_aposta_unica = $row["lim_aposta_unica"];
                        $lim_aposta_diario = $row["lim_aposta_diario"];
                    }
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }
        $con->disconnect($open);
    } else {
        try {
            // Obter valores apostados no dia aprovado pelo funcionario
            $sql = "SELECT SUM(valor_apostado) AS valor_dia
          FROM tb_ticket
         WHERE id_funcionario = $usuario
           AND datahora_efetivacao_pagamento > DATE(NOW()) - INTERVAL 1 DAY
              ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $apostas_dia = $row["valor_dia"] == null ? 0 : $row["valor_dia"];
                    }
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        try {
            // Obter limites cadastrados para o funcionario
            $sql = "SELECT lim_aposta_diario, lim_aposta_unica
          FROM tb_funcionario
         WHERE cod_usuario = $usuario
                ";

            $result = mysqli_query($open, $sql);
            if (!$result) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $lim_aposta_unica = $row["lim_aposta_unica"];
                        $lim_aposta_diario = $row["lim_aposta_diario"];
                    }
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }
        $con->disconnect($open);
    }
    // echo $valorAposta . " | " . $lim_aposta_unica . " | " . $funcionario;
    // $retornoAprovacao = new Aprovacao();
    if ($valorAposta > $lim_aposta_unica) {
        $retorno['codigo'] = -1;
        $retorno['mensagem'] = "Aposta maior que o limite: R$ " . strval($lim_aposta_unica);
    }

    if (($apostas_dia + $valorAposta) > $lim_aposta_diario) {
        $retorno['codigo'] = -1;
        $retorno['mensagem'] = "Aposta ultrapassa o limite diario";
    }

    $multAtual = atualizarMultiplicador($id_ticket);
    if ($multAtual != "0") {
        $retorno['codigo'] = $multAtual;
        $retorno['mensagem'] = "Seu premio mudou!\n Premio: R$" .  number_format($valorAposta * $multTicket, 2, ',', '.')  . " -> R$" . number_format($valorAposta * $multAtual, 2, ',', '.');

    }
    
    return $retorno;
}

function atualizarTicket($idTicket)
{
                $con =  new conexao();
                $open = $con->connect();
                try {
                    $sql = "SELECT ta.id_aposta, a.resultado as resultado_aposta, p.resultado as resultado_partida, t.status_ticket, a.id_partida as id_partida
                FROM tb_ticket_aposta ta
                LEFT JOIN tb_aposta a on ta.id_aposta = a.id_aposta
                LEFT JOIN tb_partidas p  on a.id_partida = p.id_partida
                LEFT JOIN tb_ticket t  on t.id_ticket = $idTicket
                WHERE ta.id_ticket = $idTicket";

                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        $resultados = array();
                        $statusTicketAnt = '';
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $statusTicketAnt = $row["status_ticket"];
                                if ($row["status_ticket"] == 'Aprovado') {
                                    if ($row["resultado_aposta"] == "A iniciar" && ($row["resultado_partida"] == 'V1' || $row["resultado_partida"] == 'V2' || $row["resultado_partida"] == 'DD')) {
                                        //Atualiza aposta de partida Concluida
                                        $row["resultado_aposta"] = atualizarAposta($row["id_aposta"]);
                                    } else {
                                        if ($row["resultado_partida"] == '4') {
                                            //Atualiza aposta de partida adiada
                                            $row["resultado_aposta"] = atualizarApostaAdiada($row["id_aposta"], $row["id_partida"]);
                                        }
                                    }
                                    array_push($resultados, $row["resultado_aposta"]);
                                }
                            }
                            if ($statusTicketAnt == "Aprovado" && ((!in_array("A iniciar", $resultados) || in_array("Perdeu", $resultados)))) {
                                try {
                                    $statusTicketNov = '';
                                    if (in_array("Perdeu", $resultados)) {
                                        $statusTicketNov = 'Perdeu';
                                    } else {
                                        if (!in_array("Ganhou!", $resultados)) {
                                            $statusTicketNov = 'Adiado';
                                        } else {
                                            $statusTicketNov = 'Premiado!';
                                        }
                                    }

                                    $sql = "UPDATE tb_ticket t
                                   SET t.status_ticket = '$statusTicketNov'
                                 WHERE id_ticket = $idTicket";
                                    $result = mysqli_query($open, $sql);
                                    if (!$result) {
                                        $erro = mysqli_error($open);
                                        throw new $erro;
                                    } else {
                                        if (!$result) {
                                            $erro = mysqli_error($open);
                                            throw new $erro;
                                        } else {
                                            return 0;
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo ($e->getMessage());
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
                $con->disconnect($open);
}

function atualizarApostaAdiada($idAposta, $idPartida)
{
                $con =  new conexao();
                $open = $con->connect();
                try {
                    $sql = "UPDATE tb_aposta a
                       SET a.resultado = 'Adiado'
                     WHERE a.id_aposta = $idAposta 
                       AND a.id_partida = " . $idPartida;
                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        return 'Adiado';
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
                $con->disconnect($open);
}

function atualizarAposta($idAposta)
{
                $con =  new conexao();
                $open = $con->connect();
                try {
                    $sql = "     SELECT p.resultado, a.aposta, p.id_partida
                      FROM tb_aposta a 
                 LEFT JOIN tb_partidas p ON a.id_partida = p.id_partida 
                 WHERE a.id_aposta = $idAposta 
                ";
                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            try {
                                $statusAposta = validaAposta($row['id_partida'], $row["aposta"]) ? 'Ganhou!' : 'Perdeu';
                                $sql = "UPDATE tb_aposta a
                               SET a.resultado = '$statusAposta'
                             WHERE a.id_aposta = $idAposta 
                               AND a.id_partida = " . $row['id_partida'];
                                $result = mysqli_query($open, $sql);
                                if (!$result) {
                                    $erro = mysqli_error($open);
                                    throw new $erro;
                                } else {
                                    if (!$result) {
                                        $erro = mysqli_error($open);
                                        throw new $erro;
                                    } else {
                                        return $statusAposta;;
                                    }
                                }
                            } catch (Exception $e) {
                                echo ($e->getMessage());
                            }
                        }
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
                $con->disconnect($open);
}

function validaAposta($idPartida, $aposta)
{
                $vencedor = false;

                $con =  new conexao();
                $open = $con->connect();
                try {
                    $sql = "    SELECT r.*, o.*
                      FROM tb_results r 
                 LEFT JOIN tb_odds o ON r.id_partida = o.id_partida 
                     WHERE r.id_partida = $idPartida 
                ";
                    $result = mysqli_query($open, $sql);
                    if (!$result) {
                        $erro = mysqli_error($open);
                        throw new $erro;
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            switch ($aposta) {
                                case "V1":
                                    $vencedor = $row["fulltime_home"] > $row["fulltime_away"];
                                    break;
                                case "DD":
                                    $vencedor = $row["fulltime_home"] == $row["fulltime_away"];
                                    break;
                                case "V2":
                                    $vencedor = $row["fulltime_home"] < $row["fulltime_away"];
                                    break;
                                case "12V1":
                                    break;
                                case "12V2":
                                    break;
                                case "13MG":
                                    $vencedor = $row["fulltime_home"] + $row["fulltime_away"] > $row["handicap_13"];
                                    break;
                                case "13LG":
                                    $vencedor = $row["fulltime_home"] + $row["fulltime_away"] < $row["handicap_13"];
                                    break;
                                case "14MC":
                                    $vencedor = $row["fulltime_corners"] > $row["handicap_14"];
                                    break;
                                case "14LC":
                                    $vencedor = $row["fulltime_corners"] < $row["handicap_14"];
                                    break;
                                case "15V1":
                                    break;
                                case "15V2":
                                    break;
                                case "16MG":
                                    $vencedor = $row["first_half_home"] + $row["first_half_away"] > $row["handicap_16"];
                                    break;
                                case "16LG":
                                    $vencedor = $row["first_half_home"] + $row["first_half_away"] < $row["handicap_16"];
                                    break;
                                case "17MC":
                                    $vencedor = $row["first_half_corners"] > $row["handicap_17"];
                                    break;
                                case "17LC":
                                    $vencedor = $row["first_half_corners"] < $row["handicap_17"];
                                    break;
                                case "18V1":
                                    $vencedor = $row["first_half_home"] > $row["first_half_away"];
                                    break;
                                case "18DD":
                                    $vencedor = $row["first_half_home"] == $row["first_half_away"];
                                    break;
                                case "18V2":
                                    $vencedor = $row["first_half_home"] < $row["first_half_away"];
                                    break;
                            }
                        }
                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
                $con->disconnect($open);

                return $vencedor;
}

function atualizarMultiplicador($idTicket)
{
    $con =  new conexao();
    $open = $con->connect();
    try {
        $sql = "SELECT t.valor_multiplicador, a.aposta, a.num_multiplicador,
                    12V1, 12V2,
                    13MG, 13LG,
                    14MC, 14LC,
                    15V1, 15DD, 15V2,
                    16MG, 16LG,
                    17MC, 17LC,
                    18V1, 18DD, 18V2, 
                    V1, DD, V2
            FROM tb_ticket t
            LEFT JOIN  tb_ticket_aposta ta on t.id_ticket = ta.id_ticket 
            RIGHT JOIN tb_aposta a on a.id_aposta = ta.id_aposta
            LEFT JOIN tb_partidas p on p.id_partida = a.id_partida 
            LEFT JOIN  tb_odds o on a.id_partida = o.id_partida 
            WHERE t.id_ticket = $idTicket 
    ";
        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $col) {
                    $col_array[$key] = utf8_encode($col);
                }
                $ticket[] =  $col_array;
            }
            $novoMult = 1;
            foreach ($ticket as $aposta) {
                $novoMult = $novoMult * $aposta[$aposta["aposta"]];
            }
            $novoMult = number_format($novoMult, '2');
            if ($novoMult != $ticket[0]["valor_multiplicador"]) {
                return $novoMult;
            } else {
                return "0";
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
    $con->disconnect($open);
}

function buscaTicketBD($id_ticket)
{    
    if ($id_ticket != 0) {
        $r = atualizarTicket($id_ticket);
    }

    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT t.*, ta.id_aposta, a.aposta, a.resultado, a.num_multiplicador, p.id_partida, p.datahora_partida, p.id_campeonato, p.resultado AS resultado_partida, c.nome_campeonato,  
                       id_time_casa, t_casa.nome_time as nome_time_casa, t_casa.id_pais_time as pais_time_casa, t_casa.url_logo_time as logo_time_casa, p.V1,
                       id_time_visitante, t_visitante.nome_time as nome_time_vis, t_visitante.id_pais_time as pais_time_vis, t_visitante.url_logo_time as logo_time_vis ,p.V2, p.DD, 
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

        FROM tb_ticket t
        LEFT JOIN  tb_ticket_aposta ta on t.id_ticket = ta.id_ticket 
        RIGHT JOIN tb_aposta a on a.id_aposta = ta.id_aposta
        INNER JOIN tb_partidas p on a.id_partida = p.id_partida
        LEFT JOIN  tb_odds o on p.id_partida = o.id_partida
        INNER JOIN tb_time t_casa ON p.id_time_casa = t_casa.id_time
        INNER JOIN tb_time t_visitante ON p.id_time_visitante = t_visitante.id_time
        INNER JOIN tb_campeonato c ON p.id_campeonato = c.id_campeonato";

        if ($id_ticket != 0) {
            $sql = $sql . " where t.id_ticket = $id_ticket ";
        }

        $sql = $sql . " order by id_partida ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_object()) {
                    foreach ($row as $key => $col) {
                        $col_array[$key] = $col;
                    }
                    $row_array[] =  $col_array;
                }

                $partidasTicket = $row_array;
                // echo "TT";
            } else {
                $partidasTicket = [];
            }
        }
    }
    catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);

    return $partidasTicket;
}