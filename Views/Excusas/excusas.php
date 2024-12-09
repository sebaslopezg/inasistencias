<?php header_admin($data);
getModal('excusasModal', $data);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $data['page_title'] ?>
            <button type="button" id="btnCrearExcusa" class="btn btn-primary mt-3 mb-3" style="display: block;">
                Crear Excusa
            </button>
        </h1>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Inasistencias</h5>

            <table id="tablaExcusas" class="table">
                <thead>
                    <tr>
                        <th>Fecha Inasistencia</th>
                        <th>Aprendiz</th>
                        <th>Estado</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
</main>

<?php footer_admin($data); ?>