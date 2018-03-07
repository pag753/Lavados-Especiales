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

    <script src="<?php echo base_url()?>js/jquery.js"></script>
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
        <?php
          $input_folio = array('name'    => 'folio',
                               'id'      => 'folio',
                               'readonly'=> 'true',
                               'type'    => 'text',
                               'class'   => 'bootstrap',
                               'value'   => $corte,);

          $input_fecha = array('name'    => 'fecha',
                               'id'      => 'fecha',
                               'type'    => 'text',
                               'class'   => 'tcal',
                               'value'   => set_value('fecha',date("d/m/Y")),
                               'readonly'=> 'true',);

          $input_corte = array('name'       => 'corte',
                               'id'         => 'corte',
                               'type'       => 'text',
                               'class'      => 'bootstrap',
                               'placeholder'=> 'Inserte corte',
                               'required'   => 'true');

          $input_piezas = array('name'      => 'piezas',
                                'id'         => 'piezas',
                                'type'       => 'number',
                                'class'      => 'bootstrap',
                                'placeholder'=> 'Inserte # de piezas',
                                'required'   => 'true');

          $input_imagen = array('name'      =>  'mi_imagen',
                                'id'         => 'mi_imagen',
                                'type'       => 'file',
                                'class'      => 'bootstrap',);

          foreach ($maquileros as $key => $value)
            $opciones_maquilero[$value['id']]=$value['nombre'];

          $select_maquilero=array('name'   => 'maquilero',
                                  'id'     => 'maquilero',
                                  'class'  => 'bootstrap',);

          $opciones_cliente[-1]="Seleccione el cliente";

          foreach ($clientes as $key => $value)
            $opciones_cliente[$value['id']]=$value['nombre'];

          $select_cliente=array('name'    => 'cliente',
                                'id'    => 'cliente',
                                'class' => 'bootstrap',);

          foreach ($tipos as $key => $value)
            $opciones_tipo[$value['id']]=$value['nombre'];

          $select_tipo=array('name'  => 'tipo',
                             'id'    => 'tipo',
                             'class' => 'bootstrap',);
        ?>
        <script type="text/javascript">
          $(document).ready(function(){
            $('#cliente').change(function(){
              $.ajax({url: "<?php echo base_url() ?>index.php/ajax/gestionMarcas/"+$('#cliente').val(), success: function(result){
                $('#marcas').html(result);
              }});
            });
            $( "form" ).submit(function( event ){
              var val=$("#cliente").val();

              if(parseInt(val)==-1){
                alert("Por favor escoja un cliente");
                return false;
              }
              else
                return true;
              });
            });
        </script>

        <div class="table">
          <div class="header-text">
            <form class="form-inline" role='form' action="<?php echo base_url(); ?>index.php/Gestion/alta" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <h3 class="black">Alta de Corte</h3>
                <table class="table table-condensed">
                  <caption>Ingrese los datos del nuevo corte</caption>
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
                      <td>Corte</td>
                      <td><?php echo form_input($input_corte); ?></td>
                    </tr>
                    <tr>
                      <td>Piezas</td>
                      <td><?php echo form_input($input_piezas); ?></td>
                    </tr>
                    <tr>
                      <td>Cliente</td>
                      <td><?php echo form_dropdown($select_cliente,$opciones_cliente,set_value('cliente',@$datos_corte[0]->cliente)); ?></td>
                    </tr>
                    <tr>
                      <td>Marca</td>
                      <td><div id='marcas' value='marcas'>Escoja primero el cliente</div></td>
                    </tr>
                    <tr>
                      <td>Maquilero</td>
                      <td><?php echo form_dropdown($select_maquilero,$opciones_maquilero,set_value('maquilero',@$datos_corte[0]->maquilero)); ?></td>
                    </tr>
                    <tr>
                      <td>Tipo</td>
                      <td><?php echo form_dropdown($select_tipo,$opciones_tipo,set_value('tipo',@$datos_corte[0]->tipo)); ?></td>
                    </tr>
                  </tbody>
                </table>
                <label>Imágen:</label>
                <?php echo form_input($input_imagen); ?>
                <input type="submit" class="bootstrap" value="Aceptar"/>
              </div>
            </form>
          </div>
        </div>
      </header>
    </body>
  </head>
</html>
