<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/logo.png" data-active-url="<?php echo base_url()?>img/logo-active.png" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> <?php echo strtoupper($_SESSION['username']) ?></a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/welcome/cambiarPass">Cambiar contraseña</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/welcome/cambiarDatos">Cambiar datos personales</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/welcome/cerrarSesion">Cerrar Sesión</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cortes</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/produccion/autorizar">Autorizar corte</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/produccion/primerProceso">Abrir primer proceso de cargas</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
