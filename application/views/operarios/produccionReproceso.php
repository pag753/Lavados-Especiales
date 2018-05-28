<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$input_folio = array(
  'type' => 'number',
  'name' => 'folio',
  'id' => 'folio',
  'class' => 'form-control',
  'value' => set_value('folio',@$folio),
  'placeholder' => 'Inserte número de folio',
);
?>
<script>
$(document).ready(function() {
  $('#tabla').hide();
  $('#folio').keyup(function() {
    if ($('#folio').val() == '') {
      $('#tabla').hide(500);
      $('#alerta').attr("class","alert alert-danger" )
      .html("Número de folio vacío")
      .show(500);
    }
    else {
      $.ajax({
        url: "<?php echo base_url() ?>index.php/ajax/getProcesosReproceso",
        data: { folio: $('#folio').val() },
        dataType: 'json',
        type: "POST",
        success: function(result) {
          if (result.length == 0) {
            $('#tabla').hide(500);
            $('#alerta').attr("class","alert alert-warning" )
            .html("No hay reprocesos para este folio.")
            .show(500);
          }
          else {
            $('#tabla tbody').html("");
            $.each(result, function( index, value ) {
              var contenido = "", clase = "", estado="", boton="";
              if ((value.status*1) != 1) {
                clase = ' class="table-danger"';
                estado = "Cerrado";
              }
              else {
                estado = "Abierto";
                boton = '<a href="insertarReproceso?id='+value.id+'&lavado='+value.lavado+'&proceso='+value.proceso_seco+'"><button type="button" class="btn btn-primary"><i class="far fa-edit"></i></button></a>'
              }
              contenido = '<tr'+clase+'><td>'+value.lavado+'</td><td>'+value.proceso_seco+'</td><td>'+estado+'</td>'+value.lavado+'<td>'+boton+'</td></tr>';
              $("#tabla tbody").append(contenido);
              $('#tabla').show(500);
              $('#alerta').hide(500);
            });
          }
        },
        error: function (request, status, error) {
          console.log(request.responseText);
        },
      });
    }
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Alta de producción de reproceso</h3>
      <div class="form-group row">
        <label for="folio" class="col-3 col-form-label">Folio</label>
        <div class="col-9">
          <?php echo form_input($input_folio); ?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover" name="tabla" id="tabla" style="background: rgba(255,255,255,0.9)">
          <thead>
            <tr>
              <th>Carga o lavado</th>
              <th>Proceso</th>
              <th>Estado</th>
              <th>Registrar</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="alert alert-primary" role="alert" name="alerta" id="alerta">
        Inserta el número de folio.
      </div>
    </div>
  </div>
</div>
