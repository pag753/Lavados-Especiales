<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Lavados especiales</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="msapplication-TileColor" content="#00a8ff"/>
  <meta name="msapplication-config" content="<?php echo base_url()?>img/favicons/browserconfig.xml"/>
  <meta name="theme-color" content="#ffffff"/>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url()?>img/favicons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url()?>img/favicons/apple-touch-icon-60x60.png">
  <link rel="icon" type="image/png" href="<?php echo base_url()?>img/favicons/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo base_url()?>img/favicons/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="<?php echo base_url()?>img/favicons/manifest.json">
  <link rel="shortcut icon" href="<?php echo base_url()?>img/favicons/favicon.ico">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/tcal.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/normalize.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/owl.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/cardio.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>fonts/font-awesome-4.1.0/css/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>fonts/eleganticons/et-icons.css"/>
  <script src="<?php echo base_url()?>js/tcal.js"></script>
  <script src="<?php echo base_url()?>js/jquery.js"></script>
  <script src="<?php echo base_url()?>js/bootstrap.min.js"></script>
  <script src="<?php echo base_url()?>js/owl.carousel.min.js"></script>
  <script src="<?php echo base_url()?>js/wow.min.js"></script>
  <script src="<?php echo base_url()?>js/typewriter.js"></script>
  <script src="<?php echo base_url()?>js/jquery.onepagenav.js"></script>
  <script src="<?php echo base_url()?>js/main.js"></script>
</head>

<body>
  <div class="preloader">
    <img src="<?php echo base_url()?>img/loader.gif" alt="Preloader image"/>
  </div>
  <header id="intro" >
    <nav class="navbar" role="navigation">
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
          <ul class="nav navbar-nav navbar-right">
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
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="row">
            <div class="col-md-12 text-center">
              <h3 class="light blue"><?php echo $texto1; ?></h3>
              <h1 class="blue typed"><?php echo $texto2; ?></h1>
              <span class="typed-cursor">|</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mobile-nav">
      <ul>
      </ul>
      <a href="#" class="close-link"><i class="arrow_up"></i></a>
    </div>
  </header>
</body>
</html>
