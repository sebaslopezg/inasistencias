<?php header_admin($data);
getModal('excepcionesModal', $data);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $data['page_title'] ?>
           
        </h1>

    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #00AA00;">
            <h3 class="card-title text-light mb-0">Excepciones</h3>
            <button type="button" id="btnCrearExcepcion" class="btn bg-white text-grey">
               <i class="bi bi-plus-circle"></i> Agregar Excepcion
            </button>
        </div>

        <div class="card-body table-responsive">
            <table id="tblExcepciones" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Motivo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Aprendices</th>
                        <th>Ficha</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
</main>

<?php footer_admin($data); ?>