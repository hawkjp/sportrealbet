<?php
set_time_limit(1200);
chdir(dirname(__FILE__));
include_once("conexao.php");
include_once("callAPI.php");
include('simple_html_dom.php');
header('Content-Type: text/html; charset=utf-8');

$agent = 'Mozilla/5.0 (Linux; Android 4.1.1; Nexus 7 Build/JRO03D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Safari/535.19';
$cookieJar = null;
$url = "https://sportreal.bet/";

$ch = curl_init();

$fp = fopen("example_homepage.txt", "w");
curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
curl_setopt( $ch, CURLOPT_HEADER, 0 );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_FILE, $fp);


// $data = file_get_contents("https://www.bet365.com");

// $html = file_get_html("https://www.bet365.com");

// $apis = array(
//     array(
//         "NomeAPI"          =>          "Bet365 events",
//         "URL"              =>          "https://bet365-scoccer-odds.p.rapidapi.com/v1/event/view",
//         "x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
//         "token"            =>          "37146-av3VER3rXx03ms"
//     ),
// );

// $url = "http://www.bet365.com";
// $fields[] = '';

// /* Inicializa a biblioteca cURL */
// $ch = curl_init();
// /* Define as configurações da requisição */
// curl_setopt_array($ch, [
//     /* Informa a URL */
//     CURLOPT_URL            => $url,
    
//     /* Informa que deseja capturar o retorno */
//     CURLOPT_RETURNTRANSFER => true,
    
//     /* Permite o redirecionamento */
//     CURLOPT_FOLLOWLOCATION => true,
    
//     /* Informa que o tipo da requisição é POST */
//     CURLOPT_POST           => true,
    
//     /* Converte os dados para application/x-www-form-urlencoded */
//     CURLOPT_POSTFIELDS     => http_build_query($fields),
    
//     /**
//      * Habilita a escrita de Cookies
//      *(É obrigatório para alguns sites)
//      */
//     CURLOPT_COOKIEJAR      => 'cookies.txt',
    
//     /* Desabilita a verificação do SSL,
//      * caso você possua, pode deixar habilitado
//      */
//     CURLOPT_SSL_VERIFYPEER => false,
// ]);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// curl_setopt($ch, CURLOPT_SSLVERSION, 3);
/* Executa a requisição e captura o retorno */
$response = curl_exec($ch);
/* Captura eventuais erros */
$error = curl_error($ch);
/* Captura a informação da requisição */
$info = curl_getinfo($ch);
/* Fecha a conexão */
echo($response);
fclose($fp);
curl_close($ch);
