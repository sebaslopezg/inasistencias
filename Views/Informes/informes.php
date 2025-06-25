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
        <div class="card-header  bg bg-dark text-white">Informe</div>

        <div class="card-body" id="card-informe" style="display: block;">
            <h5 class="card-title" id="titulo">Búsqueda</h5>
            <div class="row align-items-end g-2 mb-3" id="buscador">
                <!-- Campo de búsqueda -->
                <div class="col-md-8" id="colBuscador">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="ficha" name="search_Ficha" placeholder="Ingrese el nombre de la ficha...">
                    </div>
                </div>

                <!-- Botón limpiar -->
                <div class="col-md-2" id="colLimpiar">
                    <button type="button" id="btnLimpiar" class="btn btn-danger btn-sm w-40 eliminar-fila">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                <!-- Botón asistencia -->
                <div class="col-md-2" id="colAsistencia">
                    <button type="button" id="btnAsistencia" class="btn btn-outline-success w-100" style="display: none;">
                        Asistencia <i class="bi bi-calendar-check"></i>
                    </button>
                </div>
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
            <h5 class="card-title" id="titulo" style="display: flex; flex-direction: column;">Fecha</h5>
            <div class="row align-items-center g-2 mb-3">

                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-search"></i>
                        </span>
                        <label for="filtroFecha" class="visually-hidden">Filtrar por fecha</label>
                        <input type="month" class="form-control" name="filtroFecha" id="filtroFecha" />
                    </div>
                </div>

                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary rounded-circle btn-sm" id="btnFecha">
                        <i class="bi bi-check2"></i>
                    </button>
                </div>

                <div class="col-md-auto ms-auto">
                    <div class="d-flex gap-2">
                        <button type="button" id="btnInasistencia" class="btn btn-outline-info" style="display: none;">
                            Inasistencias <i class="bi bi-calendar-check"></i>
                        </button>
                        <button type="button" id="btnPdf" class="btn btn-danger" style="display: none;">
                            <i class="bi bi-filetype-pdf" style="font-size: larger;"></i>
                        </button>
                    </div>
                </div>
            </div>


            <div class="class-informes" id="informe-asistencia" style="display: none;">
                <table class="table table-bordered" id="tabla-asistencia">
                    <thead>
                        <tr id="colum-info-ficha">
                            <th scope="col" style="text-align: center;"># </th>
                            <th scope="col" style="text-align: center;"> Aprendiz </th>
                            <th scope="col" style="text-align: center;"> Fecha</th>
                        </tr>
                        <tr id="fecha-tr">
                            <th scope="col" id="thVacio" style="text-align: center;"> </th>
                            <th scope="col" id="thVacio" style="text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody id="colum-aprendiz">

                    </tbody>
                </table>
            </div>

        </div>

    </div>

</main>
<!-- End #main -->
<?php footer_admin($data); ?>