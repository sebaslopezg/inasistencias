<?php header_admin($data);
getModal('excusasModal', $data);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $data['page_title'] ?>

        </h1>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Excusas</h5>

            <table id="tablaExcusas" class="table">
                <thead>
                    <tr>
                        <th>Fecha Inasistencia</th>
                        <th>Aprendiz</th>
                        <th>Instructor</th>
                        <th>Estado</th>
                        <th>Excusa</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
</main>

<?php footer_admin($data); ?>