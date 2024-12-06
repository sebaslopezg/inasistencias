<?php 
header_admin($data); 
getModal('usuariosModal', $data);
?>
  <main id="main" class="main">
  <div class="pagetitle">
    <h1>
      <?= $data['page_title'] ?>
      <button type="button" id="btnCrearUsuario" class="btn btn-primary">
        Crear Usuario
      </button>
    </h1>

    </div>





  </main><!-- End #main -->
  <?php footer_admin($data); ?>