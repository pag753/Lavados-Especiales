<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <?php
    $cadena= $folio.'_'.$corte.'_'.$marca.'_'.$maquilero.'_'.$cliente.'_'.$tipo.'_'.$piezas.'_'.$fecha;
    ?>
    <script>
      $(document).ready(function(){
        $('#boton').click(function(){
          alert("Folio: "+$("#folio").val()+
              "\nCorte: "+$('#corte').val()+
              "\nMarca: "+$('#marca').val()+
              "\nMaquilero: "+$('#maquilero').val()+
              "\nCliente: "+$('#cliente').val()+
              "\nTipo: "+$('#tipo').val()+
              "\nFecha de entrada: "+$('#fecha').val()+
              "\nPiezas: "+$('#piezas').val());
        });
      });
    </script>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white"><?php echo $texto1 ?></h3>
              <h4 class="white"><?php echo $texto2 ?></h4>
              <form action="<?php echo base_url(); ?>index.php/administracion/costos/<?php echo $folio."_".$carga ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="folio" id="folio" value="<?php echo $folio; ?>"/>
                <input type="hidden" name="corte" id="corte" value="<?php echo $corte; ?>"/>
                <input type="hidden" name="marca" id="marca" value="<?php echo $marca; ?>"/>
                <input type="hidden" name="maquilero" id="maquilero" value="<?php echo $maquilero; ?>"/>
                <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente; ?>"/>
                <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>"/>
                <input type="hidden" name="piezas" id="piezas" value="<?php echo $piezas; ?>"/>
                <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>"/>
                <input type="hidden" name="carga" id="carga" value="<?php echo $carga; ?>"/>
                <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado; ?>"/>
                <center>
                  <button type="button" name="informacion" id="boton">Ver detalles</button>
                </center>
                <table class="bootstrap">
                  <tr>
                    <th>
                      <center>
                        <label><h5 class="white">Folio: <?php echo $folio; ?></h5></label>
                      </center>
                    </th>
                    <th>
                      <center>
                        <h5 class="white"><?php echo strtoupper($lavado) ?></h5>
                      </center>
                    </th>
                  </tr>
                  <tr>
                    <th>
                      <center>
                        <h5 class="white">Proceso</h5>
                      </center>
                    </th>
                    <th>
                      <center>
                        <h5 class="white">Costo $</h5>
                      </center>
                    </th>
                  </tr>
                  <?php foreach ($procesos as $key => $value): ?>
                    <tr>
                      <th>
                        <input type="text" readonly="true" name="proc[<?php echo $key ?>]" value="<?php echo strtoupper($value) ?>"/>
                      </th>
                      <th>
                        <input type="number" step="any" required placeholder="Inserte costo" name="costo[<?php echo $key ?>]" value="<?php echo $costos[$key] ?>">
                      </th>
                    </tr>
                  <?php endforeach; ?>
                </table>
                <input type="submit" class="bootstrap" value="Aceptar"/>
              </form>
            </div>
            <!-- Holder for mobile navigation -->
          </div>
        </div>
      </div>
    </div>
  </header>
