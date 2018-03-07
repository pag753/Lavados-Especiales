<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Lavados especiales</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="msapplication-TileColor" content="#00a8ff"/>
    <meta name="msapplication-config" content="<?php echo base_url()?>img/favicons/browserconfig.xml"/>
    <meta name="theme-color" content="#ffffff"/>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
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
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <?php
          $cadena= $folio.'_'.$corte.'_'.$marca.'_'.$maquilero.'_'.$cliente.'_'.$tipo.'_'.$piezas.'_'.$fecha;
        ?>
        <script>
          $(document).ready(function(){
            $('#boton').click(function(){
              alert("Folio: "+$("#folio").val()+
                  "\nCorte: "+$('#corte').val()+
                  "\nMarca: "+$('#marca').val()+
                  "\nMaquilero: "+$('#maquilero').val()+
                  "\nCliente: "+$('#cliente').val()+
                  "\nTipo: "+$('#tipo').val()+
                  "\nFecha de entrada: "+$('#fecha').val()+
                  "\nPiezas: "+$('#piezas').val());
            });
          });
        </script>
        <div class="container">
          <div class="table">
            <div class="header-text">
              <div class="modal-dialog">
                <div class="modal-content modal-popup">
                  <h3 class="white"><?php echo $texto1 ?></h3>
                  <h4 class="white"><?php echo $texto2 ?></h4>
                  <form action="<?php echo base_url(); ?>index.php/administracion/costos/<?php echo $folio."_".$carga ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="folio" id="folio" value="<?php echo $folio; ?>"/>
                    <input type="hidden" name="corte" id="corte" value="<?php echo $corte; ?>"/>
                    <input type="hidden" name="marca" id="marca" value="<?php echo $marca; ?>"/>
                    <input type="hidden" name="maquilero" id="maquilero" value="<?php echo $maquilero; ?>"/>
                    <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente; ?>"/>
                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>"/>
                    <input type="hidden" name="piezas" id="piezas" value="<?php echo $piezas; ?>"/>
                    <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>"/>
                    <input type="hidden" name="carga" id="carga" value="<?php echo $carga; ?>"/>
                    <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado; ?>"/>
                    <center>
                      <button type="button" name="informacion" id="boton">Ver detalles</button>
                    </center>
                    <table class="bootstrap">
                      <tr>
                        <th>
                          <center>
                            <label><h5 class="white">Folio: <?php echo $folio; ?></h5></label>
                          </center>
                        </th>
                        <th>
                          <center>
                            <h5 class="white"><?php echo strtoupper($lavado) ?></h5>
                          </center>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <center>
                            <h5 class="white">Proceso</h5>
                          </center>
                        </th>
                        <th>
                          <center>
                            <h5 class="white">Costo $</h5>
                          </center>
                        </th>
                      </tr>
                      <?php foreach ($procesos as $key => $value): ?>
                        <tr>
                          <th>
                            <input type="text" readonly="true" name="proc[<?php echo $key ?>]" value="<?php echo strtoupper($value) ?>"/>
                          </th>
                          <th>
                            <input type="number" step="any" required placeholder="Inserte costo" name="costo[<?php echo $key ?>]" value="<?php echo $costos[$key] ?>">
                          </th>
                        </tr>
                      <?php endforeach; ?>
                    </table>
                    <input type="submit" class="bootstrap" value="Aceptar"/>
                  </form>
                </div>
                <!-- Holder for mobile navigation -->
              </div>
            </div>
          </div>
        </div>
      </header>
    </body>
  </head>
</html>
