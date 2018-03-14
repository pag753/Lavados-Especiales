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
              <h3 class="white">Información del corte</h3>
              <h4 class="white">Ingrese número de folio</h4>
              <form action="<?php echo base_url(); ?>index.php/operario/alta" method="post" enctype="multipart/form-data">
                <center>
                  <table>
                    <tr>
                      <th>
                        <label>Folio:</label>
                      </th>
                      <th>
                        <?php echo form_input($input_folio); ?>
                      </th>
                      <th>
                        <?php echo form_error('folio') ; ?>
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
  </header>
