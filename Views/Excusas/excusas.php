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
    <div class="card-header" style="background-color: #00AA00;">
            <h3 class="card-title text-light">Excusas</h3>
          </div>
        <div class="card-body table-responsive">
            <table id="tablaExcusas" class="table table-bordered table-striped" >
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