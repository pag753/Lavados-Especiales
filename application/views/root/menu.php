<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/logo.png" data-active-url="<?php echo base_url()?>img/logo-active.png" alt=""></a>
  <button class="navbar-toggler" type="button"  data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="<?php echo base_url()?>index.php/Gestion/reportes" class="btn btn-blue">Reportes de inventarios</a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url()?>index.php/Gestion/reportes">Reportes de costos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Reporte de Producción</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url()?>index.php/root/cerrar_sesion">Cerrar Sesión</a>
      </li>
    </ul>
  </div>
</nav>
