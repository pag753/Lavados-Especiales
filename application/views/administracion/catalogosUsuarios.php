<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white">Catálogos</h3>
              <h4 class="white">Usuario
                <?php
                  if($post['Boton']=='Nuevo')
                  {
                    $data[0]['nombre']='';
                    $data[0]['tipo_usuario_id']=0;
                    $data[0]['nombre_completo']='';
                    $data[0]['direccion']='';
                    $data[0]['telefono']='';
                  }
                ?>
              </h4>
              <form action="<?php echo base_url(); ?>index.php/administracion/catalogosGuardar/6" method="post" enctype="multipart/form-data">
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
                      <td>Usuario</td>
                      <td>
                        <?php if ($post['Boton']=='Nuevo'): ?>
                          <input type="text" name="nombre" value="<?php echo $data[0]['nombre'] ?>" required="true">
                        <?php else: ?>
                          <input type="text" name="nombre" value="<?php echo $data[0]['nombre'] ?>" readonly="true">
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Contraseña nueva</td>
                      <?php if ($post['Boton']=='Nuevo'): ?>
                        <td><input type="password" name="pass" value="" required="true"></td>
                      <?php else: ?>
                        <td><input type="password" name="pass" value="" placeholder="Escribir sólo en cambio" ></td>
                      <?php endif; ?>
                    </tr>
                    <tr>
                      <td>Tipo de usuario</td>
                      <td>
                        <select name="tipo_usuario_id" id="tipo_usuario_id">
                          <?php foreach ($tiposUsuario as $key => $value): ?>
                            <?php if ($value['id']==$data[0]['tipo_usuario_id']): ?>
                              <option value="<?php echo $value['id']?>" selected="true"><?php echo $value['tipo_usuariocol']?></option>
                            <?php else: ?>
                              <option value="<?php echo $value['id']?>"><?php echo $value['tipo_usuariocol']?></option>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Nombre completo</td>
                      <td><input type="text" name="nombre_completo" value="<?php echo $data[0]['nombre_completo']?>"></td>
                    </tr>
                    <tr>
                      <td>Dirección</td>
                      <td><input type="text" name="direccion" value="<?php echo $data[0]['direccion']?>"></td>
                    </tr>
                    <tr>
                      <td>Teléfono</td>
                      <td><input type="text" name="telefono" value="<?php echo $data[0]['telefono']?>"></td>
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
