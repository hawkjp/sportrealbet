<!-- Inicio ticket -->
<div class="row mt-4" id="divAlert" hidden>
  <div class="col-lg-10" id="divAlertContent">
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card mb-4 mt-4 shadow">
      <div class="card-header">
        <span style="font-weight: bold;">Tickets pendentes para aprovação</span>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12 mb-3">
            <input type="text" id="ticketFilter" class="form-control" onkeyup="filterFunction()" placeholder="Digite o numero do ticket..">
          </div>
        </div>
        <div class="row" id="ticketList">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4" id="ticket" hidden>
  <div class="col-lg-10">
    <div class="card mb-4" id="dvRetTicket">
    </div>
  </div>
</div>

<script src="ModelJS/ticket.js?random=<?php echo rand(); ?>"></script>
<script>
  $(document).ready(function() {
    buscaTicketPendente();
  });

  function filterFunction() {
    var input, filter, cards, cardContainer, h5, title, i;
    input = document.getElementById("ticketFilter");
    filter = input.value.toUpperCase();
    cardContainer = document.getElementById("ticketList");
    cards = cardContainer.getElementsByClassName("card");
    for (i = 0; i < cards.length; i++) {
      title = cards[i].querySelector(".cft");
      if (title.innerText.toUpperCase().indexOf(filter) > -1) {
        cards[i].style.display = "";
      } else {
        cards[i].style.display = "none";
      }
    }
  }
</script>