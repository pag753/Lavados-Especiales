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
  $("#folio").focus();
  $('#tabla').hide();
  $('#folio').keyup(function() {
    if ($('#folio').val() == '') {
      $('#tabla').hide(500);
      $('#alerta').attr("class","alert alert-danger")
      .html("Número de folio vacío")
      .show(500);
    }
    else {
      $.ajax({
        error: function(request, status, error){
          window.location.replace("<?php echo base_url() ?>");
        },
        url: "<?php echo base_url() ?>index.php/ajax/getProcesosReproceso",
        data: { folio: $('#folio').val() },
        dataType: 'json',
        type: "POST",
        success: function(result) {
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
              if ((value.status * 1) == 2) {
                clase = ' class="table-danger"';
                estado = "Cerrado";
              }
              else {
                estado = "Abierto";
                boton = '<a href="insertarReproceso?id=' + value.id + '&lavado=' + value.lavado + '&proceso=' + value.proceso_seco + '&marca=' + result.corte.marca + '&cliente=' + result.corte.cliente + '&carga=' + value.id_carga + '&color_hilo=' + value.color_hilo + '&tip=' + value.tipo + '&folio=' + $('#folio').val() + '"><button type="button" class="btn btn-primary"><i class="far fa-edit"></i></button></a>'
              }
              contenido = '<tr'+clase+'><td>' + value.id_carga + '</td><td>' + value.tipo + '</td><td>' + value.color_hilo + '</td><td>'+value.lavado+'</td><td>'+value.proceso_seco+'</td><td>'+estado+'</td><td>'+boton+'</td></tr>';
              $("#tabla tbody").append(contenido);
              $('#tabla').show(500);
              $('#alerta').hide(500);
            });
            $('#marca').html(result.corte.marca);
            $('#cliente').html(result.corte.cliente);
          }
        }
      });
    }
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <h3>Alta de producción de reproceso</h3>
      <table class="table table-striped table-bordered" style="background: rgba(255,255,255,.7)">
        <tbody>
          <tr>
            <th>Folio</th>
            <td><?php echo form_input($input_folio); ?></td>
          </tr>
          <tr>
            <th>Marca</th>
            <td id="marca"></td>
          </tr>
          <tr>
            <th>Cliente</th>
            <td id="cliente"></td>
          </tr>
        </tbody>
      </table>
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="tabla" style="background: rgba(255, 255, 255, 0.9)">
          <thead>
            <tr>
              <th># Carga</th>
              <th>Tipo</th>
              <th>Color de hilo</th>
              <th>Lavado</th>
              <th>Proceso seco</th>
              <th>Estado</th>
              <th>Ingresar producción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="alert alert-primary" role="alert" id="alerta">Inserta el número de folio.</div>
    </div>
  </div>
</div>
