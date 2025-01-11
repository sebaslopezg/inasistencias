<!-- MODAL -->
<div class="modal fade" id="crearExcusaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #00AA00;">
        <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">Agregar Excusa</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="frmCrearExcusa" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12 "><label for="txtNombre" class="form-label" style="display: none;">IdExcusa</label>
              <input type="number" class="form-control" id="txtIdExcusa" name="txtIdExcusa" value="0" style="display: none;">
            </div>
            <div class="col-12 "><label for="" class="form-label" style="display: none;">idInasistencias</label>
              <input type="number" class="form-control" id="txtIdInasistencia" name="txtIdInasistencia" style="display: none;">
            </div>
            <div class="col-12 "><label for="txtNombre" class="form-label" style="display: none;">idUsuario</label>
              <input type="number" class="form-control" id="txtIdUsuario" name="txtIdUsuario" style="display: none;">
            </div>
            <div class="col-12 "><label for="txtNombre" class="form-label" style="display: none;">IdInstructor</label>
              <input type="number" class="form-control" id="txtIdInstructor" name="txtIdInstructor" style="display: none;">
            </div>
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">Excusa</label>
              <input type="file" class="form-control" id="txtArchivo" name="txtArchivo" accept="application/pdf" required>
            </div>
            <div class="mb-3">
              <label for="genero" class="form-label" style="display: none;">Estado</label>
              <select class="form-control" id="txtEstado" name="txtEstado" style="display:none;">
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
<!-- FIN DE MODAL DE AGREGAR EXCUSA -->

<!-- Modal observaciones-->
<div class="modal fade" id="modalObsevaciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #00AA00;">
        <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">Agregar Observacion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="frmObservacion" method="post">
      <div class="modal-body">
      <div class="col-12 "><label for="txtNombre" class="form-label" style="display: none;">IdExcusa</label>
         <input type="number" class="form-control" id="IdExcusa" name="IdExcusa" value="0" style="display: none;">
      </div>
      <div class="form-floating">
        <textarea class="form-control" placeholder="Leave a comment here" id="txtObservacion" name="txtObservacion" style="height: 100px" required></textarea>
        <label for="floatingTextarea2">Observacion</label>
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
<!-- fin de modal observaciones -->

<!-- Modal observaciones(APRENDIZ)-->
<div class="modal fade" id="modalObsApre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #00AA00;">
        <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">Ver Observacion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="frmObservacion" method="post">
      <div class="modal-body">
      <div class="form-floating">
        <textarea class="form-control" id="observacionApre" style="height: 100px" readonly></textarea>
        <label for="floatingTextarea2">Observacion</label>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- fin de modal observaciones -->