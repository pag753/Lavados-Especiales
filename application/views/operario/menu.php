<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/logo.png" data-active-url="<?php echo base_url()?>img/logo-active.png" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i><?php strtoupper($_SESSION['username']) ?></a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/welcome/cambiarPass">Cambiar contraseña</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/welcome/cambiarDatos">Cambiar datos personales</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/welcome/cerrarSesion">Cerrar Sesión</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cortes</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/insertar">Insertar producción de proceso seco</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/insertarReproceso">Insertar producción de reproceso</a>
          <?php if ($_SESSION['id'] == 4):?>
            <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/alta">Cerrar proceso</a>
            <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/cerrarReproceso">Cerrar reproceso</a>
          <?php endif; ?>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/ver">Ver producción</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Otros</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/verAhorro">Ver caja de ahorro</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/operario/verNominas">Ver información de nóminas</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
