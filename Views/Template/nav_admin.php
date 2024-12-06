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

      <!-- Pagina Gestion de horarios -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'horario' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/horario">
          <i class="bi bi-person-x"></i>
          <span>Horario</span>
        </a>
      </li>
      
      <!-- Pagina de usuarios -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'usuarios' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/usuarios">
          <i class="bi bi-people"></i>
          <span>Usuarios</span>
        </a>
      </li>
      
      <!-- Pagina de ingresos -->
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'ingresos' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/ingresos">
          <i class="bi bi-journal-plus"></i>
          <span>Ingresos</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?= base_url() ?>/salir">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Salir</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->