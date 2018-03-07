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
    <link href="<?php echo base_url()?>css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url()?>css/bootstrap.css" rel="stylesheet"/>
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url()?>img/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url()?>img/favicons/apple-touch-icon-60x60.png">
    <link rel="icon" type="image/png" href="<?php echo base_url()?>img/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo base_url()?>img/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="<?php echo base_url()?>img/favicons/manifest.json">
    <link rel="shortcut icon" href="<?php echo base_url()?>img/favicons/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/tcal.css" />
    <!-- Normalize -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/normalize.css"/>
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.css"/>
    <!-- Owl -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/owl.css"/>
    <!-- Animate.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>fonts/font-awesome-4.1.0/css/font-awesome.min.css"/>
    <!-- Elegant Icons -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>fonts/eleganticons/et-icons.css"/>
    <!-- Main style -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/cardio.css"/>

    <script src="<?php echo base_url()?>js/tcal.js"></script>
  	<script src="<?php echo base_url()?>js/jquery.js"></script>
  	<script src="<?php echo base_url()?>js/bootstrap.min.js"></script>
  	<script src="<?php echo base_url()?>js/jquery-1.11.1.min.js"></script>
  	<script src="<?php echo base_url()?>js/owl.carousel.min.js"></script>
  	<script src="<?php echo base_url()?>js/wow.min.js"></script>
  	<script src="<?php echo base_url()?>js/typewriter.js"></script>
  	<script src="<?php echo base_url()?>js/jquery.onepagenav.js"></script>
  	<script src="<?php echo base_url()?>js/main.js"></script>

    <body>
      <div class="preloader">
        <img src="<?php echo base_url()?>img/loader.gif" alt="Preloader image"/>
      </div>
      <header id="intro" >
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
        <script src="<?php echo base_url() ?>js/jquery.js"></script>
        <?php
          $input_fechai = array('name'    => 'fechai',
                                'id'      => 'fechai',
                                'type'    => 'text',
                                'class'   => 'tcal',
                                'value'   => set_value('fecha',date("d/m/Y")),
                                'readonly'=> 'true',);

          $input_fechaf = array('name'    => 'fechaf',
                                'id'      => 'fechaf',
                                'type'    => 'text',
                                'class'   => 'tcal',
                                'value'   => set_value('fecha',date("d/m/Y")),
                                'readonly'=> 'true',);
        ?>
        <div class="container">
          <div class="table">
            <div class="header-text">
              <div class="modal-dialog">
                <h3 class="black">Generar reporte</h3>
                <form action="<?php echo base_url(); ?>index.php/Gestion/generaReporte/" method="post" enctype="multipart/form-data" target="_blank">
                  <table class="table table-striped" name="tabla" id="tabla">
                    <caption>Ingrese los datos</caption>
                    <thead>
                      <tr>
                        <th><center>Descripción</center></th>
                        <th><center>Valor</center></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Tipo de reporte</td>
                        <td>
                          <select name="reporte" id="reporte">
                            <option value="1">Cortes en almacen</option>
                            <option value="2">Cortes autorizados</option>
                            <option value="3">Cortes entregados</option>
                            <option value="4">Cortes en proceso</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Fecha de inicio</td>
                        <td><?php echo form_input($input_fechai); ?></td>
                      </tr>
                      <tr>
                        <td>Fecha final</td>
                        <td><?php echo form_input($input_fechaf); ?></td>
                      </tr>
                      <tr>
                        <td>Corte</td>
                        <td><input type="text" name="corte" placeholder="Escribir si es necesario"></td>
                      </tr>
                      <tr>
                        <td>Folio</td>
                        <td><input type="text" name="folio" placeholder="Escribir si es necesario"></td>
                      </tr>
                      <tr>
                        <td>Cliente</td>
                        <td>
                          <select name="cliente_id">
                            <option value="0">ESCOJA SOLO SI ES NECESARIO</option>
                            <?php foreach ($clientes as $key => $value): ?>
                              <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Marca</td>
                        <td>
                          <select name="marca_id">
                            <option value="0">ESCOJA SOLO SI ES NECESARIO</option>
                            <?php foreach ($marcas as $key => $value): ?>
                              <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Maquilero</td>
                        <td>
                          <select name="maquilero_id">
                            <option value="0">ESCOJA SOLO SI ES NECESARIO</option>
                            <?php foreach ($maquileros as $key => $value): ?>
                              <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Tipo de pantalón</td>
                        <td>
                          <select name="tipo_pantalon_id">
                            <option value="0">ESCOJA SOLO SI ES NECESARIO</option>
                            <?php foreach ($tipos as $key => $value): ?>
                              <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Incluir imágenes</td>
                        <td><input type="checkbox" name="check"></td>
                      </tr>
                    </tbody>
                  </table>
                  <input type="submit" name="aceptar" value="Aceptar">
                </form>
                <!-- Holder for mobile navigation -->
              </div>
            </div>
          </div>
        </div>
      </header>
    </body>
  </head>
</html>
