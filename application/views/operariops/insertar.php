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
</head>
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
                <li><a href="<?php echo base_url()?>index.php/operariops/insertar" class="btn btn-gray">Insertar producción</a></li>
                <li><a href="<?php echo base_url()?>index.php/operariops/ver" class="btn btn-gray">Ver producción</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="btn btn-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Datos<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url()?>index.php/operariops/pass" class="btn btn-gray">Cambiar contraseña</a></li>
                <li><a href="<?php echo base_url()?>index.php/operariops/datos" class="btn btn-gray">Cambiar datos personales</a></li>
              </ul>
            </li>
            <?php if ($_SESSION['id']==5): ?>
              <li><a href="<?php echo base_url()?>index.php/root/" class="btn btn-blue">Regresar</a></li>
            <?php endif ?>
            <li><a href="<?php echo base_url()?>index.php/operariops/cerrar_sesion" class="btn btn-blue">Cerrar Sesión</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
    </nav>
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <?php if (!isset($folio): ?>
      <?php
        $input_folio = array('type'    => 'text',
                             'name'    => 'folio',
                             'id'      => 'folio',
                             'class'   => 'bootstrap',
                             'value'   => set_value('folio',@$folio),);
      ?>
      <script>
        $(document).ready(function(){
          $('#folio').keyup(function(){
            $('#procesos').html('');
            $('#valida').html('');
            $.ajax({url: "<?php echo base_url() ?>index.php/ajax/operarioCargas/"+$('#folio').val(), success: function(result){
              $("#cargas").html(result);
              $( '#carga' ).change(function(){
                $('#valida').html('');
                $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos/'+$('#folio').val()+'_'+$('#carga').val(), success: function(result){
                  $('#procesos').html(result);
                  $( '#proceso' ).change(function(){
                    $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioValida/'+$('#folio').val()+'_'+$('#carga').val()+'_'+$('#proceso').val(), success: function(result){
                      $('#valida').html(result);
                    }});
                  });
                }});
              });
            }});
          });
          $( '#carga' ).change(function(){
            $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos/'+$('#folio').val()+'_'+$('#carga').val(), success: function(result){
              $('#procesos').html(result);
            }});
          });
          $( '#proceso' ).change(function(){
            $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioValida/'+$('#folio').val()+'_'+$('#carga').val()+'_'+$('#proceso').val(), success: function(result){
              $('#valida').html(result);
            }});
          });
        });
      </script>
      <div class="container">
        <div class="table">
          <div class="header-text">
            <div class="modal-dialog">
              <div class="modal-content modal-popup">
                <h3 class="white">Alta de producción</h3>
                <h4 class="white">Ingrese número de folio</h4>
                <form action="<?php echo base_url(); ?>index.php/operariops/insertar" method="post" enctype="multipart/form-data">
                  <center>
                    <table>
                      <tr>
                        <th>
                          <label>Folio:</label>
                        </th>
                        <th>
                          <?php echo form_input($input_folio); ?>
                        </th>
                      </tr>
                    </table>
                    <div id="cargas" name="cargas">
                    </div>
                    <div id="procesos" name="procesos">
                    </div>
                    <div id="valida" name="valida">
                    </div>
                  </center>
                  <br />
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="container">
        <div class="table">
          <div class="header-text">
            <div class="modal-dialog">
              <div class="modal-content modal-popup">
                <h3 class="white">Alta de producción</h3>
                <h4 class="white">Ingrese los datos</h4>
                <form action="<?php echo base_url(); ?>index.php/operariops/insertar/1" method="post" enctype="multipart/form-data">
                  <center>
                    <table>
                      <tr>
                        <th>
                          <label>Folio:</label>
                        </th>
                        <th>
                          <input type="text" name="folio" readonly="true" value="<?php echo $folio ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Carga:</label>
                        </th>
                        <th>
                          <input type="text" name="nombreCarga" readonly="true" value="<?php echo strtoupper($nombreCarga) ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Proceso:</label>
                        </th>
                        <th>
                          <input type="text" name="nombreProceso" readonly="true" value="<?php echo strtoupper($nombreProceso) ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Piezas de producción:</label>
                        </th>
                        <th>
                          <input type="number" name="piezas"  id="piezas"  required="true" placeholder="Inserte el valor" value="<?php echo $piezas ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Defectos:</label>
                        </th>
                        <th>
                          <input type="number" name="defectos" id="defectos"  required="true" placeholder="Inserte el valor" value="<?php echo $defectos ?>"/>
                        </th>
                      </tr>
                    </table>
                  </center>
                  <br />
                  <input type="hidden" name="carga" id="carga" value="<?php echo $carga ?>"/>
                  <input type="hidden" name="proceso" id="proceso" value="<?php echo $proceso ?>"/>
                  <input type="hidden" name="nuevo" id="nuevo" value="<?php echo $nuevo ?>"/>
                  <input type="hidden" name="idprod" id="idprod" value="<?php echo $idprod ?>"/>
                  <input type="hidden" name="usuarioid" id="usuarioid" value="<?php echo $usuarioid ?>"/>
                  <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado ?>"/>
                  <input type="submit" value="Aceptar"/>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </header>
</body>
</html>
