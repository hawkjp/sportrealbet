<?php
include_once("conexao.php");
include_once("callAPI.php");
include_once("../FuncoesPHP/sessao.php");

$acao = $_POST['acao'];

if (isset($_POST['id_partida'])) {
    $id_partida = $_POST['id_partida'];
} else {
    $id_partida = 0;
}

if (isset($_POST['valor'])) {
    $valor = $_POST['valor'];
} else {
    $valor = 0;
}

if (isset($_POST['mult'])) {
    $mult = $_POST['mult'];
} else {
    $mult = 0;
}

switch ($acao) {
    case "BUSCA":
        buscarPartidas($id_partida);
        break;
    case "BUSCA_ODDS":
        buscarOddPartida($id_partida);
        break;
    case "BUSCA_DIA":
        buscarPartidasDia();
        break;
}
function buscarPartidasDia()
{
    $idCampAnt = 0;
    $partidasDB = buscaPartidas(0, date('Y-m-d'), date('Y-m-d'));

    foreach ($partidasDB as $partida) {
        $nome_camp = $partida['nome_campeonato'];
        $temp_camp = date("Y");
        if ($idCampAnt != $partida['id_campeonato']) {
            if ($idCampAnt != 0) {
                ?>
                    </div>
                    </div>
                    </div>
                    </div>
                <?php
            }
?>
                <div class="card mt-4 shadow">
                    <div align="center">
                        <div class='card-header'>
<?php
            echo $nome_camp . ' - ' . $temp_camp
?>
                        </div>
                        <div class='container'>
                            <div class='row align-items-center'>
<?php
            $idCampAnt = $partida['id_campeonato'];
        }
?>
    <div class="divFiltro <?php echo " " . $partida['nome_time_casa'] . " " . $partida['nome_time_vis'] ?> m-2">
            <div class="d-flex bd-highlight">
                <div class="p-2 bd-highlight align-self-center">
                    <img src='<?php echo $partida['logo_time_casa'] ?>' height='70%' width='auto'></img>
                </div>
                <div class="p-2 flex-grow-1 bd-highlight">
                    <div class='d-flex flex-column justify-content-center '>
                        <h6 class='mr-2 mb-0 text-muted'><?php echo date("d/m/Y H:i", strtotime($partida['datahora_partida']))  ?></h6>
                        <h5 class='mr-2 mb-0 cfm'><?php echo $partida['nome_time_casa'] . " X " . $partida['nome_time_vis'] ?></h5>
                    </div>
                </div>
                <div class="p-2 bd-highlight align-self-center">
                    <img src='<?php echo $partida['logo_time_vis'] ?>' height='70%' width='auto'></img>
                </div>
            </div>
            <div class="col-md-6 col-12 m-2 align-bottom">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">1</h7>
                    </div>
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">X</h7>
                    </div>
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">2</h7>
                    </div>
                    <div class="p-2 flex-fill bd-highlight ">
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight flex-odd " onclick='incluirCarrinho("V1", "<?php echo $partida["id_partida"] ?>")' id="V1<?php echo $partida["id_partida"] ?>">
                        <h5 class="card-title"><?php echo $partida['V1'] ?>x</h5>
                    </div>
                    <div class="p-2 flex-fill bd-highlight flex-odd " onclick='incluirCarrinho("DD", "<?php echo $partida["id_partida"] ?>")' id="DD<?php echo $partida["id_partida"] ?>">
                        <h5 class="card-title"><?php echo $partida['DD'] ?>x</h5>
                    </div>
                    <div class="p-2 flex-fill bd-highlight flex-odd " onclick='incluirCarrinho("V2", "<?php echo $partida["id_partida"] ?>")' id="V2<?php echo $partida["id_partida"] ?>">
                        <h5 class="card-title"><?php echo $partida['V2'] ?>x</h5>
                    </div>
                    <div class="p-2 flex-fill bd-highlight flex-odd "  onclick='modalEx("<?php echo $partida["id_partida"] ?>")'>
                        <h7 class="card-text">+</h7>
                    </div>
                </div>
            </div>
    </div>
<?php
    }
}    

function buscarPartidas($id_partida)
{
    $idCampAnt = 0;
    $partidasDB = buscaPartidas($id_partida, date('Y-m-d'), date('Y-m-d', strtotime('+7 day')));

    foreach ($partidasDB as $partida) {
        $nome_camp = $partida['nome_campeonato'];
        $temp_camp = date("Y");
        if ($idCampAnt != $partida['id_campeonato']) {
            if ($idCampAnt != 0) {
                ?>
                    </div>
                    </div>
                    </div>
                    </div>
                <?php
            }
?>
                <div class="card mt-4 shadow">
                    <div align="center">
                        <div class='card-header'>
<?php
            echo $nome_camp . ' - ' . $temp_camp
?>
                        </div>
                        <div class='container'>
                            <div class='row align-items-center'>
<?php
            $idCampAnt = $partida['id_campeonato'];
        }
?>
    <div class="divFiltro <?php echo " " . $partida['nome_time_casa'] . " " . $partida['nome_time_vis'] ?> m-2">
            <div class="d-flex bd-highlight">
                <div class="p-2 bd-highlight align-self-center">
                    <img src='<?php echo $partida['logo_time_casa'] ?>' height='70%' width='auto'></img>
                </div>
                <div class="p-2 flex-grow-1 bd-highlight">
                    <div class='d-flex flex-column justify-content-center '>
                        <h6 class='mr-2 mb-0 text-muted'><?php echo date("d/m/Y H:i", strtotime($partida['datahora_partida']))  ?></h6>
                        <h5 class='mr-2 mb-0 cfm'><?php echo $partida['nome_time_casa'] . " X " . $partida['nome_time_vis'] ?></h5>
                    </div>
                </div>
                <div class="p-2 bd-highlight align-self-center">
                    <img src='<?php echo $partida['logo_time_vis'] ?>' height='70%' width='auto'></img>
                </div>
            </div>
            <div class="col-md-6 col-12 m-2 align-bottom">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">1</h7>
                    </div>
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">X</h7>
                    </div>
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">2</h7>
                    </div>
                    <div class="p-2 flex-fill bd-highlight ">
                        <h7 class="card-text">Mais</h7>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight flex-odd " onclick='incluirCarrinho("V1", "<?php echo $partida["id_partida"] ?>")' id="V1<?php echo $partida["id_partida"] ?>">
                        <h5 class="card-title"><?php echo $partida['V1'] ?>x</h5>
                    </div>
                    <div class="p-2 flex-fill bd-highlight flex-odd " onclick='incluirCarrinho("DD", "<?php echo $partida["id_partida"] ?>")' id="DD<?php echo $partida["id_partida"] ?>">
                        <h5 class="card-title"><?php echo $partida['DD'] ?>x</h5>
                    </div>
                    <div class="p-2 flex-fill bd-highlight flex-odd " onclick='incluirCarrinho("V2", "<?php echo $partida["id_partida"] ?>")' id="V2<?php echo $partida["id_partida"] ?>">
                        <h5 class="card-title"><?php echo $partida['V2'] ?>x</h5>
                    </div>
                    <div class="p-2 flex-fill bd-highlight flex-odd "  onclick='modalEx("<?php echo $partida["id_partida"] ?>")'>
                        <h7 class="card-text">+</h7>
                    </div>
                </div>
            </div>
    </div>
<?php
    }
}

function buscarOddPartida($id_partida) {
    $con =  new conexao();
    $open = $con->connect();

    try {

        $sql = "SELECT p.id_partida, p.datahora_partida, 
                       p.id_campeonato, c.nome_campeonato,
                       id_time_casa, 
                       t_casa.nome_time as nome_time_casa, t_casa.id_pais_time as pais_time_casa, t_casa.url_logo_time as logo_time_casa, p.V1,
                       id_time_visitante, 
                       t_visitante.nome_time as nome_time_vis, t_visitante.id_pais_time as pais_time_vis, t_visitante.url_logo_time as logo_time_vis, p.V2, p.DD
                  FROM tb_partidas p 
                INNER JOIN tb_time t_casa ON p.id_time_casa = t_casa.id_time 
                INNER JOIN tb_time t_visitante ON p.id_time_visitante = t_visitante.id_time
                INNER JOIN tb_campeonato c ON p.id_campeonato = c.id_campeonato";
                                if ($id_partida != 0) {
                                    $sql = $sql . " WHERE id_partida = $id_partida";
                                } else {
                                    $sql = $sql . " WHERE   p.datahora_partida > CURRENT_TIMESTAMP 
                            AND p.datahora_partida < DATE(NOW()) + INTERVAL 3 DAY 
                            AND p.V2 <> 0
                            AND p.DD <> 0
                            AND p.V1 <> 0";
                                }

                                $sql = $sql . " order by c.prioridade DESC, p.id_campeonato, p.datahora_partida";

                                $result = mysqli_query($open, $sql);
                                if (!$result) {
                                    $erro = mysqli_error($open);
                                    throw new $erro;
                                } else {
                                    $row = $result->fetch_assoc();
                                }
                            } catch (Exception $e) {
                                echo ($e->getMessage());
                            }

                            $con->disconnect($open);
                            ?>
                        <div class="col-sm-12 mt-4">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="row">
                                        <div class="col-sm-8 col-12 text-center mb-4">
                                            <div class='d-flex flex-column align-content-center '>
                                                <h6 class='mr-2 mb-0 text-muted'><?php echo date("d/m/Y H:i", strtotime($row['datahora_partida']))  ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 col-5 text-center">
                                            <img src='<?php echo $row['logo_time_casa'] ?>' height='40px' width='40px' class='mr-3'></img>
                                            <h6 class='mr-2 mb-0 cfm'><?php echo $row['nome_time_casa'] ?></h6>
                                        </div>
                                        <div class="col-sm-8 col-2">
                                            <div class='d-flex flex-column align-content-center '>
                                                <h6 class='mr-2 mb-0 text-muted'>X</h6>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-5 text-center">
                                            <img src='<?php echo $row['logo_time_vis'] ?>' height='40px' width='40px' class='mr-3'></img>
                                            <h6 class='mr-2 mb-0 cfm'><?php echo $row['nome_time_vis'] ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 mt-2">
                            <div class="text-center">
                                <h5 class="card-title ">Outras apostas</h5>
                            </div>
                        </div>

                        <?php
                            $apis = array(
                                array(
                                    "NomeAPI"          =>          "Bet365 Odds",
                                    "URL"              =>          "https://bet365-scoccer-odds.p.rapidapi.com/v2/event/odds",
                                    "x-rapidapi-key"   =>          "d004c8bb91msh31fcee01a756724p14fab5jsnbb9863e5603c",
                                    "token"            =>          "37146-av3VER3rXx03ms"
                                ),
                            );
                            $get_data4 = callAPI('GET', $apis[0]['URL'] . '?event_id=' . $id_partida, false, 'x-rapidapi-key' . ':' . $apis[0]['x-rapidapi-key']);
                            $response4 = json_decode($get_data4, true);
                            if ($response4["success"] == "1") {

                                if (isset($response4["results"]["odds"]["1_8"]) && !empty($response4["results"]["odds"]["1_8"])) {
                                    $odds18 = array_pop(array_reverse($response4["results"]["odds"]["1_8"]));

                                    ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        Primeiro tempo - Vencedor
                                    </div>
                                    <div class='d-flex flex-grow-1 align-items-center justify-content-around p-3'>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6 col-12 align-bottom">
                                                    <div class="row">
                                                        <div class="col-md-3 col-4 text-center">
                                                            <h7 class="card-text">Casa</h7>
                                                            <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("18V1", "<?php echo $row["id_partida"] ?>")' id="18V1<?php echo $row["id_partida"] ?>">
                                                                <h5 class="card-title"><?php echo validaOdds($odds18['home_od']); ?>x</h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-4 text-center">
                                                            <h7 class="card-text">X</h7>
                                                            <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("18DD", "<?php echo $row["id_partida"] ?>")' id="18DD<?php echo $row["id_partida"] ?>">
                                                                <h5 class="card-title"><?php echo validaOdds($odds18['draw_od']); ?>x</h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-4 text-center">
                                                            <h7 class="card-text">Visitante</h7>
                                                            <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("18V2", "<?php echo $row["id_partida"] ?>")' id="18V2<?php echo $row["id_partida"] ?>">
                                                                <h5 class="card-title"><?php echo validaOdds($odds18['away_od']); ?>x</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                    }
                                    // if (isset($response4["results"]["odds"]["1_2"]) && !empty($response4["results"]["odds"]["1_2"])) {
                                    //     $odds12 = array_pop(array_reverse($response4["results"]["odds"]["1_2"]));
                                    ?>
                            <!-- <div class="card mt-4">
    <div class="card-header">
        Handicap asiatico <?php //echo $odds12['handicap'] 
                                    ?>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-6 col-12 align-bottom">
                        <div class="row">
                            <div class="col-md-3 col-4 text-center">
                                <h7 class="card-text">1</h7>
                                <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("12V1", "<?php echo $row["id_partida"] ?>")' id="12V1<?php echo $row["id_partida"] ?>">
                                    <h5 class="card-title"><?php //echo validaOdds($odds12['home_od']) 
                                                                    ?>x</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-4 text-center">
                                <h7 class="card-text">X</h7>
                                <div class="d-flex flex-column align-content-around" style="border:1px solid white; border-radius: 5px; width: 70px; height: 33px;">
                                    <h5 class="card-title"></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-4 text-center">
                                <h7 class="card-text">2</h7>
                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("12V2", "<?php echo $row["id_partida"] ?>")' id="12V2<?php echo $row["id_partida"] ?>">
                                    <h5 class="card-title"><?php //echo validaOdds($odds12['away_od']) 
                                                                    ?>x</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div> -->
                            <?php
                                    // }
                                    if (isset($response4["results"]["odds"]["1_3"]) && !empty($response4["results"]["odds"]["1_3"])) {
                                        $odds13 = array_pop(array_reverse($response4["results"]["odds"]["1_3"]));
                                        ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        Mais ou menos de <?php echo $odds13['handicap'] ?> gols
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 align-bottom">
                                                        <div class="row">
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Mais</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("13MG", "<?php echo $row["id_partida"] ?>")' id="13MG<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds13['over_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text"></h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid white; border-radius: 5px; width: 70px; height: 33px;">
                                                                    <h5 class="card-title"></h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Menos</h7>
                                                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("13LG", "<?php echo $row["id_partida"] ?>")' id="13LG<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds13['under_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <?php
                                    }
                                    if (isset($response4["results"]["odds"]["1_4"]) && !empty($response4["results"]["odds"]["1_4"])) {
                                        $odds14 = array_pop(array_reverse($response4["results"]["odds"]["1_4"]));
                                        ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        Mais ou menos de <?php echo $odds14['handicap'] ?> escanteios
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 align-bottom">
                                                        <div class="row">
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Mais</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("14MC", "<?php echo $row["id_partida"] ?>")' id="14MC<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds14['over_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">X</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid white; border-radius: 5px; width: 70px; height: 33px;">
                                                                    <h5 class="card-title"></h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Menos</h7>
                                                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("14LC", "<?php echo $row["id_partida"] ?>")' id="14LC<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds14['under_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <?php
                                    }
                                    // if(isset($response4["results"]["odds"]["1_5"]) && !empty($response4["results"]["odds"]["1_5"])){
                                    //     $odds15 = array_pop(array_reverse($response4["results"]["odds"]["1_5"]));

                                    ?>
                            <!-- <div class="card mt-4">
    <div class="card-header">
        Primeiro tempo - handicap asiatico <?php //echo $odds15['handicap'] 
                                                    ?>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-6 col-12 align-bottom">
                        <div class="row">
                            <div class="col-md-3 col-4 text-center">
                                <h7 class="card-text">Casa</h7>
                                <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("15V1", "<?php echo $row["id_partida"] ?>")' id="15V1<?php echo $row["id_partida"] ?>">
                                    <h5 class="card-title"><?php //echo validaOdds($odds15['home_od']) 
                                                                    ?>x</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-4 text-center">
                                <h7 class="card-text">X</h7>
                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("15DD", "<?php echo $row["id_partida"] ?>")' id="15DD<?php echo $row["id_partida"] ?>">
                                    <h5 class="card-title"></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-4 text-center">
                                <h7 class="card-text">Visitante</h7>
                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("15V2", "<?php echo $row["id_partida"] ?>")' id="15V2<?php echo $row["id_partida"] ?>">
                                    <h5 class="card-title"><?php //echo validaOdds($odds15['away_od']) 
                                                                    ?>x</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div> -->
                            <?php
                                    // }
                                    if (isset($response4["results"]["odds"]["1_6"]) && !empty($response4["results"]["odds"]["1_6"])) {
                                        $odds16 = array_pop(array_reverse($response4["results"]["odds"]["1_6"]));
                                        ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        Primeiro tempo - mais ou menos de <?php echo $odds16['handicap'] ?> gols
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 align-bottom">
                                                        <div class="row">
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Mais</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("16MG", "<?php echo $row["id_partida"] ?>")' id="16MG<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds16['over_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">X</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid white; border-radius: 5px; width: 70px; height: 33px;">
                                                                    <h5 class="card-title"></h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Menos</h7>
                                                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("16LG", "<?php echo $row["id_partida"] ?>")' id="16LG<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds16['under_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <?php
                                    }
                                    if (isset($response4["results"]["odds"]["1_7"]) && !empty($response4["results"]["odds"]["1_7"])) {
                                        $odds17 = array_pop(array_reverse($response4["results"]["odds"]["1_7"]));

                                        ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        Primeiro tempo - mais ou menos de <?php echo $odds17['handicap'] ?> escanteios
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 align-bottom">
                                                        <div class="row">
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Mais</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("17MC", "<?php echo $row["id_partida"] ?>")' id="17MC<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds17['over_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">X</h7>
                                                                <div class="d-flex flex-column align-content-around" style="border:1px solid white; border-radius: 5px; width: 70px; height: 33px;">
                                                                    <h5 class="card-title"></h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-4 text-center">
                                                                <h7 class="card-text">Menos</h7>
                                                                <div class="d-flex flex-column align-content-center" style="border:1px solid gray; border-radius: 5px; width: 70px; height: 33px;" onclick='incluirCarrinho("17LC", "<?php echo $row["id_partida"] ?>")' id="17LC<?php echo $row["id_partida"] ?>">
                                                                    <h5 class="card-title"><?php echo validaOdds($odds17['under_od']) ?>x</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                    <?php
                            }
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

function buscaPartidas($id_partida, $dataInicio = null, $dataFinal = null, $ativa = true){
    $con =  new conexao();
    $open = $con->connect();

    $partidas = [];
    try {

        $sql = "SELECT p.id_partida, p.datahora_partida, 
                       p.id_campeonato, c.nome_campeonato,
                       id_time_casa, 
                       t_casa.nome_time as nome_time_casa, t_casa.id_pais_time as pais_time_casa, t_casa.url_logo_time as logo_time_casa, p.V1,
                       id_time_visitante, 
                       t_visitante.nome_time as nome_time_vis, t_visitante.id_pais_time as pais_time_vis, t_visitante.url_logo_time as logo_time_vis, p.V2, p.DD
                  FROM tb_partidas p 
                INNER JOIN tb_time t_casa ON p.id_time_casa = t_casa.id_time 
                INNER JOIN tb_time t_visitante ON p.id_time_visitante = t_visitante.id_time
                INNER JOIN tb_campeonato c ON p.id_campeonato = c.id_campeonato ";
        if ($id_partida != 0) {
            $sql = $sql . " WHERE id_partida = $id_partida";
        } else {
            if($ativa){
                $sql = $sql . "   AND p.datahora_partida > CURRENT_TIMESTAMP 
                                  AND p.V2 <> 0
                                  AND p.DD <> 0
                                  AND p.V1 <> 0 ";
            }
            if(!is_null($dataInicio)){
                $sql = $sql . " AND p.datahora_partida > '$dataInicio 00:00:00' ";
            }
            if(!is_null($dataFinal)){
                $sql = $sql . " AND p.datahora_partida < '$dataFinal 23:59:59' ";
            }
        }

        $sql = $sql . " ORDER BY c.prioridade DESC, p.id_campeonato, p.datahora_partida";

        // echo($sql);
        $result = mysqli_query($open, $sql);
        if (!$result) {
            $erro = mysqli_error($open);
            throw new $erro;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_object()) {
                    foreach ($row as $key => $col) {
                        $col_array[$key] = $col;
                    }
                    $row_array[] =  $col_array;
                }

                $partidas = $row_array;
                // echo "TT";
            } else {
                $partidas = [];
            }
         }
    } catch (Exception $e) {
        echo ($e->getMessage());
    }

    $con->disconnect($open);

    return $partidas;
}
?>