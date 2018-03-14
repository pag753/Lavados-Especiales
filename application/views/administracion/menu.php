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
                <li><a href="<?php echo base_url()?>index.php/administracion/costos" class="btn btn-gray">Reasignación de costos</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="btn btn-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Catálogos<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/1" class="btn btn-gray">Clientes</a></li>
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/2" class="btn btn-gray">Lavados</a></li>
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/3" class="btn btn-gray">Maquileros</a></li>
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/4" class="btn btn-gray">Marcas</a></li>
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/5" class="btn btn-gray">Procesos Secos</a></li>
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/6" class="btn btn-gray">Usuarios</a></li>
                <li><a href="<?php echo base_url()?>index.php/administracion/catalogos/7" class="btn btn-gray">Tipos de pantalón</a></li>
              </ul>
            </li>
            <?php if ($_SESSION['id']==5): ?>
              <li><a href="<?php echo base_url()?>index.php/root/" class="btn btn-blue">Regresar</a></li>
            <?php endif ?>
            <li><a href="<?php echo base_url()?>index.php/administracion/cerrar_sesion" class="btn btn-blue">Cerrar Sesión</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
    </nav>
