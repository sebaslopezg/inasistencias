<?php
header_admin($data);
getModal('fichasModal', $data);
?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $data['page_title'] ?>
            <button type="button" id="btnCrearFicha" class="btn btn-primary" style="display: none;">
            <i class="bi bi-plus-lg"></i> Crear Ficha
            </button>
            <button type="button" id="btnAccion" class="btn btn-primary">
                <i class="bi bi-arrow-return-right"></i> Gestion de Fichas
            </button>
            <button type="button" id="btnAccionVolver" class="btn btn-primary" style="display: none;">
                Volver <i class="bi bi-arrow-90deg-left"></i>
            </button>
        </h1>

    </div>
    <div class="card" id="cardTabla" style="display: block;">
        <div class="card-header bg bg-dark text-white">
            <h4 class="subTitle" style="text-align: center ;">Fichas </h4>
        </div>
        <div class="card-body">
            <table id="tablaFichasView" class="table">
                <thead>
                    <tr>
                        <th>Nombre de Ficha</th>
                        <th>Numero de Ficha</th>
                        <th>Estado</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card" id="cardTablaEditar" style="display: none;">
        <div class="card-header bg bg-dark text-white">
            <h4 class="subTitle" style="text-align: center ;"> Modificar Fichas </h4>
        </div>
        <div class="card-body">
            <table id="tablaFichas" class="table">
                <thead>
                    <tr>
                        <th>Nombre de Ficha</th>
                        <th>Numero de Ficha</th>
                        <th>Estado</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</main><!-- End #main -->
<?php footer_admin($data); ?>