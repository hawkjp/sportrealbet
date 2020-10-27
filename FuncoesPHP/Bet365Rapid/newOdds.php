<?php
//set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("../../DAO/conexao.php");
include_once("../Bet365Rapid/callAPI.php");
header('Content-Type: text/html; charset=utf-8');

$apis = array(
    array(
        "NomeAPI"          =>          "Bet365 Odds",
        "URL"              =>          "https://bet365-scoccer-odds.p.rapidapi.com/v2/event/odds",
        "x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
        "token"            =>          "37146-av3VER3rXx03ms"
    ),
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
             WHERE V2 = 0
               AND DD = 0
               AND V1 = 0
               AND resultado IN ('0') ";

    $result = mysqli_query($open, $sql);
    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        $count++;
        while ($row = $result->fetch_assoc()) {
            $get_data4 = callAPI(
                'GET',
                $apis[0]['URL'] . '?event_id=' . $row["id_partida"],
                false,
                'x-rapidapi-key:' . $apis[0]['x-rapidapi-key']
            );
            $response4 = json_decode($get_data4, true);

            $IdPartida = $row["id_partida"];

            if ($response4["success"] == "1" && end($response4["results"]["odds"]) != null) {
                if (isset($response4["results"]["odds"]["1_1"])) {
                    $x = array_pop(array_reverse($response4["results"]["odds"]["1_1"]));

                    $V1Parameter = number_format(validaOdds($x["home_od"]), 2, '.', '');
                    $DDParameter = number_format(validaOdds($x["draw_od"]), 2, '.', '');
                    $V2Parameter = number_format(validaOdds($x["away_od"]), 2, '.', '');

                    $UpdatedAt = date("Y/m/d H:i:s", $x["add_time"]);

                    if ($V1Parameter == '' || $DDParameter == '' || $V2Parameter == '') {
                        echo (" IdPartida = " . $IdPartida . "<br />");
                    } else {
                        $con2 =  new conexao();
                        $open2 = $con2->connect();

                        $sqlUpdate = "UPDATE tb_partidas
                                SET
                                 V1 = $V1Parameter
                                ,V2 = $V2Parameter
                                ,DD = $DDParameter
                                ,updated_at = '$UpdatedAt'
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
        }
    }
    print_r(" Odds criadas = " . $count . "<br />");
} catch (Exception $e) {
    echo ($e->getMessage());
}

$con->disconnect($open);


function validaOdds($odd){
    if($odd <= 1.03){
        return 1.01;
    } else {
        if($odd > 1.03 && $odd <= 1.8){
            return $odd - 0.02;
        }
        else{
            if($odd > 1.8 && $odd <= 4.5){
                return $odd - 0.1;
            }
            else{
                return $odd * 0.95;
            }
        }
    }
}
