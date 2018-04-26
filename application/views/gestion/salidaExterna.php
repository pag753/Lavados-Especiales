<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
  $(document).ready(function() {
    $('#folio').keyup(function() {
      $.ajax({
        url: "<?php echo base_url() ?>index.php/ajax/salidaExterna",
        data: { folio: $('#folio').val() },
        type: 'POST',
        dataType: 'text',
        success: function(result) {
          $("#complemento").html(result);
        }
      });
    });
  });
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="<?php echo base_url(); ?>index.php/Gestion/salidaExterna/" method="post" enctype="multipart/form-data">
        <h1>Salida Externa</h1>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required="true" />
          </div>
        </div>
        <div id="complemento" name="complemento">
          <div class="alert alert-info" role="alert">
            Ingresa el número de folio.
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
