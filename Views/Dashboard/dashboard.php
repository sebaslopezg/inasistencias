<?php header_admin($data); ?>
  <main id="main" class="main">

  <div class="row">

    <div class="col-6">
      <a href="<?= base_url() ?>/inasistencias">
        <div class="card card-bg-1">
          <div class="card-body mt-4">
            <p><b>Registrar Asistencias</b></p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-6">
      <a href="<?= base_url() ?>/excepciones">
        <div class="card card-bg-2">
          <div class="card-body mt-4">
            <p><b>Exepciones</b></p>
          </div>
        </div>
      </a>
    </div>

  </div>


  </main><!-- End #main -->
  <?php footer_admin($data); ?>