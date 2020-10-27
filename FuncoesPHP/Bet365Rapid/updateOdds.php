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

    $sql = "    SELECT id_partida, V1, V2, DD, updated_at FROM tb_partidas
                 WHERE 
                       datahora_partida > CURRENT_TIMESTAMP 
                   AND datahora_partida < DATE(NOW()) + INTERVAL 7 DAY
                   AND V2 <> 0
                   AND DD <> 0
                   AND V1 <> 0 ";

    $result = mysqli_query($open, $sql);
    if (!$result) {
        $erro = mysqli_error($open);
        throw new $erro;
    } else {
        while ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $col) {
                $col_array[$key] = utf8_encode($col);
            }
            $partidas[] =  $col_array;
        }
    }
    echo (" Atualizados = " . $count . "<br />");
} catch (Exception $e) {
    echo ($e->getMessage());
}

echo ($count);
$con->disconnect($open);

foreach ($partidas as $partida) {

    $count++;

    $get_data4 = callAPI('GET', $apis[0]['URL'] . '?event_id=' . $partida["id_partida"], false, 'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']);
    $response4 = json_decode($get_data4, true);
    $idPartida = $partida["id_partida"];

    if ($response4["success"] == "1") {
        $response4 = $response4["results"]["odds"];
        if (isset($response4["1_1"])) {
            odds1_1(array_pop(array_reverse($response4["1_1"])), $partida);
        }

        $otherOdds = false;

        $sqlInsert = "INSERT INTO tb_odds( id_partida";
        $sqlValues = " VALUES (" . $idPartida;

        $sqlUpdate = "UPDATE tb_odds SET id_partida = " . $idPartida;

        if (isset($response4["1_2"]) && !empty($response4["1_2"])) {
            $odds1_2 = array_pop(array_reverse($response4["1_2"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_2["add_time"]);
            $sqlInsert = $sqlInsert . ", nome_12, 12V1, 12V2, updatedAt_12, handicap_12";
            $sqlValues = $sqlValues . ", 'Handicap Asiático " . $odds1_2["handicap"] .
                "', " . validaOdds($odds1_2["home_od"]) .
                ", " . validaOdds($odds1_2["away_od"]) .
                ", '" . $UpdatedAt .
                "', '" . $odds1_2["handicap"] . "'";

            $sqlUpdate = $sqlUpdate . ", nome_12 = 'Handicap Asiático " . $odds1_2["handicap"] . "', " .
                "12V1 = " . validaOdds($odds1_2["home_od"]) . ", " .
                "12V2 = " . validaOdds($odds1_2["away_od"]) . ", " .
                "updatedAt_12 = '" . $UpdatedAt . "', " .
                "handicap_12 = '" . $odds1_2["handicap"] . "'";
                
            $otherOdds = true;
        }
        if (isset($response4["1_3"]) && !empty($response4["1_3"])) {
            $odds1_3 = array_pop(array_reverse($response4["1_3"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_3["add_time"]);

            $sqlInsert = $sqlInsert . ", nome_13, 13MG, 13LG, updatedAt_13, handicap_13";
            $sqlValues = $sqlValues . ", 'Mais ou menos de " . $odds1_3["handicap"] .
                " gols', " . validaOdds($odds1_3["over_od"]) .
                ", " . validaOdds($odds1_3["under_od"]) .
                ", '" . $UpdatedAt .
                "', '" . $odds1_3["handicap"]  . "'";

            $sqlUpdate = $sqlUpdate . ", nome_13 = 'Mais ou menos de " . $odds1_3["handicap"] . " gols', " .
                "13MG = " . validaOdds($odds1_3["over_od"]) . ", " .
                "13LG = " . validaOdds($odds1_3["under_od"]) . ", " .
                "updatedAt_13 = '" . $UpdatedAt . "', " .
                "handicap_13 = '" . $odds1_3["handicap"] . "'";
                
                $otherOdds = true;
        }
        if (isset($response4["1_4"]) && !empty($response4["1_4"])) {
            $odds1_4 = array_pop(array_reverse($response4["1_4"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_4["add_time"]);
            $sqlInsert = $sqlInsert . ", nome_14, 14MC, 14LC, updatedAt_14, handicap_14";
            $sqlValues = $sqlValues . ", 'Mais ou menos de " . $odds1_4["handicap"] .
                " escanteios', " . validaOdds($odds1_4["over_od"]) .
                ", " . validaOdds($odds1_4["under_od"]) .
                ", '" . $UpdatedAt .
                "', '" . $odds1_4["handicap"]  . "'";

            $sqlUpdate = $sqlUpdate . ", nome_14 = 'Mais ou menos de " . $odds1_4["handicap"] . " escanteios', " .
                "14MC = " . validaOdds($odds1_4["over_od"]) . ", " .
                "14LC = " . validaOdds($odds1_4["under_od"]) . ", " .
                "updatedAt_14 = '" . $UpdatedAt . "', " .
                "handicap_14 = '" . $odds1_4["handicap"] . "'";
                
                $otherOdds = true;
        }
        if (isset($response4["1_5"]) && !empty($response4["1_5"])) {
            $odds1_5 = array_pop(array_reverse($response4["1_5"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_5["add_time"]);
            $sqlInsert = $sqlInsert . ", nome_15, 15V1, 15V2, updatedAt_15, handicap_15";
            $sqlValues = $sqlValues . ", 'Primeiro tempo - handicap asiático " . $odds1_5["handicap"] .
                "', " . validaOdds($odds1_5["home_od"]) .
                "," . validaOdds($odds1_5["away_od"]) .
                ", '" . $UpdatedAt .
                "', '" . $odds1_5["handicap"]  . "'";

            $sqlUpdate = $sqlUpdate . ", nome_15 = 'Primeiro tempo - handicap asiático " . $odds1_5["handicap"] . "', " .
                "15V1 = " . validaOdds($odds1_5["home_od"]) . ", " .
                "15V2 = " . validaOdds($odds1_5["away_od"]) . ", " .
                "updatedAt_15 = '" . $UpdatedAt . "', " .
                "handicap_15 = '" . $odds1_5["handicap"] . "'";
                
                $otherOdds = true;
        }
        if (isset($response4["1_6"]) && !empty($response4["1_6"])) {
            $odds1_6 = array_pop(array_reverse($response4["1_6"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_6["add_time"]);
            $sqlInsert = $sqlInsert . ", nome_16, 16MG, 16LG, updatedAt_16, handicap_16";
            $sqlValues = $sqlValues . ", 'Primeiro tempo - mais ou menos de " . $odds1_6["handicap"] .
                " gols', " . validaOdds($odds1_6["over_od"]) .
                ", " . validaOdds($odds1_6["under_od"]) .
                ", '" . $UpdatedAt .
                "', '" . $odds1_6["handicap"]  . "'";

            $sqlUpdate = $sqlUpdate . ", nome_16 = 'Primeiro tempo - mais ou menos de " . $odds1_6["handicap"] . " gols', " .
                "16MG = " . validaOdds($odds1_6["over_od"]) . ", " .
                "16LG = " . validaOdds($odds1_6["under_od"]) . ", " .
                "updatedAt_16 = '" . $UpdatedAt . "', " .
                "handicap_16 = '" . $odds1_6["handicap"] . "'";
                
                $otherOdds = true;
        }
        if (isset($response4["1_7"]) && !empty($response4["1_7"])) {
            $odds1_7 = array_pop(array_reverse($response4["1_7"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_7["add_time"]);
            $sqlInsert = $sqlInsert . ", nome_17, 17MC, 17LC, updatedAt_17, handicap_17";
            $sqlValues = $sqlValues . ", 'Primeiro tempo - mais ou menos de " . $odds1_7["handicap"] .
                " escanteios', " . validaOdds($odds1_7["over_od"]) .
                ", " . validaOdds($odds1_7["under_od"]) .
                ", '" . $UpdatedAt .
                "', '" . $odds1_7["handicap"]  . "'";

            $sqlUpdate = $sqlUpdate . ", nome_17 = 'Primeiro tempo - mais ou menos de " . $odds1_7["handicap"] . "', " .
                "17MC = " . validaOdds($odds1_7["over_od"]) . ", " .
                "17LC = " . validaOdds($odds1_7["under_od"]) . ", " .
                "updatedAt_17 = '" . $UpdatedAt . "', " .
                "handicap_17 = '" . $odds1_7["handicap"] . "'";
                
                $otherOdds = true;
        }
        if (isset($response4["1_8"]) && !empty($response4["1_8"])) {
            $odds1_8 = array_pop(array_reverse($response4["1_8"]));

            $UpdatedAt = date("Y/m/d H:i:s", $odds1_8["add_time"]);
            $sqlInsert = $sqlInsert . ", nome_18, 18V1, 18DD, 18V2, updatedAt_18";
            $sqlValues = $sqlValues . ", 'Primeiro tempo - Vencedor', " . validaOdds($odds1_8["home_od"]) .
                ", " . validaOdds($odds1_8["draw_od"]) .
                ", " . validaOdds($odds1_8["away_od"]) .
                ", '" . $UpdatedAt . "'";


            $sqlUpdate = $sqlUpdate . ", nome_18 = 'Primeiro tempo - Vencedor', " .
                "18V1 = " . validaOdds($odds1_8["home_od"]) . ", " .
                "18DD = " . validaOdds($odds1_8["draw_od"]) . ", " .
                "18V2 = " . validaOdds($odds1_8["away_od"]) . ", " .
                "updatedAt_18 = '" . $UpdatedAt . "'";
                
                $otherOdds = true;
        }

        $sqlInsert = $sqlInsert . ")";
        $sqlValues = $sqlValues . " )";

        $sqlUpdate = $sqlUpdate . " WHERE id_partida = " . $idPartida;

        // print_r(" 1 linha = " . $sqlOdds . "<br /><br />");
        // print_r(" 2 linha = " . $sqlValues . "<br /><br />");
        // print_r(" 3 linha = " . $sqlUpdate . "<br /><br />");

        if($otherOdds){
            $conOdds =  new conexao();
            $openOdds = $conOdds->connect();

            $sqlOdds = "SELECT * FROM  tb_odds
                       WHERE  id_partida = " . $idPartida;

            $resultOdds = mysqli_query($openOdds, $sqlOdds);
            if (!$resultOdds) {
                $erro = mysqli_error($openOdds);
                throw new $erro;
            } else {
                if (mysqli_num_rows($resultOdds) > 0) {
                    print_r(" Update <br /><br />");
                    $resultUpd = mysqli_query($openOdds, $sqlUpdate);
                    if (!$resultUpd) {
                        $erro = mysqli_error($openOdds);
                        throw new $erro;
                    } else {
                        print_r(" OK Update <br /><br />");
                    }
                } else {
                    print_r(" Insert <br /><br />");
                    $resultIns = mysqli_query($openOdds, $sqlInsert . $sqlValues);
                    if (!$resultIns) {
                        $erro = mysqli_error($openOdds);
                        throw new $erro;
                    } else {
                        print_r(" OK Insert <br /><br />");
                    }
                }
            }

            $conOdds->disconnect($openOdds);
        }
    } else {
        print_r(" IdPartida = " . $IdPartida . "<br />");
    }
}

function validaOdds($odd)
{
    if ($odd <= 1.03) {
        $odd = 1.01;
    } else {
        if ($odd > 1.03 && $odd <= 1.8) {
            $odd = $odd - 0.02;
        } else {
            if ($odd > 1.8 && $odd <= 4.5) {
                $odd = $odd - 0.1;
            } else {
                $odd = $odd * 0.95;
            }
        }
    }

    return number_format($odd, 2, '.', '');
}

function odds1_1($x, $row)
{
    print_r(" IdPartida = " . $row["id_partida"] . "<br />");

    $V1Parameter = validaOdds($x["home_od"]);
    $DDParameter = validaOdds($x["draw_od"]);
    $V2Parameter = validaOdds($x["away_od"]);

    if ($row["V1"] != $V1Parameter || $row["DD"] != $DDParameter || $row["V2"] != $V2Parameter) {

        $IdPartida = $row["id_partida"];
        $oldV1 = $row["V1"];
        $oldDD = $row["DD"];
        $oldV2 = $row["V2"];
        $oldUpdated = $row["updated_at"];

        $UpdatedAt = date("Y/m/d H:i:s", $x["add_time"]);
        if ($V1Parameter == '' || $DDParameter == '' || $V2Parameter == '') {
            print_r(" IdPartida = " . $IdPartida . "<br />");
        } else {

            $con2 =  new conexao();
            $open2 = $con2->connect();

            $sqlHistorico = "INSERT INTO tb_odds_hist(id_partida, V1, DD, V2, updated_at)
                                  VALUES ($IdPartida, $oldV1, $oldV2, $oldDD, '$oldUpdated')";

            $resultInsertHist = mysqli_query($open2, $sqlHistorico);
            if (!$resultInsertHist) {
                $erro = mysqli_error($open2);
                throw new $erro;
            } else {
                $con3 =  new conexao();
                $open3 = $con3->connect();
                $sqlUpdate = "UPDATE  tb_partidas
                                 SET  V1 = $V1Parameter
                                     ,V2 = $V2Parameter
                                     ,DD = $DDParameter
                                     ,updated_at = '$UpdatedAt'
                               WHERE  id_partida = $IdPartida";

                $resultUpdate = mysqli_query($open3, $sqlUpdate);
                if (!$resultUpdate) {
                    $erro = mysqli_error($open3);
                    throw new $erro;
                } else {
                }

                $con3->disconnect($open3);
            }
            $con2->disconnect($open2);
        }
    }
}
