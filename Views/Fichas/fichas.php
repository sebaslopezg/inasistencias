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
        <div class="card-body">
            <h5 class="card-title"></h5>
            <!-- Slides with fade transition -->

            <div id="carouselExampleFade" class="carousel slide carousel-fade">
                <div class="carousel-inner">
                    <div class="carousel-item active">
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
                    <div class="carousel-item">
                        <div class="card-header bg bg-dark text-white">
                            <h4 class="subTitle" style="text-align: center ;"> Gestion de Pesonal de Formacion </h4>
                        </div>
                        <div class="card-body">
                            <table id="" class="table">
                            </table>

                            <div class="card">
                                <div class="card-body">
                                    <!-- Bordered Tabs Justified -->
                                    <ul class="nav nav-tabs nav-tabs-bordered d-flex mb-3" id="borderedTabJustified" role="tablist">
                                        <li class="nav-item flex-fill" role="presentation">
                                            <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Asignar</button>
                                        </li>
                                        <li class="nav-item flex-fill" role="presentation">
                                            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                                        </li>
                                        <li class="nav-item flex-fill" role="presentation">
                                            <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Modificar</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                                        <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
                                            <form id="form-Ficha mb-3" method="POST" action="">
                                                <input type="hidden" name="idFicha" id="idFicha" value="0">
                                                <div class="card-body ">
                                                    <div class="form-group">
                                                        <input type="text" class="ficha form-control mb-2" id="ficha" name="search_Ficha" placeholder="Ingrese el nombre de la ficha..">
                                                    </div>
                                                    <table class="table table-bordered mb-3" style="border: solid white;" id="tabla-Ficha">
                                                        <thead>
                                                            <tr>
                                                                <th><i class="fas fa-solid fa-user"></i> Numero de Ficha</th>
                                                                <th><i class="fas fa-solid fa-id-card"></i> Nombre de la Ficha</th>
                                                                <th><i class="fas fa-solid fa-id-card"></i> Accion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Numero de Ficho</td>
                                                                <td>Analis y Desarrollo de Software</td>
                                                                <td>Funcionalidad.</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" style="background-color:#30465e; color:#eeb063; font-family:Verdana, Geneva, Tahoma, sans-serif " class="btn btn-">Asigar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
                                            Nesciunt totam et. Consequuntur magnam aliquid eos nulla dolor iure eos quia. Accusantium distinctio omnis et atque fugiat. Itaque doloremque aliquid sint quasi quia distinctio similique. Voluptate nihil recusandae mollitia dolores. Ut laboriosam voluptatum dicta.
                                        </div>
                                        <div class="tab-pane fade" id="bordered-justified-contact" role="tabpanel" aria-labelledby="contact-tab">
                                            Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
                                        </div>
                                    </div><!-- End Bordered Tabs Justified -->

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!-- End Slides with fade transition -->
            <button class="btn btn- mb-2" data-bs-target="#carouselExampleFade" data-bs-slide="prev" style="background-color: black; color:white "><i class="bi bi-arrow-left"></i> Atras </button>
            <button class="btn btn- mb-2 " data-bs-target="#carouselExampleFade" data-bs-slide="next" style="background-color: black; color:white;margin-left:85% ; ">Siguiente <i class="bi bi-arrow-right"></i></button>
        </div>
    </div>
    <div class="card">

    </div>
</main><!-- End #main -->
<?php footer_admin($data); ?>