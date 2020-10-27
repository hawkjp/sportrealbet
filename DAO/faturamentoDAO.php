<?php

include_once("conexao.php");
include_once("../FuncoesPHP/sessao.php");

$acao = $_POST['acao'];
if (isset($_POST['dias'])) {
    $dias = $_POST['dias'];
} else {
    $dias = 0;
}

if (isset($_POST['semanas'])) {
    $semanas = $_POST['semanas'];
} else {
    $semanas = 0;
}


if (isset($_SESSION['cod_usuario'])) {
    $id_user = $_SESSION['cod_usuario'];
} else {
    $id_user = 0;
}

switch ($acao) {
    case "BUSCA":
        buscaFaturamento($dias);
        break;
    case "BUSCA_PRACA":
        buscaFaturamentoPorPraca();
        break;
    case "BUSCA_GERENTE":
        buscaFaturamentoFuncionario($semanas);
        break;
    case "BUSCA_FUNCIONARIO":
        buscaFaturamentoFuncionario($semanas);
        break;
        // case "BUSCA_FUNCIONARIO_SEMANA":
        //     buscaFaturamentoFuncionario($semanas);
        //     break;

    case "BUSCA_DATAS":
        getDatas();
        break;
}

function buscaFaturamento($dias)
{
    $con =  new conexao();
    $open = $con->connect();
    try {
        $sql = "SELECT id_funcionario, status_ticket, SUM(t.valor_apostado) AS valor_apostado, premio
                FROM tb_ticket t 
                WHERE t.datahora_efetivacao_pagamento > DATE(NOW()) - INTERVAL $dias DAY  ";

        if (pegaPerfil() == 3) {
            $sql = $sql . " AND id_funcionario = " . pegaId();
        }
        if (pegaPerfil() == 4) {
            $sql = $sql . " AND id_funcionario IN ( SELECT cod_funcionario FROM tb_funcionario WHERE cod_gerente = " . pegaId() . ")";
        }

        $sql = $sql . " GROUP BY status_ticket, id_funcionario ORDER BY id_funcionario ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            $valorRecebido =  0.0;
            $valorPendenteJogos =  0.0;
            $valorPendenteApostas =  0.0;
            $valorPago =  0.0;
            $valorPremiado =  0.0;
            $valorCaixa =  0.0;
            while ($row = $result->fetch_assoc()) {
                // foreach ($row as $key => $col) {
                //     $col_array[$key] = utf8_encode($col);
                // }
                // $row_array[] =  $col_array;

                switch ($row["status_ticket"]) {
                    case 'Premiado!':
                        $valorPago += $row["premio"] * 0.9;
                        $valorRecebido += $row["valor_apostado"];
                        $valorPremiado += $row["premio"] * 0.1;
                        break;
                    case 'Perdeu':
                        $valorRecebido += $row["valor_apostado"];
                        break;
                    case 'Aprovado':
                        $valorPendenteJogos += $row["valor_apostado"];
                        $valorRecebido      += $row["valor_apostado"];
                        break;
                    case 'Pendente pagamento':
                        $valorPendenteApostas += $row["valor_apostado"];
                        break;
                }
            }
            // echo json_encode($row_array);
            $valorCaixa = $valorRecebido - $valorPago - $valorPremiado
?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-deck">
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <span style="font-weight: bold;">Valor recebido</span>
                            </div>
                            <div class="card-body">
                                R$ <?php echo number_format($valorRecebido, 2, ',', ''); ?>
                            </div>
                        </div>
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <span style="font-weight: bold;">Valor pago</span>
                            </div>
                            <div class="card-body">
                                R$ <?php echo number_format($valorPago, 2, ',', ''); ?>
                            </div>
                        </div>
                        <?php
                        if (pegaPerfil() == 5) {
                        ?>
                            <div class="card mb-4 border-success">
                                <div class="card-header bg-success text-white">
                                    <span style="font-weight: bold;">Apostas pendentes de pagamento</span>
                                </div>
                                <div class="card-body">
                                    R$ <?php echo number_format($valorPendenteApostas, 2, ',', ''); ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <span style="font-weight: bold;">Apostas em andamento</span>
                            </div>
                            <div class="card-body">
                                R$ <?php echo number_format($valorPendenteJogos, 2, ',', '');  ?>
                            </div>
                        </div>
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <span style="font-weight: bold;">Caixa</span>
                            </div>
                            <div class="card-body">
                                R$ <?php echo number_format($valorCaixa, 2, ',', ''); ?>
                            </div>
                        </div>
                        <?php
                        if (pegaPerfil() == 3 || pegaPerfil() == 4) {
                        ?>
                            <div class="card mb-4 border-success">
                                <div class="card-header bg-success text-white">
                                    <span style="font-weight: bold;">Bonus apostas premiadas</span>
                                </div>
                                <div class="card-body">
                                    R$ <?php echo number_format($valorPremiado, 2, ',', ''); ?>
                                </div>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
}

function buscaFaturamentoPorPraca()
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT f.*, SUM(t.valor_apostado) as total_praca
                FROM tb_ticket t 
                RIGHT JOIN tb_funcionario f ON f.cod_funcionario = t.id_funcionario
                WHERE t.datahora_efetivacao_pagamento > DATE(NOW()) - INTERVAL 30 DAY 
                GROUP BY nome_praca ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            $valorRecebido =  0.0;
            $valorPendenteJogos =  0.0;
            $valorPendenteApostas =  0.0;
            $valorPago =  0.0;
            //while ($row = $result->fetch_assoc()) {

            while ($row = $result->fetch_object()) {
                foreach ($row as $key => $col) {
                    $col_array[$key] = utf8_encode($col);
                }
                $row_array[] =  $col_array;
                // $faturamentoArray = array(
                //     'funcionario' => array(
                //         'idTimeVisitante'   => $partidaArray['id_time_visitante'],
                //         'nomeTimeVisitante' => $partidaArray['nome_time_vis']
                //     ),
                //     'valorRecebido'         => 0.0,
                //     'valorPendenteJogos'    => 0.0,
                //     'valorPendenteApostas'  => 0.0,
                //     'valorPago'             => 0.0,
                //     'valorPremiado'         => 0.0,
                //     'valorCaixa'            => 0.0
                // );

                // switch ($row["status_ticket"]) {
                //     case 'Premiado!':
                //         $valorPago += $row["valor_apostado"] * $row["valor_multiplicador"];
                //         $valorRecebido += $row["valor_apostado"];
                //         break;
                //     case 'Perdeu':
                //         $valorRecebido += $row["valor_apostado"];
                //         break;
                //     case 'Aprovado':
                //         $valorPendenteJogos += $row["valor_apostado"];
                //         $valorRecebido += $row["valor_apostado"];
                //         break;
                //     case 'Pendente pagamento':
                //         $valorPendenteApostas += $row["valor_apostado"];
                //         break;
                // }
            }
            echo json_encode($row_array);
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
}

function buscaFaturamentoFuncionario($semanas)
{
    
    $porcentagemGerente = 30;
    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "SELECT id_funcionario, COUNT(t.status_ticket) as tickets, status_ticket, SUM(t.valor_apostado) AS valor_apostado, premio, u.first_name, u.last_name, u.username, f.porcentagem
                FROM tb_ticket t 
                INNER JOIN tb_usuario u on t.id_funcionario = u.cod_usuario 
                LEFT JOIN tb_funcionario f ON f.cod_usuario = u.cod_usuario 
                WHERE YEARWEEK(t.datahora_efetivacao_pagamento, 1) = YEARWEEK(CURDATE() - INTERVAL $semanas WEEK, 1)";

        if (pegaPerfil() == 3) {
            $sql = $sql . " AND id_funcionario = " . pegaId();
        }
        if (pegaPerfil() == 4) {
            $sql = $sql . " AND (id_funcionario IN ( SELECT cod_usuario FROM tb_funcionario WHERE cod_gerente = " . pegaId() . ") OR id_funcionario = " . pegaId() . ")";
        }

        $sql = $sql . " GROUP BY status_ticket, id_funcionario ORDER BY id_funcionario ";

        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                $faturamentoArray = [];
                while ($row = $result->fetch_assoc()) {
                    $id = $row["id_funcionario"];
                    if (!(array_key_exists($id, $faturamentoArray))) {
                        $faturamentoIniciaArray = array(
                            'funcionario' => array(
                                'codFuncionario'    => $row['id_funcionario'],
                                'username'          => $row['username'],
                                'primeiroNome'      => $row['first_name'],
                                'ultimoNome'        => $row['last_name'],
                                'porcentagem'       => $row['porcentagem'],
                            ),
                            'qtdTickets'            => 0,
                            'valorRecebido'         => 0.0,
                            'valorPendenteJogos'    => 0.0,
                            'valorPendenteApostas'  => 0.0,
                            'valorPago'             => 0.0,
                            'valorPremiado'         => 0.0,
                            'valorCaixa'            => 0.0
                        );
                        // echo json_encode($faturamentoIniciaArray);
                        $faturamentoArray[$row['id_funcionario']] = $faturamentoIniciaArray;
                    }
                    
                    $faturamentoArray[$row['id_funcionario']]['qtdTickets'] += $row['tickets'];
                    switch ($row["status_ticket"]) {
                        case 'Premiado!':
                            $faturamentoArray[$row['id_funcionario']]['valorPago'] += $row["premio"] * 0.9;
                            $faturamentoArray[$row['id_funcionario']]['valorRecebido'] += $row["valor_apostado"];
                            $faturamentoArray[$row['id_funcionario']]['valorPremiado'] += $row["premio"] * 0.1;
                            break;
                        case 'Perdeu':
                            $faturamentoArray[$row['id_funcionario']]['valorRecebido'] += $row["valor_apostado"];
                            break;
                        case 'Aprovado':
                            $faturamentoArray[$row['id_funcionario']]['valorPendenteJogos'] += $row["valor_apostado"];
                            $faturamentoArray[$row['id_funcionario']]['valorRecebido']      += $row["valor_apostado"];
                            break;
                        case 'Pendente pagamento':
                            $faturamentoArray[$row['id_funcionario']]['valorPendenteApostas'] += $row["valor_apostado"];
                            break;
                    }
                }
                foreach ($faturamentoArray as $funcionario) {

                    $caixa = $funcionario['valorRecebido'];
                    $caixaPendente = $funcionario['valorPendenteJogos'];
                    $pagoCliente = $funcionario['valorPago'];
                    $caixaAtual = $caixa - ($pagoCliente + $funcionario['valorPremiado']);
                    if(!(is_null($funcionario["funcionario"]['porcentagem']))){
                        $pagoFuncionario = $caixaAtual * (($funcionario["funcionario"]['porcentagem']) / 100) + $funcionario['valorPremiado'];
                    } else {
                        $pagoFuncionario = 0;
                    }
                    $pagoGerente = $caixaAtual * (($porcentagemGerente - $funcionario["funcionario"]['porcentagem']) / 100);
                    $caixaCasa = $caixa - $pagoCliente - $pagoFuncionario - $pagoGerente;
            ?>
                    <div class="col-lg-12">
                        <div class="card  mb-3">
                            <div class="card-header justify-content-center">
                                <div class="row">
                                    <div class="col-sm-3 col-12 mb-2">
                                        <span data-toggle="collapse" href="#tc<?php echo $funcionario["cod_usuario"]; ?>" aria-expanded="false" aria-controls="tc<?php echo  $row["cod_usuario"]; ?>" id="fun<?php echo $row["cod_usuario"]; ?>" class="d-block cfm">
                                            <i class="fa fa-user pull-right"></i>
                                            <?php echo ' ' .  $funcionario["funcionario"]["username"]; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                                    
                            <div class="card-body justify-content-center">
                                <div class="row">
                                    
                                        <div class='container'>
                                        <?php if(!(is_null($funcionario["funcionario"]['porcentagem'])) && pegaPerfil() > 3 ){ ?>   
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-fill bd-highlight ">
                                                    Porcentagem: <?php echo $funcionario["funcionario"]['porcentagem']; ?> %
                                                </div>
                                            </div>
                                        <?php } ?>   
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-fill bd-highlight ">
                                                    Caixa: R$ <?php echo number_format($caixa, 2, ',', ''); ?> 
                                                    <br />
                                                    Caixa pendente: R$<?php echo number_format($caixaPendente, 2, ',', ''); ?>
                                                </div>
                                            </div>
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-fill bd-highlight ">
                                                    Saidas: R$ <?php echo number_format(($pagoCliente), 2, ',', ''); ?>
                                                </div>
                                            </div>
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-fill bd-highlight ">
                                        <?php if(!(is_null($funcionario["funcionario"]['porcentagem']))){ ?>   
                                                    Cambista: R$ <?php echo number_format($pagoFuncionario, 2, ',', ''); ?>   
                                                    <br />                    
                                        <?php } ?>   
                                                    Gerente: R$ <?php echo number_format($pagoGerente, 2, ',', ''); ?>
                                                    <br />
                                                    Casa: R$ <?php echo number_format($caixaCasa, 2, ',', ''); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
<?php
                }
            } else {
                throw new Exception("<div class='alert alert-danger'>Nao houve movimento essa semana!</div>");
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
    $con->disconnect($open);
}

function buscaFaturamentoGrafico()
{
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT f.cod_funcionario, f.cod_gerente, SUM(t.valor_apostado) as total_recebido, DAY(datahora_efetivacao_pagamento) 
        FROM tb_ticket t 
        RIGHT JOIN tb_funcionario f ON f.cod_funcionario = t.id_funcionario
        INNER JOIN tb_gerente g ON g.cod_gerente = f.cod_gerente 
        WHERE t.datahora_efetivacao_pagamento > DATE(NOW()) - INTERVAL 30 DAY 
        GROUP BY f.cod_gerente, DAY(datahora_efetivacao_pagamento)
        ORDER BY datahora_efetivacao_pagamento ";

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

function getDatas()
{
    $ArrayDatas = [];
    for ($i = 0; $i <= 5; $i++) {    
        $semana_atual = strtotime('+' . (date('W') - 1) - $i . ' weeks', strtotime(date('Y') . '0101'));
        // $dia_semana = 1;
        $dia_semana = date('w');
        $data_inicio_semana = strtotime('-' . $dia_semana + 2 . ' days', $semana_atual);
        $primeiro_dia_semana = date('d-m-Y', $data_inicio_semana);
        $ultimo_dia_semana = date('d-m-Y', strtotime('+6 days', strtotime($primeiro_dia_semana)));
        $dataArray = array(
            'nSemana'      => $i,
            'inicioSemana' => $primeiro_dia_semana,
            'finalSemana'  => $ultimo_dia_semana,
            'dia_semana'   => $dia_semana
        );
        $ArrayDatas[$i] = $dataArray;
    }
    
    foreach ($ArrayDatas as $semana) {
        ?>
            <option value=<?php echo ' ' .  $semana["nSemana"]; ?> selected><?php echo  $semana["inicioSemana"] . ' | ' .  $semana["finalSemana"]; ?></option>
        <?php
    }
    // return $ArrayDatas;
    // echo json_encode($ArrayDatas);
}
?>