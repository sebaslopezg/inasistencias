<!-- Modal -->
<div class="modal fade" id="crearFichaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h2 class="modal-title fs-5" id="exampleModalLabel" style="text-align: center; display: flex;">REGISTRAR FICHA NUEVA </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white;"> <i class="bi bi-x-lg"></i> </button>
            </div>
            </>
            <div class="modal-body">
                <form id="frmCrearFicha" method="POST">
                    <input type="hidden" name="txtIdFicha" id="txtIdFicha" value="0">
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="txtNombre" class="form-label">Nombre de la Ficha.</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>
                        <div class="mb-3 col-6">
                            <label for="txtNumeroFicha" class="form-label">Numero de Ficha</label>
                            <input type="number" class="form-control" id="txtNumeroFicha" name="txtNumeroFicha">
                        </div>
                    </div>
                    <div id="userStatusZone" class="mb-3">
                        <label for="" class="form-label">Estado</label>
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



<!-- Modal de Informacion de la Ficha -->
<div class="modal fade" id="infoFichaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <div class="titulo">
                    <h2 class="modal-title fs-5" id="exampleModalLabel" style="text-align: center; display: flex;">INFORMACION DE LA FICHA</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="btnCerrarModal" aria-label="Close" style="color: white;"> <i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <!-- Default Tabs -->
                    <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#instructores-justified" type="button" role="tab" aria-controls="home" aria-selected="true" style="background-color:dimgrey; color:white;font-size:larger">Instructores</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#aprendices-justified" type="button" role="tab" aria-controls="contact" aria-selected="false" style=" background-color:ghostwhite; color: dimgrey; font-size:larger; ">Aprendices</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2" id="myTabjustifiedContent">
                        <div class="tab-pane fade show active" id="instructores-justified" role="tabpanel" aria-labelledby="home-tab">

                            <div class="card mt-2">
                                <div class="card-body">
                                    <table class="table table-bordered ">
                                        <thead id="tituloModal">

                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" scope="col"><i class="bi bi-person-circle"></i> Instructores</th>
                                                <th style="text-align: center;" scope="col"> <i class="bi bi-envelope-at-fill"></i> Correo </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaInfoInstructor">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="aprendices-justified" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="card mt-2 ">
                                <div class="card-body">
                                    <table class="table table-bordered ">
                                        <thead id="tituloModal">

                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" scope="col"><i class="bi bi-mortarboard-fill"></i> Aprendiz</th>
                                                <th style="text-align: center;" scope="col"><i class="bi bi-envelope-at-fill"></i> Correo</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaInfoAprendiz">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>