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
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/gestion/alta">Alta de corte</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/ver">Ver detalles de corte</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/modificar">Modificar corte</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/costos">Reasignación de costos a procesos</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/descuentos">Descuentos</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/ojal">Costo de ojal</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/reproceso">Nuevo reproceso</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/produccion/autorizar">Autorizar Corte</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/produccion/primerProceso">Abrir primer proceso de cargas</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Catálogos</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosClientes">Clientes</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosLavados">Lavados</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosMaquileros">Maquileros</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosMarcas">Marcas</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosProcesos">Procesos Secos</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosUsuarios">Usuarios</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosTipos">Tipos de pantalón</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/catalogosPuestos">Puestos</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reportes</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/reporteCostos">Reporte de costos</a>
          <!--<a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/reporte2">Reporte de periodo de producción</a>-->
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/nomina">Nómina</a>
          <!--<a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/reporte4">Reporte de producción de trabajador</a>-->
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/reporteOjal">Reporte de cortes con ojal</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/gestion/reportes">Reportes de estado de cortes</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Salidas</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/gestion/salidaInterna">Salida interna</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/gestion/salidaAlmacen">Entrega a almacen</a>
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/gestion/salidaExterna">Entrega externa</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Otros</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php echo base_url() ?>index.php/administracion/ahorros">Ahorros</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
