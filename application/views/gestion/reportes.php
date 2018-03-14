<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
