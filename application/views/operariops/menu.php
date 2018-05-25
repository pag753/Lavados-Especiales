<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/logo.png" data-active-url="<?php echo base_url()?>img/logo-active.png" alt=""></a>
  <button class="navbar-toggler" type="button"  data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> <?php echo $_SESSION['username'] ?></a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/cambiarPass">Cambiar contraseña</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/datos">Cambiar datos personales</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/cerrar_sesion">Cerrar Sesión</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cortes</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/insertar">Insertar producción de proceso seco</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/insertarReproceso">Insertar producción de reproceso</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/ver">Ver producción</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Otros</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operariops/verAhorro">Ver caja de ahorro</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
