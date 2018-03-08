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
    <script type="text/javascript">
      $(document).ready(function(){
        $( "form" ).on( "click", "button", function(){
          if(this.id.substring(0,8)=="eliminar"){
            var renglon=this.id.substring(8);
            $( "#renglon"+renglon ).remove();
          }
          else{
            var marcas=$( "#numerodemarcas" );
            $.ajax({url: "<?php echo base_url() ?>index.php/ajax/agregarRenglon/"+marcas.val(), success: function(result){
              $( '#tabla tbody' ).append(result);
              marcas.val(parseInt(marcas.val())+1);
            }});
          }
        });
      });
    </script>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white">Catálogos</h3>
              <h4 class="white">Cliente
                <?php
                  if($post['Boton']=='Nuevo'){
                    $data[0]['nombre']='';
                    $data[0]['direccion']='';
                    $data[0]['telefono']='';
                  }
                ?>
              </h4>
              <form action="<?php echo base_url(); ?>index.php/administracion/catalogosGuardar/1" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo $data[0]['id']?>"/>
                <input type="hidden" name="opcion" id="opcion" value="<?php echo $post['Boton']?>"/>
                <input type="hidden" name="numerodemarcas" id="numerodemarcas" value="<?php echo count($marca) ?>">
                <table class="table table-striped" name="tabla" id="tabla">
                  <thead>
                    <tr>
                      <th><center>Descripción</center></th>
                      <th><center>Valor</center></th>
                      <th><center>Eliminar</center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Nombre</td>
                      <td><input type="text" name="nombre" value="<?php echo $data[0]['nombre'] ?>" required="true"></td>
                    </tr>
                    <tr>
                      <td>Dirección</td>
                      <td><input type="text" name="direccion" value="<?php echo $data[0]['direccion'] ?>"></td>
                    </tr>
                    <tr>
                      <td>Teléfono</td>
                      <td><input type="text" name="telefono" value="<?php echo $data[0]['telefono'] ?>"></td>
                    </tr>
                    <?php foreach ($marca as $key1 => $value1): ?>
                      <?php echo "<tr id='renglon$key1' name='renglon$key1'>" ?>
                        <td>Marca</td>
                        <td>
                          <?php echo "<select id='marca[$key1]' name='marca[$key1]'>" ?>
                            <?php foreach ($marcas as $key2 => $value2): ?>
                              <?php if ($value1['idmarca']==$value2['id']): ?>
                                <option value="<?php echo $value2['id'] ?>" selected="true"><?php echo $value2['nombre'] ?></option>
                              <?php else: ?>
                                <option value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td>
                          <?php echo "<button type='button' name='eliminar$key1' id='eliminar$key1'>Eliminar</button>" ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <table class="table table-striped" >
                  <tbody>
                    <tr>
                      <td></td>
                      <td><button type="button" name="button">Agregar Marca</button></td>
                    </tr>
                  </tbody>
                </table>
                <center>
                  <input type="submit" value="Aceptar" id="Aceptar" name="Aceptar"/>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</body>
</html>
