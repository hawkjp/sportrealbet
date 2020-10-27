var div = 'dvMatchList';
var pagina = 'DAO/partidasDAO.php';

function buscaPartidas(id_partida){
    var obj = {
        acao: 'BUSCA',
        id_partida:id_partida
    }

    $.ajax({
        url: pagina,
        method:'post',
        cache:false,
        data:obj,
        beforeSend: function(){
            msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
            $('#'+div).html(msg);
        },
        success:function(retorno){
            $('#'+div).html('');
            $('#'+div).html(retorno);
            toggleHighlight();            
        },
        error: function(){
            $('#'+div).html("Erro ao carregar pagina.");
        }
    }); 
}

function buscaPartidasDia(){
  var obj = {
      acao: 'BUSCA_DIA'
  }

  $.ajax({
      url: pagina,
      method:'post',
      cache:false,
      data:obj,
      beforeSend: function(){
          msg = "<div style='font-size:12px' class='alert alert-primary mt-4 col-sm-3'>Aguarde...</div>";
          $('#'+div).html(msg);
      },
      success:function(retorno){
          $('#'+div).html('');
          $('#'+div).html(retorno);
          toggleHighlight();            
      },
      error: function(){
          $('#'+div).html("Erro ao carregar pagina.");
      }
  }); 
}

function modalEx(id_partida){
    var divEx = 'dvRetExpand';
    var obj = {
        acao: 'BUSCA_ODDS',
        id_partida: id_partida
    }

    $.ajax({
        url: pagina,
        method: 'post',
        cache: false,
        data: obj,
        beforeSend: function () {
            $('#' + divEx).html("<div style='font-size:12px' class='alert alert-primary col-sm-3'>Carregando...</div>");
            $('#expandModal').modal();  
        },
        success: function (retorno) {
            $('#' + divEx).html(retorno);
            toggleHighlight();          
        },
        error: function () {
            $('#' + divEx).html("Erro ao carregar pagina.");
        }
    });
}

$('#btnCadastrarCampos').click( function(){
    cadastrarCampos();
});


function completarJogo() {
    let valorjogado = $("#amount").val();
    if (valorjogado != '' && valorjogado != null) {
      cadastraTicket();
      $('#cartModal').modal('hide');
    } else {
      Swal.fire({text: "Digite um valor para realizar o jogo!", title: "Ops!", icon: "error" })
    }
  }

  function highlight(divId, action) {
    $('#' + divId).toggleClass('highlight');
  }


  function filterSelection(c) {
    var x, i;
    x = document.getElementsByClassName("divFiltro");
    if (c == "all") c = "";
    for (i = 0; i < x.length; i++) {
      w3AddClass(x[i], "show");
      if (x[i].className.indexOf(c) > -1) w3RemoveClass(x[i], "show");
    }
  }

  function w3AddClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
      if (arr1.indexOf(arr2[i]) == -1) {
        element.className += " " + arr2[i];
      }
    }
  }

  function w3RemoveClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
      while (arr1.indexOf(arr2[i]) > -1) {
        arr1.splice(arr1.indexOf(arr2[i]), 1);
      }
    }
    element.className = arr1.join(" ");
  }

  function filterFunction() {
    var input, filter, cards, cardContainer, h5, title, i;
    input = document.getElementById("matchFilter");
    filter = input.value.toUpperCase();
    cardContainer = document.getElementById("dvMatchList");
    cards = cardContainer.getElementsByClassName("divFiltro");
    for (i = 0; i < cards.length; i++) {
      title = cards[i].querySelector(".cfm");
      if (title.innerText.toUpperCase().indexOf(filter) > -1) {
        cards[i].style.display = "";
      } else {
        cards[i].style.display = "none";
      }
    }
  }

  function toggleMute(video) {
    var video=document.getElementById(video);
    video.muted = !video.muted;
  }