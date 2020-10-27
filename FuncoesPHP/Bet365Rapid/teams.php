<?php
//set_time_limit(1200);
include_once("../../DAO/conexao.php");
include_once("../APIbets/callAPI.php");
header('Content-Type: text/html; charset=utf-8');

$apis = array(
    array(
        "NomeAPI"          =>          "Bet365 Teams",
        "URL"              =>          "https://bet365-scoccer-odds.p.rapidapi.com/v1/team",
        "x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
        "token"            =>          "37146-av3VER3rXx03ms"
    ),
);

function validarInserirAtualizarTimes($teamId, $name, $country, $logo)
{
    $name = str_replace("'", "", $name);
    $country = str_replace("'", "", $country);
    $logo = str_replace("'", "", $logo);

    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "CALL ValidarInserirAtualizarTimes($teamId, '$name', '$country', '$logo');";
        $result = mysqli_query($open, $sql);

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
    $get_data1 = callAPI('GET', $apis[0]['URL'] . '?sport_id=1&page=' . $pagina, false, 'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']);
    $response1 = json_decode($get_data1, true);

    echo "<br/><br/>" . $apis[0]['NomeAPI'] . ":<br/><br/>";
    foreach ($response1["results"] as $value) {
        validarInserirAtualizarTimes($value["id"], $value["name"], $value["cc"], ($value["image_id"] == null || $value["image_id"] == 0) ? null : 'https://assets.b365api.com/images/team/m/' . $value["image_id"] . '.png');
    }

    if ($response1["pager"]["total"] < $pagina * 50) {
        $fim = true;
    } else {
        $pagina++;
    }
} while ($fim == false);
