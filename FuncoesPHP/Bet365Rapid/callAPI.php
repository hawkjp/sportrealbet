<?php
//set_time_limit(1200);
include_once("../../DAO/conexao.php");
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


?>