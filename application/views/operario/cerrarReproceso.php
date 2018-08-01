<?php
defined('BASEPATH') or exit('No direct script access allowed');

$input_folio = array(
  'type' => 'number',
  'name' => 'folio',
  'id' => 'folio',
  'class' => 'form-control',
  'value' => set_value('folio', @$folio),
  'placeholder' => 'Inserte número de folio'
);
?>
<script>
$(document).ready(function() {
  $('#tabla').hide();
  $("#folio").focus().keyup(function() {
    if ($('#folio').val() == '') {
      $('#tabla').hide(500);
      $('#alerta').attr("class","alert alert-danger" )
      .html("Número de folio vacío")
      .show(500);
    }
    else {
      $.ajax({
        error: function(request, status, error){
          window.location.replace("<?php echo base_url() ?>");
          //console.log(request.responseText);
        },
        url: "<?php echo base_url() ?>index.php/ajax/getProcesosReproceso",
        data: { folio: $('#folio').val() },
        dataType: 'json',
        type: "POST",
        success: function(result) {
          console.log(result);
          if (result.datos.length == 0) {
            $('#tabla').hide(500);
            $('#alerta').attr("class","alert alert-warning" )
            .html("No hay reprocesos para este folio.")
            .show(500);
          }
          else {
            $('#tabla tbody').html("");
            $.each(result.datos, function( index, value ) {
              var contenido = "", clase = "", estado="", boton="";
              if ((value.status * 1) != 1) {
                clase = ' class="table-danger"';
                estado = "Cerrado";
              }
              else {
                estado = "Abierto";
                boton = '<a href="cerrarReproceso?id=' + value.id + '&lavado=' + value.lavado + '&proceso=' + value.proceso_seco + '&folio=' + $('#folio').val() + '&marca=' + result.corte.marca + '&cliente=' + result.corte.cliente + '&color_hilo=' + value.color_hilo + '&tipo=' + value.tipo + '&carga=' + value.id_carga + '"><button type="button" class="btn btn-primary"><i class="far fa-edit"></i></button></a>'
              }
              contenido = '<tr' + clase + '><td>' + value.id_carga + '</td><td>' + value.lavado + '</td><td>' + value.proceso_seco + '</td><td>' + value.color_hilo + '</td><td>' + value.tipo + '</td><td>' + estado + '</td>' + value.lavado + '<td>' + boton + '</td></tr>';
              $("#tabla tbody").append(contenido);
            });
            $('#tabla').show(500);
            $('#alerta').hide(500);
          }
        },
      });
    }
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <h3>Cerrar reproceso</h3>
      <div class="form-group row">
        <label for="folio" class="col-3 col-form-label">Folio</label>
        <div class="col-9">
          <?php echo form_input($input_folio); ?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="tabla" style="background: rgba(255, 255, 255, 0.9)">
          <thead>
            <tr>
              <th># Carga</th>
              <th>Lavado</th>
              <th>Proceso</th>
              <th>Color de hilo</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Cerrar</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="alert alert-primary" role="alert" id="alerta">Inserta el número de folio.</div>
    </div>
  </div>
</div>
