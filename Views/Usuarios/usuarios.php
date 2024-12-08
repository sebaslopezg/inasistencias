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


    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Datatables</h5>

        <table id="tablaUsuarios" class="table">
          <thead>
            <tr>
              <th>Nombre Completo</th>
              <th>Documento</th>
              <th>Telefono</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Accion</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>
    </div>

  </main><!-- End #main -->
  <?php footer_admin($data); ?>