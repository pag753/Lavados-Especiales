<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<nav class="navbar">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/logo.png" data-active-url="<?php echo base_url()?>img/logo-active.png" alt=""></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right main-nav">
        <li class="dropdown">
          <a href="#" class="btn btn-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cortes<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url()?>index.php/gestion/alta" class="btn btn-gray">Alta de corte y entrada</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="btn btn-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Salidas<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url()?>index.php/gestion/salidaInterna" class="btn btn-gray">Salida interna</a></li>
          </ul>
          <ul class="dropdown-menu">
            <li><a href="#" class="btn btn-gray">Salida externa</a></li>
          </ul>
        </li>
        <li><a href="<?php echo base_url()?>index.php/gestion/reportes" class="btn btn-blue">Reportes</a></li>
        <li><a href="<?php echo base_url()?>index.php/gestion/cambiarPass" class="btn btn-blue">Cambiar Contraseña</a></li>
        <?php if ($_SESSION['id']==5): ?>
          <li><a href="<?php echo base_url()?>index.php/root/" class="btn btn-blue">Regresar</a></li>
        <?php endif ?>
        <li><a href="<?php echo base_url()?>index.php/gestion/cerrar_sesion" class="btn btn-blue">Cerrar Sesión</a></li>
      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </div>
</nav>
