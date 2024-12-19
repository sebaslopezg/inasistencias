<?php 
header_admin($data); 
getModal('horarioModal', $data);
?>
  <main id="main" class="main">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Gestionar Horarios</h3>
    </div>

    <div class="card-body">
      <table id="tablaHorarios" class="table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>Ficha</th>
            <th>Instructor</th>
            <th>Accion</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  </main><!-- End #main -->
  <script src="<?= media() ?>/vendor/read-excel-file/read-excel-file.min.js"></script>
  <?php footer_admin($data); ?>