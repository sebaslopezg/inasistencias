<!-- Modal -->
<div class="modal fade" id="horarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="horariotitulo">Datos del Horario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        
         <div id="displayModal"></div>   
        
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="horarioEditarModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="horariotitulo">Editar Horario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        



      <div class="col-lg-6">
        <div class="mb-3 col-12">
          <label for="txtNombre" class="form-label">Fecha</label>
          <input type="text" class="form-control" id="hFecha" name="hFecha" value="">
        </div>

        <div class="mb-3 col-12">
          <label for="txtNombre" class="form-label">Instructor</label>
          <input type="text" class="form-control" id="hInstructor" name="hInstructor" value="">
        </div>
      </div>

      <div class="col-lg-6">
        <div class="mb-3 col-6">
          <label for="txtNombre" class="form-label">Hora Inicio</label>
          <input type="text" class="form-control" id="hHoraInicio" name="hHoraInicio" value="">
      </div>

      <div class="mb-3 col-6">
        <label for="txtNombre" class="form-label">Hora Fin</label>
        <input type="text" class="form-control" id="hHoraFin" name="hHoraFin" value="">
      </div>

      <div class="mb-3 col-6">
        <label for="txtNombre" class="form-label">Ficha</label>
        <input type="text" class="form-control" id="hFicha" name="hFicha" value="">
      </div>

      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>