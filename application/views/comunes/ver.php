<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
$(document).ready(function(){
  $("#folio").focus();
  $('#boton').prop('disabled', true);
  $('#complemento').hide();
  $('#folio').keyup(function() {
    if ($('#folio').val() == '')  $('#boton').prop('disabled', true);
    else  $('#boton').prop('disabled', false);
  });
  $('#boton').click(function() {
    $.ajax({
      url: "<?php echo base_url() ?>index.php/ajax/detalleCorte",
      data: { folio: $('#folio').val() },
      type: 'POST',
      dataType: 'json',
      success: function(result) {
        $('#complemento').show();
        $('#alerta').hide();
        if (result.folio != ''){
          $("#imagen").html(result.imagen);
          $("#folio").html(result.folio);
          $("#corte").html(result.corte);
          $("#marca").html(result.marca);
          $("#maquilero").html(result.maquilero);
          $("#cliente").html(result.cliente);
          $("#tipo").html(result.tipo);
          $("#fecha").html(result.fecha);
          $("#piezas").html(result.piezas);
          $("#ojales").html(result.ojales);
        }
        else
        {
          $('#complemento').hide();
          $('#alerta').show();
          $("#alerta").html("El corte con folio "+$('#folio').val()+" no existe en la base de datos");
          $('#alerta').removeClass("alert-info");
          $('#alerta').addClass("alert-danger");
        }
      }
    });
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 offset-md-3 offset-lg-3 offset-xl-3">
      <div class="card">
        <div class="card-header">
          <h1>Ver detalles de corte</h1>
        </div>
        <div class="card-body">
          <input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required />
          <div class="mx-auto">
            <button type="button" name="boton" id="boton" class="btn btn-primary">
              <i class="fas fa-eye"></i> Ver
            </button>
          </div>
        </div>
      </div>
      <div id="complemento">
        <table class="table table-striped table-bordered" style="background: rgba(255, 255, 255, .5)">
          <tbody>
            <tr>
              <td>Imágen</td>
              <td id="imagen"></td>
            </tr>
            <tr>
              <td>Folio</td>
              <td id="folio"></td>
            </tr>
            <tr>
              <td>Corte</td>
              <td id="corte"></td>
            </tr>
            <tr>
              <td>Marca</td>
              <td id="marca"></td>
            </tr>
            <tr>
              <td>Maquilero</td>
              <td id="maquilero"></td>
            </tr>
            <tr>
              <td>Cliente</td>
              <td id="cliente"></td>
            </tr>
            <tr>
              <td>Tipo</td>
              <td id="tipo"></td>
            </tr>
            <tr>
              <td>Fecha de entrada</td>
              <td id="fecha"></td>
            </tr>
            <tr>
              <td>Piezas</td>
              <td id="piezas"></td>
            </tr>
            <tr>
              <td>Ojales</td>
              <td id="ojales"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="alert alert-info" role="alert" id="alerta">Ingresa el número de folio y da click en ver.</div>
    </div>
  </div>
</div>
