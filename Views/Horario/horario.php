<?php header_admin($data); ?>
  <main id="main" class="main">

  <div class="mb-3">
    <input class="form-control" type="file" id="excel">
  </div>

  <div class="mb-3">
    <button id="btnMostrarRegistrosLeidos" class="btn btn-primary" disabled>Mostrar registros leidos</button>
  </div>

  <div id="alertZone"></div>

  <div class="card">
    <div class="card-body">
      <div id="display"></div>
    </div>
  </div>



  </main><!-- End #main -->
  <script src="<?= media() ?>/vendor/read-excel-file/read-excel-file.min.js"></script>
  <?php footer_admin($data); ?>