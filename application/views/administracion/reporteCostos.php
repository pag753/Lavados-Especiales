<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
$(document).ready(function() {
  $("#folio").focus();
  $('#alerta').hide();
  $("#boton").click(function (){
    if ($('#folio').val() == "")
    {
      $('#alerta').html("El campo de folio está vacío.");
      $('#alerta').show();
    }
    var s = $.ajax({
      type: "POST",
      url: "<?php echo base_url("index.php/ajax/detalleCorte/") ?>",
      data: { "folio": $("#folio").val() },
      success: function(res) {
        if (res.folio == ''){
          $('#alerta').html("El corte con folio "+$('#folio').val()+" no existe en la base de datos.");
          $('#alerta').show();
        }
        else $("#formulario").submit();
      },
      dataType: "json"
    });
  });
  $("#formulario").submit(function() {
    if ($('#folio').val() == "") {
      $('#alerta').html("El campo de folio está vacío.");
      $('#alerta').show();
      return false;
    }
    else return true;
  });
});
</script>
<div class="container-fluid">
  <div class="table">
    <div class="row">
      <div class="col-lg-4 col-md-4 offset-lg-4 offset-md-4">
        <h3 class="white">Generar reporte de costos</h3>
        <form action="reporteCostos" method="get" enctype="multipart/form-data" name="formulario" id="formulario">
          <div class="form-group row">
            <label for="folio" class="col-3 col-form-label">Folio</label>
            <div class="col-9">
              <input type="number" name="folio" id="folio" class="form-control">
            </div>
          </div>
          <div class="mx-auto">
            <button type="button" name="boton" id="boton" class="btn btn-primary">Aceptar</button>
          </div>
        </form>
        <div class="alert alert-danger" role="alert" id="alerta"></div>
      </div>
    </div>
  </div>
</div>
