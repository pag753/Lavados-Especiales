<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <script>
      $(document).ready(function(){
        $('#folio').keyup(function(){
          $.ajax({url: "<?php echo base_url() ?>index.php/ajax/salidaInterna/"+$('#folio').val(), success: function(result){
            $("#aceptar").html(result);
          }});
        });
      });
    </script>
    <?php
      $input_folio = array('name'       => 'folio',
                           'id'         => 'folio',
                           'type'       => 'text',
                           'class'      => 'bootstrap',
                           'required'   => 'true',
                           'placeholder'=>'Ingresa número de folio');
    ?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white"><?php echo $texto1; ?></h3>
              <h4 class="white"><?php echo $texto2; ?></h4>
              <form action="<?php echo base_url(); ?>index.php/gestion/salidaInterna1" method="post" enctype="multipart/form-data">
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
                      </th>
                    </tr>
                  </table>
                </center>
                <br />
                <div id="aceptar" name"aceptar">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
