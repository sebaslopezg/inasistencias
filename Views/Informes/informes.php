<?php
header_admin($data);
getModal('informesModal', $data);
?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $data['page_title'] ?>

        </h1>
    </div>
    <div class="card" style="display: block">
        <div class="card-header  bg bg-dark text-white">

        </div>
        <div class="card-body">
            <div class="card-body" id="card-informe">
                <h5 class="card-title">Busqueda</h5>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-8">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <label for="ficha"></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="ficha"
                                    style="display:block"
                                    name="search_Ficha"
                                    placeholder="Ingrese el nombre de la ficha.."
                                    aria-describedby="basic-addon1" />
                            </div>

                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-x-circle-fill"></i></button>
                        </div>
                    </div>
                    <button type="button" style="float: right; display: none;" id="btnAsistencia" class="btn btn-outline-success mb-3"> Asistencia <i class="bi bi-calendar-check"></i></button>
                    <button type="button" style="float: right; display: none; font-size:larger;" id="btnInasistencia" class="btn btn-outline-info mb-3"> Inasistencias <i class="bi bi-calendar-check"></i></button>
                    <button type="button" style="float: right; display: none; margin-right:5px;" id="btnPdf" class="btn btn-outline-danger mb-3"> <i class="bi bi-filetype-pdf" style="font-size:larger;"></i></button>
                </div>
                <div class="class-informes" id="tabla-informe" style="display: none;">

                    <table class="table table-bordered" id="tabla-infoFicha">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align: center;"><i class="bi bi-person-fill"></i> Ficha</th>
                                <th scope="col" style="text-align: center;"> # Numero</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <table
                        class="table table-bordered"
                        id="tabla-aprendices">
                        <thead>
                            <tr>
                                <th scope="col"><i class="bi bi-mortarboard-fill"></i> Aprendiz</th>
                                <th scope="col"><i class="bi bi-envelope-fill"></i> Correo </th>
                                <th scope="col" style="text-align: center;"><i class="bi bi-ban"></i> Inasistencias </th>
                                <th scope="col" style="text-align: center;"><i class="bi bi-info-circle-fill"></i> Info </th>
                            </tr>
                        </thead>
                        <tbody id="infoAprendiz">
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="card-body" id="cardAsistencias" style="display: none;">
                <div class="form-group">
                    <div class="row" style="display: flex; flex-direction: row;">
                        <div class="col-2 mb-3">
                            <div class="form-group">
                                <label for="Filtro"></label>
                                <input type="month" class="form-control" name="filtroFecha" id="filtroFecha">
                            </div>
                        </div>
                        <div class="col-2  mt-2">
                            <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-x-circle-fill"></i></button>
                        </div>
                    </div>
                </div>

                <div class="class-informes" id="informe-asistencia" style="display: none;">

                    <table class="table table-bordered"
                        id="tabla-asistencia">
                        <thead>
                            <tr id="colum-info-ficha">
                                <th scope="col" style="text-align: center;"># </th>
                                <th scope="col" style="text-align: center;"> Aprendiz </th>
                                <th scope="col" style="text-align: center;"> Fecha</th>
                            </tr>
                            <tr id="fecha-tr">
                                <th scope="col" style="text-align: center;"> </th>
                                <th scope="col" style="text-align: center;"> </th>
                            </tr>
                        </thead>
                        <tbody id="colum-aprendiz">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End #main -->
<?php footer_admin($data); ?>