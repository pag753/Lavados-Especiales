<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
