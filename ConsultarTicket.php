<form name="frmHome1" id="frmHome1" method="POST" class="d-sm-inline-block form-inline mr-auto ml-md-3 mt-4 my-2 my-md-0 mw-100 navbar-search">
  <div class="input-group">
    <input type="text" class="form-control bg-white border-0 small" placeholder="Ticket" aria-label="Search" aria-describedby="basic-addon2" id="id_ticket">
    <!--<div class="input-group-append">
       class="btn btn-primary"
    </div> -->
    <button class="btn btn-secondary" type="button" onclick="buscaTicketId()">
      <i class="fas fa-search fa-sm"></i>
    </button>
  </div>
</form>

<div class="row mt-4" id="divAlert" hidden>
  <div class="col-lg-10" id="divAlertContent">
  </div>
</div>

<div class="row mt-4" id="ticket" hidden>
  <div class="col-lg-10">
    <div class="card mb-4" id="dvRetTicket">
    </div>
  </div>
</div>

<!-- Modal carrinho -->

<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div id="dvRetCarrinho">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal carrinho FIM -->

<script src="ModelJS/ticket.js?random=<?php echo rand(); ?>"></script>
<script src="ModelJS/carrinho.js?random=<?php echo rand(); ?>"></script>
<script>
  $(document).ready(function() {
    <?php
      if (array_key_exists("ticket", $_GET)){
    ?>
        buscaTicket(<?php echo urldecode($_GET["ticket"]); ?>);
    <?php
      }
    ?>
  });

  $('form input').keydown(function(e) {
    if (e.keyCode == 13) {
      e.preventDefault();
      return false;
    }
  });
</script>