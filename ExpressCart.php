<!-- class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"-->
<nav class="navbar navbar-expand navbar-light bg-submenu topbar fixed-top shadow" style="top:140px; height:50px">
    <!-- <input type="text" name="amount" class="form-control border border-dark rounded .money" onkeyup="calculaRetorno()" id="amount" placeholder="Valor da aposta" required=""> -->
    <!-- <button class="btn btn-dark btn-outline-dark ml-2" id="oddsTemp" disabled>1,02x</button> -->
    <div>
        <div class="input-group">
            <input type="text" name="amount" class="form-control .money" onkeyup="calculaRetorno(this.value);" id="amountCart" placeholder="Valor da aposta" required="">
            <div class="input-group-append">
                <span class="input-group-text" id="odds-temp-addon">1,00x</span>
            </div>
            <!-- <div class="input-group-append">
                <span class="input-group-text" id="oddsTemp-addon2">1,00x</span>
            </div> -->
        </div>
    </div>
    <div>
        <div class="input-group">
            <input type="text" name="amount" class="form-control border rounded ml-2 .money" disabled id="expIncome">
            <button class="btn btn-success ml-2" data-toggle="modal" data-target="#cartModal">
                <li class="fas fa-check"></li>
            </button>
        </div>
    </div>
</nav>
<!-- End of Topbar -->

<script>
    function AlterarConteudoPagina(pagina) {

        var div = 'divConteudoPagina';
        $.ajax({
            type: 'POST',
            url: pagina,
            cache: false,
            success: function(retorno) {
                $('#' + div).html(retorno);
            },
            beforeSend: function() {
                $('#' + div).html('<i class="fa fa-refresh"></i>');
            },
            error: function() {
                $('#' + div).html('');
            }
        });
    }


    // function validaCont() {
    //     let amount = parseFloat($('#odds-temp-addon').html().slice(0, -1).replace(',', ''));
    //     if (isNaN(amount)) {
    //         $('#odds-temp-addon').html("1,00x")
    //     }
    // }
    // $('#odds-temp-addon').on('DOMSubtreeModified', function() {
    //     let amount = parseFloat($('#odds-temp-addon').html().slice(0, -1).replace(',', ''));
    //     if (isNaN(amount)) {
    //         $('#odds-temp-addon').html('');
    //         $('#odds-temp-addon').html("1,00x")
    //     }
    // })

    var w = window.innerWidth;
    if (w < 765) {
        //$("#accordionSidebar").addClass("toggled");
    }
</script>