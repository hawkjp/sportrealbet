<?php
//set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("../../DAO/conexao.php");
header('Content-Type: text/html; charset=utf-8');

$con =  new conexao();
$open = $con->connect();

$count = 0;
$TicketsAprovado = [];
$TicketsPendentes = [];

try {
    $sql = "SELECT id_ticket, status_ticket FROM tb_ticket
                 WHERE status_ticket IN ('Aprovado', 'Pendente pagamento')";

    $result = mysqli_query($open, $sql);
    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                if($row["status_ticket"] == 'Pendente pagamento'){
                    $TicketsPendentes[$count] = $row["id_ticket"];
                } else {
                    $TicketsAprovado[$count] = $row["id_ticket"];
                }
                $count++;
            }
        } else {
            echo ("Não houveram tickets atualizados");
        }
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}

$con->disconnect($open);

foreach($TicketsAprovado as $Ticket){
    atualizarTicket($Ticket);
}

foreach($TicketsPendentes as $Ticket){
    verificarAprovacao($Ticket);
}

echo json_encode($TicketsAprovado);


function verificarAprovacao($id_ticket)
{
    $con =  new conexao();
    $open = $con->connect();

    // Verifica se jogos ja estao em andamento ou adiados
    try {
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
                cancelarTicket($id_ticket);
                $con->disconnect($open);
                return "<div class='alert alert-danger'><b>Aposta com partida em andamento ou adiado, ticket #$id_ticket cancelado</b></div>";
            } else {
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
}

function cancelarTicket($id_ticket)
{
    $funcionario = 99999999999;

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
                        }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
            }
            else{
                throw new Exception("<div class='alert alert-danger'>Erro ao cancelar o ticket!</div>");
            }
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
    $con->disconnect($open);

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
                        }
                        else{
                            if($row["resultado_partida"] == '4'){
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
                        if(in_array("Perdeu", $resultados)){
                            $statusTicketNov = 'Perdeu';
                        }
                        else{
                            if(!in_array("Ganhou!", $resultados)){
                                $statusTicketNov = 'Adiado';
                            }
                            else{
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