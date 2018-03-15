<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/logo.png" data-active-url="<?php echo base_url()?>img/logo-active.png" alt=""></a>
  <button class="navbar-toggler" type="button"  data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cortes</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/costos">Reasignación de costos</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Catálogos</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/1">Clientes</a>
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/2">Lavados</a>
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/3">Maquileros</a>
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/4">Marcas</a>
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/5">Procesos Secos</a>
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/6">Usuarios</a>
          <a class="dropdown-item" href="<?php echo base_url()?>index.php/administracion/catalogos/7">Tipos de pantalón</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url()?>index.php/administracion/cerrar_sesion">Cerrar Sesión</a>
      </li>
    </ul>
  </div>
</nav>
