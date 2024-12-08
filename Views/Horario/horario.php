<?php header_admin($data); ?>
  <main id="main" class="main">

  <input class="form-control" type="file" id="excel">

  <br><br>
  <div id="display"></div>

  </main><!-- End #main -->
  <script src="<?= media() ?>/vendor/read-excel-file/read-excel-file.min.js"></script>
  <?php footer_admin($data); ?>