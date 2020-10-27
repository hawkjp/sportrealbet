<?php
function retornarSignificadoResultado($statusShort){
    $retorno = "";

    switch($statusShort){
        case "":
        break;
        case "TBD":
            //Time To Be Defined
            $retorno = "Iniciar";
            break;
        case "NS":
            //Not Started
            $retorno = "Iniciar";
            break;
        case "1H":
            //First Half, Kick Off
            $retorno = "Andamento";
            break;
        case "HT":
            //Halftime
            $retorno = "Andamento";
            break;
        case "2H":
            //Second Half, 2nd Half Started
            $retorno = "Andamento";
            break;
        case "ET":
            //Extra Time
            $retorno = "Andamento";
            break;
        case "P":
            //Penalty In Progress
            $retorno = "Andamento";
            break;
        case "FT":
            //Match Finished
            $retorno = "Finalizado";
            break;
        case "AET":
            //Match Finished After Extra Time
            $retorno = "Finalizado";
            break;
        case "PEN":
            //Match Finished After Penalty
            $retorno = "Finalizado";
            break;
        case "BT":
            //Break Time (in Extra Time)
            $retorno = "Andamento";
            break;
        case "SUSP":
            //Match Suspended
            $retorno = "Cancelado";
            break;
        case "INT":
            //Match Interrupted
            $retorno = "Cancelado";
            break;
        case "PST":
            //Match Postponed
            $retorno = "Iniciar";
            break;
        case "CANC":
            //Match Cancelled
            $retorno = "Cancelado";
            break;
        case "ABD":
            //Match Abandoned
            $retorno = "Cancelado";
            break;
        case "AWD":
            //Technical Loss
            $retorno = "Andamento";
            break;
        case "WO":
            //WalkOver
            $retorno = "Finalizado";
            break;
        default:
            $retorno = "Outro";
    }

    return $retorno;
}
?>