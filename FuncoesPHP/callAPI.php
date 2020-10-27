<?php
set_time_limit(1200);
include_once("../DAO/conexao.php");
header('Content-Type: text/html; charset=utf-8');

/*
https://weichie.com/blog/curl-api-calls-with-php/
http://idealize.tecnologia.ws/BolaoDES/FuncoesPHP/callAPI.php
*/

//$paises = array("Brazil", "England", "Spanin");
$pais = "Brazil";
$txtLeagueId = "";
$txtFixtureId = "";
$txtLabel = "1";

$apis = array(
    array(
         "NomeAPI"          =>          "TeamsBySearchCountry"
        ,"URL"              =>          "https://api-football-v1.p.rapidapi.com/v2/teams/search/"
        ,"x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c"
    ),
    array(
        "NomeAPI"          =>          "LeaguesBySearchCountry"
       ,"URL"              =>          "https://api-football-v1.p.rapidapi.com/v2/leagues/current/"
       ,"x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c"
    ),
    array(
       "NomeAPI"          =>          "FixturesBySearchLeague"
      ,"URL"              =>          "https://api-football-v1.p.rapidapi.com/v2/fixtures/league/<LeagueId>"
      ,"x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c"
    ),
    array(
       "NomeAPI"          =>          "OddsBySearchFixtureAndLabel"
      ,"URL"              =>          "https://api-football-v1.p.rapidapi.com/v2/odds/fixture/<FixtureId>/label/<Label>"
      ,"x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c"
    )
);

function callAPI($method, $url, $data, $headers){
    $curl = curl_init();

    switch($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if($data){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }             
            break;

        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if($data){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }             			 					
            break;

        default:
            if($data){
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }             
    }

    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    if($headers){
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            $headers
        ));
    } else{
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
    }

   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){
        die("Connection Failure");
    }

    curl_close($curl);
    return $result;
}

function validarInserirAtualizarTimes($teamId, $name, $country, $logo, $venueName){
    $name = str_replace("'", "", $name);
    $country = str_replace("'", "", $country);
    $logo = str_replace("'", "", $logo);
    $venueName = str_replace("'", "", $venueName);

    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "CALL ValidarInserirAtualizarTimes($teamId, '$name', '$country', '$logo', '$venueName');";
        $result = mysqli_query($open, $sql);

        if(!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        }

    } catch (Exception $e){
        echo ($e->getMessage());
    }
    
    $con->disconnect($open);
}

function validarInserirAtualizarCampeonatos($leagueId, $name, $season, $logo, $standings, $seasonStart, $seasonEnd){
    $name = str_replace("'", "", $name);
    $logo = str_replace("'", "", $logo);

    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "CALL ValidarInserirAtualizarCampeonatos($leagueId, '$name', '$season', '$logo', $standings, '$seasonStart', '$seasonEnd');";
        $result = mysqli_query($open, $sql);

        if(!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        }
    } catch (Exception $e){
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function validarInserirAtualizarPartidas($fixtureId, $leagueId, $eventDate, $teamId1, $teamId2, $odd1, $odd2, $odd3, $statusShort){
    $statusShort = str_replace("'", "", $statusShort);

    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "CALL ValidarInserirAtualizarPartidas($fixtureId, $leagueId, '$eventDate', $teamId1, $teamId2, '$odd1', '$odd2', '$odd3', '$statusShort');";
        $result = mysqli_query($open, $sql);

        if(!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        }

    } catch (Exception $e){
        echo ($e->getMessage());
    }

    $con->disconnect($open);
}

function buscarCampeonatos(){
    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "SELECT id_campeonato FROM tb_campeonato ORDER BY id_campeonato ASC;";
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
        }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);
    return json_encode($row_array);
}

// GET
/*
$get_data = callAPI('GET', 'https://api.example.com/get_url/'.$user['User']['customer_id'], false);
$response = json_decode($get_data, true);
$errors = $response['response']['errors'];
$data = $response['response']['data'][0];
*/

// POST
/*
$data_array =  array(
    "customer"        => $user['User']['customer_id'],
    "payment"         => array(
          "number"         => $this->request->data['account'],
          "routing"        => $this->request->data['routing'],
          "method"         => $this->request->data['method']
    ),
);

$make_call = callAPI('POST', 'https://api.example.com/post_url/', json_encode($data_array));
$response = json_decode($make_call, true);
$errors   = $response['response']['errors'];
$data     = $response['response']['data'][0];
*/

// PUT
/*
$data_array =  array(
    "amount" => (string)($lease['amount'] / $tenant_count)
);

$update_plan = callAPI('PUT', 'https://api.example.com/put_url/'.$lease['plan_id'], json_encode($data_array));
$response = json_decode($update_plan, true);
$errors = $response['response']['errors'];
$data = $response['response']['data'][0];
*/


// DELETE
/*
callAPI('DELETE', 'https://api.example.com/delete_url/' . $id, false);
*/

// Creating custom headers before our call
/*
$one_month_ago = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
$rent_header = 'Search: and[][created][greater]=' . $one_month_ago . '%and[][created][less]=' . date('Y-m-d') . '%';
//the actual call with custom search header
$make_call = callAPI('GET', 'https://api.example.com/get_url/', false, $rent_header);
*/

// |INÍCIO| - Looping paises
//foreach($paises as $pais){
    // |INÍCIO| - Incluir Times
    $get_data1 = callAPI('GET', $apis[0]['URL'] . $pais, false, 'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']);
    $response1 = json_decode($get_data1, true);

    //echo "<br/><br/>response1:<br/><br/>";
    //print_r($response1);

    echo "<br/><br/>". $apis[0]['NomeAPI'] .":<br/><br/>";
    foreach ($response1["api"]["teams"] as $value) {
        validarInserirAtualizarTimes($value["team_id"], $value["name"], $value["country"], $value["logo"], $value["venue_name"]);

        /*
        echo "team_id: ". $value["team_id"] . "<br/>";
        echo "name: ". $value["name"] . "<br/>";
        echo "country: " . $value["country"] . "<br/>";
        echo "logo: " . $value["logo"] . "<br/>";
        echo "venue_name: " . $value["venue_name"] . "<br/>";
        */
    }
    // |FIM| - Incluir Times

    // |INÍCIO| - Incluir Campeonatos
    $get_data2 = callAPI('GET', $apis[1]['URL'] . $pais, false, 'x-rapidapi-key' . ':' . $apis[1]['x-rapidapi-key']);
    $response2 = json_decode($get_data2, true);

    //echo "<br/><br/>response2:<br/><br/>";
    //print_r($response2);

    echo "<br/><br/>". $apis[1]['NomeAPI'] .":<br/><br/>";
    foreach ($response2["api"]["leagues"] as $value) {
        validarInserirAtualizarCampeonatos($value["league_id"], $value["name"], $value["season"], $value["logo"], $value["standings"], $value["season_start"], $value["season_end"]);

        /*
        echo "league_id: ". $value["league_id"] . "<br/>";
        echo "name: ". $value["name"] . "<br/>";
        echo "season: ". $value["season"] . "<br/>";
        echo "logo: ". $value["logo"] . "<br/>";
        echo "standings: ". $value["standings"] . "<br/>";
        echo "season_start: ". $value["season_start"] . "<br/>";
        echo "season_end: ". $value["season_end"] . "<br/>";
        */
    }
    // |FIM| - Incluir Campeonatos

    // |INÍCIO| - Incluir Partidas
    $reponseCampeonatos = buscarCampeonatos();
    $reponseCampeonatos = json_decode($reponseCampeonatos, false);

    //echo "<br/><br/>reponseCampeonatos:<br/><br/>";
    //print_r($reponseCampeonatos);

    foreach ($reponseCampeonatos as $value0){
        //echo "id_campeonato: " . $value0->id_campeonato . "<br/>";
        $txtLeagueId = $value0->id_campeonato;

        $urlPartida1 = str_replace("<LeagueId>", "$txtLeagueId", $apis[2]['URL']);
        $get_data3 = callAPI('GET', $urlPartida1, false, 'x-rapidapi-key' . ':' . $apis[2]['x-rapidapi-key']);
        $response3 = json_decode($get_data3, true);

        //echo "<br/><br/>response3:<br/><br/>";
        //print_r($response3);

            $IdPartida = "";
            $IdCampeonato = "";
            $DataHoraPartida = "";
            $IdTimeCasa = "";
            $IdTimeVisitante = "";
            $V1Parameter = "";
            $V2Parameter = "";
            $DDParameter = "";
            $ResultadoParameter = "";  

        echo "<br/><br/>". $apis[2]['NomeAPI'] .":<br/><br/>";
        foreach ($response3["api"]["fixtures"] as $value) {
            /*
            echo "fixture_id: ". $value["fixture_id"] . "<br/>";
            echo "league_id: ". $value["league_id"] . "<br/>";
            echo "event_date: ". $value["event_date"] . "<br/>";
            echo "team_id: ". $value["homeTeam"][0]["team_id"] . "<br/>";
            echo "team_id: ". $value["awayTeam"][0]["team_id"] . "<br/>";
            */

            $IdPartida = $value["fixture_id"];
            $txtFixtureId = $IdPartida;
            $IdCampeonato = $value["league_id"];
            $DataHoraPartida = str_replace("+00:00", "", $value["time"]);
            $DataHoraPartida = str_replace("T", " ", $DataHoraPartida);
            $IdTimeCasa = $value["homeTeam"]["team_id"];
            $IdTimeVisitante = $value["awayTeam"]["team_id"];
            // Tratar Significado
            $ResultadoParameter = $value["statusShort"];    

            echo "<br/><br/>". $apis[3]['NomeAPI'] .":<br/><br/>";
            if($txtFixtureId != "") {
                $urlPartida2 = str_replace("<FixtureId>", "$txtFixtureId", $apis[3]['URL']);
                $urlPartida2 = str_replace("<Label>", "$txtLabel", $urlPartida2);
                $get_data4 = callAPI('GET', $urlPartida2, false, 'x-rapidapi-key' . ':' . $apis[3]['x-rapidapi-key']);
                $response4 = json_decode($get_data4, true);

                foreach ($response4["api"]["odds"] as $value) {
                    //echo "odd: ". $value["odd"] . "<br/>";
                    //echo "odd: ". $value["odd"] . "<br/>";
                    //echo "odd: ". $value["odd"] . "<br/>";

                    $V1Parameter = $value["bookmakers"][0]["bets"][0]["values"][0]["odd"];
                    $DDParameter = $value["bookmakers"][0]["bets"][0]["values"][1]["odd"];
                    $V2Parameter = $value["bookmakers"][0]["bets"][0]["values"][2]["odd"];
                }
            }

            /*
            echo "IdPartida: " . $IdPartida . "<br />";
            echo "IdCampeonato: " . $IdCampeonato . "<br />";
            echo "DataHoraPartida: " . $DataHoraPartida . "<br />";
            echo "IdTimeCasa: " . $IdTimeCasa . "<br />";
            echo "IdTimeVisitante: " . $IdTimeVisitante . "<br />";
            echo "V1Parameter: " . $V1Parameter . "<br />";
            echo "V2Parameter: " . $V2Parameter . "<br />";
            echo "DDParameter: " . $DDParameter . "<br />";
            echo "ResultadoParameter: " . $ResultadoParameter . "<br />";
            */

            ValidarInserirAtualizarPartidas($IdPartida, $IdCampeonato, $DataHoraPartida, $IdTimeCasa, $IdTimeVisitante, $V1Parameter, $V2Parameter, $DDParameter, $ResultadoParameter);
        }
    }
    // |FIM| - Incluir Partidas
//}
// |FIM| - Looping paises
?>