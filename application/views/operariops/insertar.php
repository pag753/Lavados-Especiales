<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <?php if (!isset($folio): ?>
      <?php
        $input_folio = array('type'    => 'text',
                             'name'    => 'folio',
                             'id'      => 'folio',
                             'class'   => 'bootstrap',
                             'value'   => set_value('folio',@$folio),);
      ?>
      <script>
        $(document).ready(function(){
          $('#folio').keyup(function(){
            $('#procesos').html('');
            $('#valida').html('');
            $.ajax({url: "<?php echo base_url() ?>index.php/ajax/operarioCargas/"+$('#folio').val(), success: function(result){
              $("#cargas").html(result);
              $( '#carga' ).change(function(){
                $('#valida').html('');
                $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos/'+$('#folio').val()+'_'+$('#carga').val(), success: function(result){
                  $('#procesos').html(result);
                  $( '#proceso' ).change(function(){
                    $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioValida/'+$('#folio').val()+'_'+$('#carga').val()+'_'+$('#proceso').val(), success: function(result){
                      $('#valida').html(result);
                    }});
                  });
                }});
              });
            }});
          });
          $( '#carga' ).change(function(){
            $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos/'+$('#folio').val()+'_'+$('#carga').val(), success: function(result){
              $('#procesos').html(result);
            }});
          });
          $( '#proceso' ).change(function(){
            $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioValida/'+$('#folio').val()+'_'+$('#carga').val()+'_'+$('#proceso').val(), success: function(result){
              $('#valida').html(result);
            }});
          });
        });
      </script>
      <div class="container">
        <div class="table">
          <div class="header-text">
            <div class="modal-dialog">
              <div class="modal-content modal-popup">
                <h3 class="white">Alta de producción</h3>
                <h4 class="white">Ingrese número de folio</h4>
                <form action="<?php echo base_url(); ?>index.php/operariops/insertar" method="post" enctype="multipart/form-data">
                  <center>
                    <table>
                      <tr>
                        <th>
                          <label>Folio:</label>
                        </th>
                        <th>
                          <?php echo form_input($input_folio); ?>
                        </th>
                      </tr>
                    </table>
                    <div id="cargas" name="cargas">
                    </div>
                    <div id="procesos" name="procesos">
                    </div>
                    <div id="valida" name="valida">
                    </div>
                  </center>
                  <br />
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="container">
        <div class="table">
          <div class="header-text">
            <div class="modal-dialog">
              <div class="modal-content modal-popup">
                <h3 class="white">Alta de producción</h3>
                <h4 class="white">Ingrese los datos</h4>
                <form action="<?php echo base_url(); ?>index.php/operariops/insertar/1" method="post" enctype="multipart/form-data">
                  <center>
                    <table>
                      <tr>
                        <th>
                          <label>Folio:</label>
                        </th>
                        <th>
                          <input type="text" name="folio" readonly="true" value="<?php echo $folio ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Carga:</label>
                        </th>
                        <th>
                          <input type="text" name="nombreCarga" readonly="true" value="<?php echo strtoupper($nombreCarga) ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Proceso:</label>
                        </th>
                        <th>
                          <input type="text" name="nombreProceso" readonly="true" value="<?php echo strtoupper($nombreProceso) ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Piezas de producción:</label>
                        </th>
                        <th>
                          <input type="number" name="piezas"  id="piezas"  required="true" placeholder="Inserte el valor" value="<?php echo $piezas ?>"/>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <label>Defectos:</label>
                        </th>
                        <th>
                          <input type="number" name="defectos" id="defectos"  required="true" placeholder="Inserte el valor" value="<?php echo $defectos ?>"/>
                        </th>
                      </tr>
                    </table>
                  </center>
                  <br />
                  <input type="hidden" name="carga" id="carga" value="<?php echo $carga ?>"/>
                  <input type="hidden" name="proceso" id="proceso" value="<?php echo $proceso ?>"/>
                  <input type="hidden" name="nuevo" id="nuevo" value="<?php echo $nuevo ?>"/>
                  <input type="hidden" name="idprod" id="idprod" value="<?php echo $idprod ?>"/>
                  <input type="hidden" name="usuarioid" id="usuarioid" value="<?php echo $usuarioid ?>"/>
                  <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado ?>"/>
                  <input type="submit" value="Aceptar"/>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </header>
