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
          $.ajax({url: "<?php echo base_url() ?>index.php/ajax/costosAdministracion/"+$('#folio').val(), success: function(result){
            $("#cargas").html(result);
          }});
        });
      });
    </script>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white"><?php echo $texto1; ?></h3>
              <h4 class="white"><?php echo $texto2; ?></h4>
              <form action="<?php echo base_url(); ?>index.php/administracion/costos" method="post" enctype="multipart/form-data">
                <center>
                  <table>
                    <tr>
                      <th><label>Folio:</label></th>
                      <th><?php echo form_input($input_folio); ?></th>
                      <th><?php echo form_error('folio') ; ?></th>
                    </tr>
                  </table>
                  <div id="cargas" name"cargas">
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
