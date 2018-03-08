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
  <script src="<?php echo base_url(); ?>js/jquery.js"></script>
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
                <li><a href="<?php echo base_url()?>index.php/operario/alta" class="btn btn-gray">Insertar información</a></li>
              </ul>
            </li>
            <?php if ($_SESSION['id']==5): ?>
              <li><a href="<?php echo base_url()?>index.php/root/" class="btn btn-blue">Regresar</a></li>
            <?php endif ?>
            <li><a href="<?php echo base_url()?>index.php/operario/cerrar_sesion" class="btn btn-blue">Cerrar Sesión</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
    </nav>
    <?php
      $input_folio = array('type'    => 'text',
                           'name'    => 'folio',
                           'id'      => 'folio',
                           'class'   => 'bootstrap',
                           'value'   => set_value('folio',@$folio),);
    ?>
    <script>
      $(document).ready(function(){
        $("form").submit(function( event ){
          var total=parseInt($("#total").val());
          var trabajadas=parseInt($("#piezas_trabajadas").val());
          var defectos=parseInt($("#defectos").val());
          if($("#siguente")!=null){
            if(parseInt($("#siguente").val())==-1){
              alert("Seleccione una opción válida en siguente");
              return false;
            }
            else
              return true;
          }
        });
      });
    </script>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <h3 class="blue">Datos generales</h3>
            <form action="<?php echo base_url(); ?>index.php/operario/registro" method="post" enctype="multipart/form-data">
              <center>
                <table class="table table-bordered">
                  <tr class="danger">
                    <th>
                      <label class="blue">Folio</label>
                    </th>
                    <th>
                      <center>
                        <label class="blue">Carga</label>
                      </center>
                    </th>
                    <th>
                      <center>
                        <label class="blue">Proceso</label>
                      </center>
                    </th>
                    <th>
                      <center>
                        <label class="blue">Trabajadas</label>
                      </center>
                    </th>
                    <th>
                      <center>
                        <label class="blue">Defectos</label>
                      </center>
                    </th>
                    <th>
                      <center>
                        <label class="blue">Total</label>
                      </center>
                    </th>
                  </tr>
                  <tr>
                    <th>
                      <label class="blue" name="folio" id="folio"><?php echo $folio; ?></label>
                    </th>
                    <th>
                      <label class="blue"><?php echo strtoupper($nombreCarga); ?></label>
                    </th>
                    <th>
                      <label class="blue"><?php echo strtoupper($nombreProceso); ?></label>
                    </th>
                    <th>
                      <label class="blue"><?php echo $trabajadas ?></label>
                    </th>
                    <th>
                      <label class="blue"><?php echo $defectos ?></label>
                    </th>
                    <th>
                      <label class="blue"><?php echo $piezas ?></label>
                    </th>
                  </tr>
                </table>
                <table>
                  <?php
                    if ($trabajadas+$defectos==$piezas)
                    {
                      if(count($faltantes)!=0)
                      {
                        echo "
                        <tr>
                          <th class='blue'>
                            Siguente:
                          </th>
                          <th>
                            <select name='siguente' id='siguente'>
                              <option value=-1>
                                SELECCIONE UNA OPCIÓN
                                </option>";
                        foreach ($faltantes as $key => $value)
                        {
                          echo "
                              <option value=".$value['idproceso'].">
                                ".strtoupper($value['proceso'])."
                              </option>";
                        }
                        echo "
                            </select>
                          </th>
                        </tr>";
                      }
                    }
                  ?>
                </table>
              </center>
              <input type="hidden" name="proceso" id="proceso" value="<?php echo $proceso ?>"/>
              <input type="hidden" name="carga" id="carga" value="<?php echo $carga ?>"/>
              <input type="hidden" name="orden" id="orden" value="<?php echo $orden ?>"/>
              <br />
              <?php if ($trabajadas+$defectos==$piezas) {?>
                <input type="submit" name="aceptar" id="aceptar" value="Aceptar"/>
              <?php } else {?>
                <h6 class="blue">La suma de las piezas de producción y defecectos no son iguales al total. Favor de revisar con los operarios</h6>
              <?php } ?>
              <h3 class="blue">Para ver los datos epecíficos siga bajando</h3>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <h3 class="blue">Datos específicos</h3>
            <table class="table table-bordered">
              <tr class="danger">
                <th>
                  <label class="blue">Empleado</label>
                </th>
                <th>
                  <label class="blue">Piezas</label>
                </th>
                <th>
                  <label class="blue">Defectos</label>
                </th>
                <th>
                  <label class="blue">Fecha</label>
                </th>
              </tr>
              <?php
                foreach ($query as $key => $value)
                {
                  echo "<tr>
                  <th><label class='blue'>".$value['usuario']."</label></th>
                  <th><label class='blue'>".$value['piezas']."</label></th>
                  <th><label class='blue'>".$value['defectos']."</label></th>
                  <th><label class='blue'>".$value['fecha']."</label></th>
                  </tr>";
                }
              ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </header>
</body>
</html>
