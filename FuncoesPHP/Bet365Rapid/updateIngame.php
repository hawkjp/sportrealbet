<?php
//set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("../../DAO/conexao.php");
include_once("../Bet365Rapid/callAPI.php");
header('Content-Type: text/html; charset=utf-8');

$con =  new conexao();
$open = $con->connect();

$count = 0;

try {
    $sql = "SELECT id_partida FROM tb_partidas
                 WHERE datahora_partida < CURRENT_TIMESTAMP + INTERVAL 15 MINUTE
                   AND resultado = '0'  ";

    $result = mysqli_query($open, $sql);
    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        if (mysqli_num_rows($result) > 0) {
            $sqlUpdate = "UPDATE  tb_partidas 
                             SET  resultado = '1' 
                           WHERE  id_partida IN (";
            while ($row = $result->fetch_assoc()) {
                $IdPartida = $row["id_partida"];
                $sqlUpdate = $sqlUpdate . '"' . $IdPartida . '", ';
                $count++;
            }
            $sqlUpdate = substr($sqlUpdate, 0, -2) . ')';

            $resultUpdate = mysqli_query($open, $sqlUpdate);
            if (!$resultUpdate) {
                $erro = mysqli_error($open);
                throw new $erro;
            } else {
                echo ("Partidas em andamento: " . $count);
            }
        } else {
            echo ("Não houveram partidas atualizadas");
        }
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}

$con->disconnect($open);
