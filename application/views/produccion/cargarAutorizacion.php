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
  <script src="<?php echo base_url(); ?>js/jquery-1.12.4.js"></script>
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
                <li><a href="<?php echo base_url()?>index.php/produccion/autorizar" class="btn btn-gray">Autorizar corte</a></li>
              </ul>
            </li>
            <?php if ($_SESSION['id']==5): ?>
              <li><a href="<?php echo base_url()?>index.php/root/" class="btn btn-blue">Regresar</a></li>
            <?php endif ?>
            <li><a href="<?php echo base_url()?>index.php/produccion/cerrar_sesion" class="btn btn-blue">Cerrar Sesión</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <script>
      $(document).ready(function(){
        $('#folio').keyup(function(){
          $.ajax({url: "<?php echo base_url() ?>index.php/ajax/autorizacionCorte/"+$('#folio').val(), success: function(result){
            $("#respuesta").html(result);
          }});
        });
        $( "form" ).on( "click", "button", function(){
          if(this.name=="boton"){
            var numero=$( "#numero" );
            $.ajax({url: "<?php echo base_url() ?>index.php/ajax/agregarRenglonProduccion/"+numero.val(), success: function(result){
              $( '#tabla tbody' ).append(result);
              numero.val(parseInt(numero.val())+1);
            }});
          }
          else{
            if(this.id.substring(0,8)=="eliminar"){
              var renglon=this.id.substring(8);
              $( "#renglon"+renglon ).remove();
              var numero=$( "#numero" );
              numero.val(parseInt(numero.val())-1);
            }
          }
        });
      });
    </script>
    <?php
      $input_folio = array('name'       => 'folio',
                           'id'         => 'folio',
                           'type'       => 'text',
                           'class'      => 'bootstrap',
                           'placeholder'=> 'Ingresa folio de corte',
                           'value'      => set_value('folio',@$datos_corte->folio),
                           'required'   => 'true');

      $input_fecha = array('name'    => 'fecha',
                           'id'      => 'fecha',
                           'type'    => 'text',
                           'class'   => 'tcal',
                           'value'   => set_value('fecha',date("d/m/Y"),@$datos_corte->fecha),
                           'readonly'=> 'true',
                        'required'   => 'true');
    ?>
    <div class="container">el folio y fecha de autorización
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <form class="form-inline" action="<?php echo base_url(); ?>index.php/produccion/autorizar" method="post">
              <input type="hidden" name="numero" id="numero" value="0">
              <h3 class="black"><?php echo $texto1; ?></h3>
              <table class="table table-striped" name="tabla" id="tabla">
                <caption><?php echo $texto2; ?></caption>
                <thead>
                  <tr>
                    <th><center>Descripción</center></th>
                    <th><center>Valor</center></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Folio</td>
                    <td><?php echo form_input($input_folio); ?></td>
                  </tr>
                  <tr>
                    <td>Fecha</td>
                    <td><?php echo form_input($input_fecha); ?></td>
                  </tr>
                  <tr>
                    <th><center>Lavado</center></th>
                    <th><center>Proceso seco</center></th>
                    <th><center>Eliminar</center></th>
                  </tr>
                </tbody>
              </table>
              <button type="button" name="boton" id="boton">Agregar Lavado</button>
              <br />
              <br>
              <div name='respuesta' id='respuesta'>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
</body>
</html>
