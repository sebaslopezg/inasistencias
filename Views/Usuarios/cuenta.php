<?php 
header_admin($data); 
?>
  <main id="main" class="main">

  <script>
    const userId = "<?=  $_SESSION['userData']['idUsuarios'];?>"
  </script>

 

  <section class="section profile">
      <div class="row">

        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview" aria-selected="true" role="tab">Resumen</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit" aria-selected="false" tabindex="-1" role="tab">Editar Perfil</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" aria-selected="false" tabindex="-1" role="tab">Cambiar Contraseña</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview" role="tabpanel">
                  <h5 class="card-title">Detalles del perfil</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nombre</div>
                    <div class="col-lg-9 col-md-8" id="displayNombre"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Apellido</div>
                    <div class="col-lg-9 col-md-8" id="displayApellido"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Documento</div>
                    <div class="col-lg-9 col-md-8" id="displayDocumento"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Genero</div>
                    <div class="col-lg-9 col-md-8" id="displayGenero"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Telefono</div>
                    <div class="col-lg-9 col-md-8" id="displayTelefono"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Correo</div>
                    <div class="col-lg-9 col-md-8" id="displayEmail"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Codigo</div>
                    <div class="col-lg-9 col-md-8" id="displayCodigo"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Rol</div>
                    <div class="col-lg-9 col-md-8" id="displayRol"></div>
                  </div>

                  <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label label">Firma</label>
                    <div class="col-md-8 col-lg-9" id="displayFirma">
                      <img src="assets/img/profile-img.jpg" alt="Firma">
                    </div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">

                  <!-- Profile Edit Form -->
                  <form method="post" data-form="editProfile">

                    <div class="row mb-3">
                      <label for="userName" class="col-md-4 col-lg-3 col-form-label">Nombre</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userName" type="text" class="form-control" id="userName" value="">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userApellido" class="col-md-4 col-lg-3 col-form-label">Apellido</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userApellido" type="text" class="form-control" id="userApellido" value="">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userDocument" class="col-md-4 col-lg-3 col-form-label">Documento</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userDocument" type="text" class="form-control" id="userDocument" value="" readonly>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userGenero" class="col-md-4 col-lg-3 col-form-label">Genero</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="col-md-4 col-lg-3 form-control" name="userGenero" id="userGenero">
                            <option value="0">Seleccione el Genero</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userTelefono" class="col-md-4 col-lg-3 col-form-label">Telefono</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userTelefono" type="text" class="form-control" id="userTelefono" value="">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userEmail" class="col-md-4 col-lg-3 col-form-label">Correo Electrónico</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userEmail" type="email" class="form-control" id="userEmail" value="">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userCodigo" class="col-md-4 col-lg-3 col-form-label">Codigo</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="userCodigo" type="text" class="form-control" id="userCodigo" value="" readonly>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="userRol" class="col-md-4 col-lg-3 col-form-label">Rol</label>
                      <div class="col-md-8 col-lg-9">
                        <input readonly name="userRol" type="text" class="form-control" id="userRol" value="">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Firma</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="assets/img/profile-img.jpg" alt="Firma">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>


                <div class="tab-pane fade pt-3" id="profile-change-password" role="tabpanel">
                  <!-- Change Password Form -->
                  <form method="post" data-form="editPass">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña Actual</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="currentPassword" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nueva Contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Repetir contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewPassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->
  <?php footer_admin($data); ?>