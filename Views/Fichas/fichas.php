<?php
header_admin($data);
getModal('fichasModal', $data);
?>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>
      <?= $data['page_title'] ?>
      <button type="button" id="btnCrearFicha" class="btn btn-success" style="display: none">
        <i class="bi bi-plus-lg"></i> Crear Ficha
      </button>

      <button type="button" id="btnAccionVolver" class="btn btn-primary" style="display: none">
        Volver <i class="bi bi-arrow-90deg-left"></i>
      </button>
      <button type="button" id="btnAccion" class="btn btn-primary">
        <i class="bi bi-tools"></i> Gestion de Fichas
      </button>
    </h1>
  </div>
  <div class="card" id="cardTabla" style="display: block">
    <div class="card-header bg bg-dark text-white">

      <h4 class="subTitle" style="text-align: center">Fichas</h4>
    </div>
    <div class="card-body">
      <table id="tablaFichasView" class="table">
        <thead>
          <tr>
            <th scope="col">Nombre de Ficha</th>
            <th scope="col"> Numero de Ficha</th>
            <th scope="col">Estado</th>
            <th scope="col">Info</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="card" id="cardTablaEditar" style="display: none">
    <!-- Slides with fade transition -->
    <div id="carouselExampleFade" class="carousel slide carousel-fade">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="card-header bg bg-dark text-white">
            <h4 class="subTitle" style="text-align: center">Modificar Fichas</h4>
          </div>
          <div class="card-body">
            <table id="tablaFichas" class="table">
              <thead>
                <tr>
                  <th scope="col">Nombre de Ficha</th>
                  <th scope="col">Numero de Ficha</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Accion</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="carousel-item">
          <div class="card-header bg bg-dark text-white">
            <h4 class="subTitle" style="text-align: center">Gestion de Pesonal de Formacion</h4>
          </div>
          <div class="card-body">
            <div class="card">
              <div class="card-body">
                <!-- Bordered Tabs Justified -->
                <ul
                  class="nav nav-tabs nav-tabs-bordered d-flex mb-3"
                  id="borderedTabJustified"
                  role="tablist">
                  <li class="nav-item flex-fill" role="presentation">
                    <button
                      class="nav-link w-100 active"
                      id="home-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#bordered-justified-home"
                      type="button"
                      role="tab"
                      aria-controls="home"
                      aria-selected="true">
                      Asignar
                    </button>
                  </li>

                  <li class="nav-item flex-fill" role="presentation">
                    <button
                      class="nav-link w-100"
                      id="contact-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#bordered-justified-contact"
                      type="button"
                      role="tab"
                      aria-controls="contact"
                      aria-selected="false">
                      Modificar
                    </button>
                  </li>
                </ul>
                <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                  <div
                    class="tab-pane fade show active"
                    id="bordered-justified-home"
                    role="tabpanel"
                    aria-labelledby="home-tab">
                    <form id="form-Ficha" method="POST" action="">
                      <input type="hidden" name="idFicha" id="idFicha" value="0" />
                      <div class="card-body">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-8">
                              <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <label for="ficha"></label>
                                <input
                                  type="text"
                                  class="form-control"
                                  id="ficha"
                                  name="search_Ficha"
                                  placeholder="Ingrese el nombre de la ficha.."
                                  aria-describedby="basic-addon1" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <table
                          class="table table-bordered mb-3 mt-2"
                          style="border: solid white"
                          id="tabla-Ficha">
                          <thead>
                            <tr>
                              <th scope="col"><i class="bi bi-journal-bookmark"></i> Ficha</th>
                              <th scope="col" style="text-align: center; "><i class="bi bi-person-fill"></i> Instructores</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                      <div class="card-footer">
                        <button
                          type="submit"
                          id="btnEnviar"
                          style="
                              background-color:green;
                              color: white;
                              font-family: Verdana, Geneva, Tahoma, sans-serif;
                            "
                          class="btn btn-"><i class="bi bi-check-circle-fill"></i>
                          Asigar
                          <i class="bi bi-check-circle-fill"></i>
                        </button>
                      </div>
                    </form>
                  </div>
                  <div
                    class="tab-pane fade"
                    id="bordered-justified-contact"
                    role="tabpanel"
                    aria-labelledby="contact-tab">

                    <table class="table table-bordered"
                      id="tabla-infoFicha-Mod">
                      <thead>
                        <tr>
                          <th scope="col" style="text-align: center;"><i class="bi bi-person-fill"></i> Ficha</th>
                          <th scope="col" style="text-align: center;"><i class="bi bi-person-fill"></i> Numero</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                    <table
                      class="table table-bordered"
                      id="tabla-instructores-Mod">
                      <thead>
                        <tr>
                          <th scope="col"><i class="bi bi-person-fill"></i> Instructores</th>
                          <th scope="col"><i class="bi bi-chat-right-text"></i> Contacto </th>
                          <th>Accion</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- End Bordered Tabs Justified -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Slides with fade transition -->
    <button
      class="btn btn- mb-2"
      data-bs-target="#carouselExampleFade"
      data-bs-slide="prev"
      style="background-color: black; color: white; margin-left:1%">
      <i class="bi bi-arrow-left"></i> Atras
    </button>
    <button
      class="btn btn- mb-2"
      data-bs-target="#carouselExampleFade"
      data-bs-slide="next"
      style="background-color: black; color: white;">
      Siguiente <i class="bi bi-arrow-right"></i>
    </button>

    </>
    <div class="card"></div>
</main>
<!-- End #main -->
<?php footer_admin($data); ?>