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
        <li><a href="<?php echo base_url()?>index.php/Gestion/reportes" class="btn btn-blue">Reportes de inventarios</a></li>
        <li><a href="<?php echo base_url()?>index.php/Gestion/reportes" class="btn btn-blue">Reportes de costos</a></li>
        <li><a href="#" class="btn btn-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reporte de Producción</a></li>
        <li class="dropdown">
          <a href="#" class="btn btn-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cambiar rol<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url()?>index.php/administracion" class="btn btn-gray">Administración</a></li>
            <li><a href="<?php echo base_url()?>index.php/gestion" class="btn btn-gray">Gestión</a></li>
            <li><a href="<?php echo base_url()?>index.php/produccion" class="btn btn-gray">Producción</a></li>
            <li><a href="<?php echo base_url()?>index.php/operario" class="btn btn-gray">Encargado de proceso seco</a></li>
            <li><a href="<?php echo base_url()?>index.php/operariops" class="btn btn-gray">Operario de proceso seco</a></li>
          </ul>
          <li><a href="<?php echo base_url()?>index.php/root/cerrar_sesion" class="btn btn-blue">Cerrar Sesión</a></li>
        </ul>
      </li>
    </div>
    <!-- /.navbar-collapse -->
  </div>
</nav>
