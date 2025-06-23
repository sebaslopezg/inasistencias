<?php header_admin($data); ?>
  <main id="main" class="main">

  <form action="" id="frmAsistencias">
    <div class="row">
      <div class="mb-3 col-6">
        <input type="text" class="form-control" id="txtCodigo" name="txtCodigo">
      </div>
      <div class="mb-3 col-6">
        <input type="submit" class="btn btn-primary" id="btnEnviar">
      </div>
    </div>
  </form>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body" id="toast_body">
      {msg}
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  </div>
</div>



  </main><!-- End #main -->
  <?php footer_admin($data); ?>