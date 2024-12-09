<!-- MODAL -->
<div class="modal fade" id="crearExcusaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Excusa</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="frmCrearExcusa" method="post">
          <div class="row">
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">Inansistencia</label>
              <input type="text" class="form-control" id="txtNombre" name="txtNombre">
            </div>
            <div class="col-12 mb-3"><label for="txtNombre" class="form-label">Excusa</label>
              <input type="file" class="form-control" id="txtNombre" name="txtNombre">
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>