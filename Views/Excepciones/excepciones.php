<?php header_admin($data);
getModal('excepcionesModal', $data);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $data['page_title'] ?>
            <button type="button" id="btnCrearExcepcion" class="btn btn-primary">
                Crear Excepcion
            </button>
        </h1>
        
    </div>

    <div class="card">
    <div class="card-header" style="background-color: #00AA00;">
            <h3 class="card-title text-light">Excepciones</h3> 
    </div>
        <div class="card-body table-responsive">
            <table id="tblExcepciones" class="table table-bordered table-striped" >
                <thead>
                    <tr>
                        <th>Descripcion     </th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin  </th>
                        <th>Aprendiz</th>
                        <th>Ficha</th>
                        <th>Tipo Excepcion</th>
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