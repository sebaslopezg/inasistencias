<!-- MODAL -->
<div class="modal fade" id="crearExcepcionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #00AA00;">
                <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">Agregar Excepcion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmCrearExcepcion" method="post" enctype="multipart/form-data">
                    <div class="row mb-2 mt-2">
                    <input type="number"  class="form-control" id="txtExcepcionId" name="idExcepcion" hidden>
                        <div class="col-12 mb-2">
                            <select class="form-select" id="selectExepcion" name="txtTipoExcepcion" aria-label="Default select example" required>
                                <option value="" disabled selected>Seleccione el tipo de Excepcion</option>
                                <option value="1">Aprendiz</option>
                                <option value="2">Ficha</option>
                                <option value="3">Global</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1" id="slcts" style="display:none;">
                        <div class="col-6 mb-2" id="divFicha" style="display:none;">
                            <select class="ficha form-select" id="ficha" name="txtFicha" value="null" aria-label="Default select example">
                                <option value="" disabled selected>Seleccione la Ficha</option>
                            </select>
                        </div>
                        <div class="col-6 mb-2" id="divAprendiz" style="display:none;">
                            <select class="aprendiz form-select" id="aprendiz" name="txtAprendiz" value="null" aria-label="Default select example">
                                <option value="" disabled selected>Seleccione el Aprendiz</option>

                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class=" col-6 mb-2">
                            <label for="txtFechaInicio" class="form-label" style="margin-bottom: -20%;">Fecha Inicio</label>
                            <input type="datetime-local" min="<?php echo date('Y-m-d H:i'); ?>" class="form-control" id="txtFechaInicio" name="txtFechaInicio" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="txtFechaFin" class="form-label" style="margin-bottom: -20%;">Fecha Fin</label>
                            <input type="datetime-local" min="<?php echo date('Y-m-d H:i'); ?>" class="form-control" id="txtFechaFin" name="txtFechaFin" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="txtMotivo" name="txtMotivo" style="height: 100px" required></textarea>
                                <label for="floatingTextarea2">Motivo de la excepcion</label>
                            </div>
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
<!-- FIN DE MODAL DE AGREGAR EXCEPCIONES -->