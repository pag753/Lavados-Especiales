<?php
defined('BASEPATH') or exit('No direct script access allowed');
$input_folio = array(
    'type' => 'number',
    'name' => 'folio',
    'id' => 'folio',
    'class' => 'form-control',
    'value' => set_value('folio', @$folio)
);
?>
<script>
$(document).ready(function() {
  $("#folio").focus().keyup(function() {
    $.ajax({
      url: "<?php echo base_url() ?>index.php/ajax/costosAdministracion",
      data: { folio: $('#folio').val() },
      type: 'POST',
      dataType: "text",
      success: function(result) {
        $( "#cargas" ).html(result);
      }
    });
  });
  $('#costos').submit(function() {
    if ($('#folio').val() == "") {
      alert("Favor de completar el campo del folio");
      return false;
    }
    else return true;
  });
});
</script>
<div class="container-fluid">
  <div class="table">
    <div class="row">
      <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
        <h3 class="white"><?php echo $texto1; ?></h3>
        <form action="costos" id="costos" method="get"
          enctype="multipart/form-data">
          <div class="form-group row">
            <label for="folio" class="col-3 col-form-label">Folio</label>
            <div class="col-9"><?php echo form_input($input_folio); ?></div>
          </div>
          <div id="cargas" class="form-group row"></div>
        </form>
      </div>
    </div>
  </div>
</div>
