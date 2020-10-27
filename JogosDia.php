<div class="row">
  <!-- Lista propaganda -->
  <div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="false">
    <div class="carousel-inner">
      <?php

      $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

      $dir = 'img/ad/';
      $imagens = glob($dir . "*");
      foreach ($imagens as $imagem) {
        $explodeImage = explode('.', $imagem);
        $extension = end($explodeImage);
        $imageId = array_pop(array_reverse($explodeImage));

        if (in_array($extension, $imageExtensions)) {
          echo "<div class='carousel-item'>
                  <img class='card-img-top' src='$imagem' alt='Card image cap'>
                </div>";
        } else {
          echo "<div class='carousel-item'>
                  <video style='width:100%; height:100%;' autoplay='autoplay' loop='loop' muted id='$imageId' onclick=toggleMute(this.id)>
                    <source src='$imagem' type='video/mp4'></source>
                  </video>
                </div>";
        }
      }
      ?>
    </div>
    <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <!-- Lista de Jogos inicio -->
  <div class="col-sm-12 mt-4" id="listaJogos">
    <input class="form-control" id="matchFilter" type="text" placeholder="Procurar por um jogo" onkeyup="filterFunction()">

    <div id="dvMatchList">
    </div>

    <div class="mb-4">
    </div>
  </div>
  <!-- Lista de Jogos fim -->

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

  <div class="row mt-4" id="ticket" hidden>
    <div class="col-lg-10">
      <div class="card mb-4" id="dvRetTicket">
      </div>
    </div>
  </div>
  <!-- Modal carrinho FIM -->

  <!-- Modal expand -->

  <div class="modal fade" id="expandModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div id="dvRetExpand">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal expand FIM -->

  <script src="ModelJS/partidas.js?random=<?php echo rand(); ?>"></script>
  <script src="ModelJS/carrinho.js?random=<?php echo rand(); ?>"></script>
  <script>
    $(document).ready(function() {
      buscaPartidasDia();
      buscaCarrinho();
      filterSelection("all");
      $('#carouselControls').find('.carousel-item').first().addClass('active');
    });
    
    // $('.carousel.carousel-multi-item.v-2 .carousel-item').each(function() {
    //   var next = $(this).next();
    //   if (!next.length) {
    //     next = $(this).siblings(':first');
    //   }
    //   next.children(':first-child').clone().appendTo($(this));

    //   for (var i = 0; i < 4; i++) {
    //     next = next.next();
    //     if (!next.length) {
    //       next = $(this).siblings(':first');
    //     }
    //     next.children(':first-child').clone().appendTo($(this));
    //   }
    // });
  </script>