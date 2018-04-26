<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
  $(document).ready(function(){
    $('#folio').keyup(function(){
      $.ajax({
        url: "<?php echo base_url() ?>index.php/ajax/salidaAlmacen",
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
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h1>Entrega a Almacen</h1>
        </div>
        <div class="card-body">
          <input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required="true" />
        </div>
      </div>
      <div id="complemento" name="complemento">
        <div class="alert alert-info" role="alert">
          Ingresa el número de folio.
        </div>
      </form>
    </div>
  </div>
</div>
