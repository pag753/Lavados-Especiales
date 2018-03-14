<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white">Catálogos</h3>
              <h4 class="white">Maquilero
                <?php
                  if($post['Boton']=='Nuevo')
                  {
                    $data[0]['nombre']='';
                    $data[0]['direccion']='';
                    $data[0]['telefono']='';
                  }
                ?>
              </h4>
              <form action="<?php echo base_url(); ?>index.php/administracion/catalogosGuardar/3" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo $data[0]['id']?>"/>
                <input type="hidden" name="opcion" id="opcion" value="<?php echo $post['Boton']?>"/>
                <table class="table table-striped" name="tabla" id="tabla">
                  <thead>
                    <tr>
                      <th><center>Descripción</center></th>
                      <th><center>Valor</center></th>
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
