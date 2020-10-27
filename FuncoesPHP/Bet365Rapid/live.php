<?php
//set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("../../DAO/conexao.php");
include_once("../Bet365Rapid/callAPI.php");
header('Content-Type: text/html; charset=utf-8');

$apis = array(
    array(
        "NomeAPI"          =>          "Bet365 Inplay",
        "URL"              =>          "https://bet365-scoccer-odds.p.rapidapi.com/v1/events/inplay?sport_id=1",
        "x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
        "token"            =>          "37146-av3VER3rXx03ms"
    ),
);

function validarInserirAtualizarPartidas($fixtureId, $leagueId, $eventDate, $teamId1, $teamId2, $odd1, $odd2, $odd3, $statusShort, $UpdatedAt, $IdPartidaB365)
{
    $statusShort = str_replace("'", "", $statusShort);

    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "CALL ValidarInserirAtualizarPartidas($fixtureId, $leagueId, '$eventDate', $teamId1, $teamId2, '$odd1', '$odd2', '$odd3', '$statusShort', '$UpdatedAt', $IdPartidaB365);";
        $result = mysqli_query($open, $sql);
        echo ($result);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

$pagina = 1;
$fim = false;
$count = 0;

do {
$get_data1 = callAPI(
    'GET',
    $apis[0]['URL'] . '?LNG_ID=1&sport_id=1&page=' . $pagina ,
    false,
    'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']
);

$response1 = json_decode($get_data1, true);
//  echo "<br/><br/>response1:<br/><br/>" . $response1["success"];
//  print_r($apis[0]['URL'] . '?sport_id=1&token=' . $apis[0]['token']);
//  print_r($response1);

if ($response1["success"] == '0') {
    $fim = true;
    print_r(" \nError: " . $response1["error"] . "\nError_detail: " . $response1["error_detail"]);
} else {
    foreach ($response1["results"] as $value) {
        print_r("\n");
        print_r($value);
        // $get_dataBetsAPI = callAPI('GET', $apis[2]['URL'] . '?token=' . $apis[2]['token'] . '&event_id=' . $value["our_event_id"], false, null);
        // $responseBetsAPI = json_decode($get_dataBetsAPI, true);

        //  if ($responseBetsAPI["success"] == 1) {

            // $IdPartida = "";
            // $IdCampeonato = "";
            // $DataHoraPartida = "";
            // $IdTimeCasa = "";
            // $IdTimeVisitante = "";
            // $V1Parameter = "";
            // $V2Parameter = "";
            // $DDParameter = "";
            // $ResultadoParameter = "";

            // $IdPartida     = $value["id"];

            // $IdPartidaB365 = isset($value["bet365_id"]) ? $value["bet365_id"] : 0;
            // $txtFixtureId = $IdPartidaB365;

            // $IdCampeonato = $value["league"]["id"];
            // $DataHoraPartida = date("Y/m/d H:i:s", $value["time"]);
            // $IdTimeCasa = $value["home"]["id"];
            // $IdTimeVisitante = $value["away"]["id"];
            // // Tratar Significado
            // $ResultadoParameter = $value["time_status"];
            
            // $V1Parameter = 0;
            // $DDParameter = 0;
            // $V2Parameter = 0;

            // $UpdatedAt = date("Y/m/d H:i:s", 0);

            // // echo "league_id: ". $IdCampeonato . "<br/>";
            // // echo "id: ". $IdPartida . "<br/>";
            // // echo "IdTimeCasa: ". $IdTimeCasa . "<br/>";
            // // echo "IdTimeVisitante: ". $IdTimeVisitante . "<br/>";
            // // echo "DataHoraPartida: ". $DataHoraPartida . "<br/>";
            // // echo "ResultadoParameter: ". $ResultadoParameter . "<br/>";
            // // echo "V1Parameter: ". $V1Parameter . "<br/>";
            // // echo "DDParameter: ". $DDParameter . "<br/>";
            // // echo "V2Parameter: ". $V2Parameter . "<br/>";

            // if ($IdPartida == "" || $IdCampeonato == "" || $DataHoraPartida == "" || 
            //     $IdTimeCasa == "" || $IdTimeVisitante == "" || $ResultadoParameter == "") 
            //     {
            //     $count++;
            // } else {
            //     ValidarInserirAtualizarPartidas($IdPartida, $IdCampeonato, $DataHoraPartida, $IdTimeCasa, $IdTimeVisitante, $V1Parameter, $V2Parameter, $DDParameter, $ResultadoParameter, $UpdatedAt, $IdPartidaB365);
            // }
            $count++;
        }
    // }

    if ($response1["pager"]["total"] < $pagina * 50) {
        $fim = true;
    } else {
        $pagina++;
    }
}
} while ($fim == false);

echo "Partidas ao vivo: " . $count;
