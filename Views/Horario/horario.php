<?php 
header_admin($data); 
getModal('horarioModal', $data);
?>
  <main id="main" class="main">

  <div class="mb-3">
    <input class="form-control" type="file" id="excel">
  </div>

  <div class="mb-3">

  </div>

  <div id="alertZone"></div>

  <div id="display"></div>

  </main><!-- End #main -->
  <script src="<?= media() ?>/vendor/read-excel-file/read-excel-file.min.js"></script>
  <?php footer_admin($data); ?>