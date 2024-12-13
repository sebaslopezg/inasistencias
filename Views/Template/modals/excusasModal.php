<!-- MODAL -->
<div class="modal fade" id="crearExcusaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Excusa</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="frmCrearExcusa" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">IdExcusa</label>
              <input type="number" class="form-control" id="txtIdExcusa" name="txtIdExcusa" value="0">
            </div>
            <div class="col-12 mb-3"><label for="" class="form-label">idInasistencias</label>
              <input type="number" class="form-control" id="txtIdInasistencia" name="txtIdInasistencia">
            </div>
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">idUsuario</label>
              <input type="number" class="form-control" id="txtIdUsuario" name="txtIdUsuario">
            </div>
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">IdInstructor</label>
              <input type="number" class="form-control" id="txtIdInstructor" name="txtIdInstructor">
            </div>
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">Excusa</label>
              <input type="file" class="form-control" id="txtArchivo" name="txtArchivo">
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Estado</label>
                <select class="form-control" id="txtEstado" name="txtEstado">
                    <option value="0">Inactivo</option>
                    <option value="1">Activo</option>
                </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Agregar</button>
      </div>
      </form>
    </div>
  </div>
</div>