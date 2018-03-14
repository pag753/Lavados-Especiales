<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
