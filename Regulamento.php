<div class="row mt-4 ">
  <div class="col-lg-12">
    <div class="card mb-4 shadow">
      <div class="card-header">
        <span style="font-weight: bold;">Regulamento</span>
      </div>
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
    buscaCarrinho();
  });
</script>