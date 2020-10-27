<?php
set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("../../DAO/conexao.php");
include_once("../Bet365Rapid/callAPI.php");
header('Content-Type: text/html; charset=utf-8');

$apis = array(
    array(
        "NomeAPI"          =>          "Bet365 events",
        "URL"              =>          "https://bet365-scoccer-odds.p.rapidapi.com/v1/event/view",
        "x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
        "token"            =>          "37146-av3VER3rXx03ms"
    ),
);

$con =  new conexao();
$open = $con->connect();

$count = 0;

try {

    $sql = "SELECT id_partida FROM tb_partidas
            -- WHERE resultado = '1' LIMIT 1000
             WHERE id_partida = 2598447";

    $result = mysqli_query($open, $sql);
    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        $count++;
        while ($row = $result->fetch_assoc()) {

            $count++;
            $get_data4 = callAPI('GET', $apis[0]['URL'] . '?event_id=' . $row["id_partida"], false, 'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']);
            $response4 = json_decode($get_data4, true);

            $IdPartida = $row["id_partida"];

            if ($response4["success"] == "1") {
                if ($response4["results"][0]["time_status"] == '3') {
                    $placar = explode("-", $response4["results"][0]["ss"]);

                    if ($placar[0] > $placar[1]) {
                        $resultado = "V1";
                    } else {
                        if ($placar[0] < $placar[1]) {
                            $resultado = "V2";
                        } else {
                            $resultado = "DD";
                        }
                    }

                    $con3 =  new conexao();
                    $open3 = $con3->connect();
                    print_r($response4["results"][0]["scores"]);

                    if(isset($response4["results"][0]["stats"]["corner_h"])){
                        $fhCorners = array_sum($response4["results"][0]["stats"]["corner_h"]);
                    }else{
                        $fhCorners = -1;
                    }

                    if(isset($response4["results"][0]["stats"]["corners"])){
                        $ftCorners = array_sum($response4["results"][0]["stats"]["corners"]);
                    }else{
                        $ftCorners = -1;
                    }

                    $UpdatedAt = date("Y/m/d H:i:s", $response4["results"][0]["time"]);
                    $sqlInsert = "INSERT INTO tb_results(id_partida, first_half_home, first_half_away, first_half_corners, fulltime_home, fulltime_away, fulltime_corners, updated_at)
                                     VALUES (" . $IdPartida . ", " .
                                                 $response4["results"][0]["scores"]["1"]["home"] . ", " .
                                                 $response4["results"][0]["scores"]["1"]["away"] . ", " .
                                                 $fhCorners . ", " .
                                                 $response4["results"][0]["scores"]["2"]["home"] . ", " .
                                                 $response4["results"][0]["scores"]["2"]["away"] . ", " .
                                                 $fhCorners . ", " .
                                                 " '" . $UpdatedAt . "') ";

                    $resultInsert = mysqli_query($open3, $sqlInsert);
                    if (!$resultInsert) {
                        $erro = mysqli_error($open3);
                        throw new $erro;
                    } else {
                        $count++;
                    }
                    $con3->disconnect($open3);
                } else {
                    $resultado = $response4["results"][0]["time_status"];
                }

                $con2 =  new conexao();
                $open2 = $con2->connect();

                $sqlUpdate = "UPDATE tb_partidas
                                         SET resultado = '$resultado'
                                       WHERE id_partida = $IdPartida";

                $resultUpdate = mysqli_query($open2, $sqlUpdate);
                if (!$resultUpdate) {
                    $erro = mysqli_error($open2);
                    throw new $erro;
                } else {
                    $count++;
                }
                $con2->disconnect($open2);
            }
        }
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}

$con->disconnect($open);
