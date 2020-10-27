<?php
//set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("../../DAO/conexao.php");
include_once("../APIbets/callAPI.php");
header('Content-Type: text/html; charset=utf-8');

/*
https://weichie.com/blog/curl-api-calls-with-php/
http://idealize.tecnologia.ws/BolaoDES/FuncoesPHP/callAPI.php
*/

//$paises = array("Brazil", "England", "Spanin");

$apis = array(
    array(
        "NomeAPI"          => "Bet365 Teams",
        "URL"              => "https://bet365-scoccer-odds.p.rapidapi.com/v1/league",
        "x-rapidapi-key"   => "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
        "token"            => "37146-av3VER3rXx03ms"
    ),
);

function limpar_string($string)
{
    if ($string !== mb_convert_encoding(mb_convert_encoding($string, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
        $string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string));
    $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $string);
    $string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), ' ', $string);
    $string = preg_replace('/( ){2,}/', '$1', $string);
    $string = strtoupper(trim($string));
    return $string;
}

function validarInserirAtualizarCampeonatos($id, $name, $pais, $tabela, $toplist)
{
    $con =  new conexao();
    $open = $con->connect();

    try {
        $sql = "CALL ValidarInserirAtualizarCampeonatos($id, '$name', '$pais', $tabela, $toplist);";
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
    $get_data1 = callAPI('GET', $apis[0]['URL'] . '?sport_id=1' . '&page=' . $pagina, false, 'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']);
    $responseLeagues = json_decode($get_data1, true);

    //echo "<br/><br/>response2:<br/><br/>";
    //print_r($response2);

    foreach ($responseLeagues["results"] as $league) {
        validarInserirAtualizarCampeonatos($league["id"], limpar_string($league["name"]), $league["cc"], $league["has_leaguetable"], $league["has_toplist"]);

        // echo "league_id: ". $league["id"] . "<br/>";
        // echo "name: ". $league["name"] . "<br/>";
        // echo "season: ". $league["cc"] . "<br/>";

    }

    if ($responseLeagues["pager"]["total"] < $pagina * 50) {
        $fim = true;
    } else {
        $pagina++;
    }
} while ($fim == false);
