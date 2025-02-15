  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'dashboard' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/dashboard">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Pagina Inasistencias -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'inasistencias' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/inasistencias">
          <i class="bi bi-person-x"></i>
          <span>Inasistencias</span>
        </a>
      </li>

      <?php if ($_SESSION['userData']['rol'] == 'INSTRUCTOR') : ?>
        <!-- Gestion de horarios -->
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Gestion de Horarios</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a class="nav-link <?= $data['page_name'] != 'horario' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/horario">
                <i class="bi bi-circle"></i>
                <span>Cargar Horarios</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url() ?>/horario/gestionar">
                <i class="bi bi-circle"></i><span>Gestionar horarios</span>
              </a>
            </li>
          </ul>
        </li><!-- End Gestion de horarios -->
      <?php endif; ?>

      <?php if ($_SESSION['userData']['rol'] == 'COORDINADOR') : ?>

      <!-- Pagina Gestion de Fichas -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'fichas' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/fichas">
        <i class="bi bi-journals"></i>
          <span>Fichas</span>
        </a>
      </li>

      <?php if ($_SESSION['userData']['rol'] == 'INSTRUCTOR' || $_SESSION['userData']['rol'] == 'APRENDIZ') : ?>
        <!-- Pagina Gestion de Excusas -->
        <li class="nav-item">
          <a class="nav-link <?= $data['page_name'] != '' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/excusas">
            <i class="bi bi-person-x"></i>
            <span>Excusas</span>
          </a>
        </li>
      <?php endif; ?>


      <?php if ($_SESSION['userData']['rol'] == 'COORDINADOR') : ?>
        <!-- Pagina Gestion de Excepciones -->
        <li class="nav-item">
          <a class="nav-link <?= $data['page_name'] != '' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/excepciones">
            <i class="bi bi-person-x"></i>
            <span>Exepciones</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($_SESSION['userData']['rol'] == 'COORDINADOR') : ?>
        <!-- Pagina de usuarios -->
        <li class="nav-item">
          <a class="nav-link <?= $data['page_name'] != 'usuarios' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/usuarios">
            <i class="bi bi-people"></i>
            <span>Usuarios</span>
          </a>
        </li>
      <?php endif; ?>


      <!-- Pagina de ingresos -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'ingresos' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/ingresos">
          <i class="bi bi-journal-plus"></i>
          <span>Ingresos</span>
        </a>
      </li>
      <!-- Pagina de ingresos -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'informes' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/informes">
          <i class="bi bi-file-earmark-bar-graph"></i>
          <span>Informes</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?= base_url() ?>/logout">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Salir</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->