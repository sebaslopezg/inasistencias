<!-- Modal -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="frmCrearUsuario" method="POST">
            <input type="hidden" name="idUsuario" id="idUsuario" value="0">
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="txtNombre" class="form-label">Nombre(s)</label>
                    <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                </div>
                <div class="mb-3 col-6">
                    <label for="txtApellido" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="txtApellido" name="txtApellido">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-6">
                    <label for="txtDocumento" class="form-label">Numero de documento</label>
                    <input type="number" class="form-control" id="txtDocumento" name="txtDocumento" >
                </div>

                <div class="mb-3 col-6">
                    <label for="txtTelefono" class="form-label">Telefono</label>
                    <input type="number" class="form-control" id="txtTelefono" name="txtTelefono">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-12">
                    <label for="genero" class="form-label">Genero</label>
                    <select class="form-control" name="genero" id="genero">
                        <option value="0">Seleccione el Genero</option>
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                        <option value="3">Otro</option>
                    </select>
                </div>
            </div>


            <div class="mb-3">
                <label for="txtEmail" class="form-label">Correo electronico</label>
                <input type="email" class="form-control" id="txtEmail" name="txtEmail">
            </div>


            <div class="mb-3">
                <label for="txtCodigo" class="form-label">Codigo</label>
                <input type="text" class="form-control" id="txtCodigo" name="txtCodigo">
            </div>
            <div class="mb-3">
                <label for="txtNombre" class="form-label">Firma</label>
                <input class="form-control" type="file" id="firma">
            </div>
            <div id="userStatusZone" class="mb-3">
                <label for="genero" class="form-label">Estado</label>
                <select class="form-control" name="userStatus" id="userStatus">
                    <option value="2">Inactivo</option>
                    <option value="1">Activo</option>
                </select>
            </div>
            
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Crear</button>
        </form>
      </div>
    </div>
  </div>
</div>