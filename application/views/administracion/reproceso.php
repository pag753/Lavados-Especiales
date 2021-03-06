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
  $('#reproceso').hide();
  $("#folio").focus().keyup(function() {
    if ($('#folio').val() == '') {
      $('#alerta').attr('class','alert alert-warning')
      .html('Folio de corte vacío')
      .show(200);
      $('#reproceso').hide(200);
    }
    else {
      $.ajax({
        error: function(request, status, error){
          window.location.replace("<?php echo base_url() ?>");
        },
        url: "<?php echo base_url() ?>index.php/ajax/cargasAdministracion",
        data: { folio: $('#folio').val() },
        type: 'POST',
        dataType: "json",
        success: function(result) {
          if (result.length == 0) {
            $('#alerta').attr('class','alert alert-danger')
            .html('No se encontraron cargas para este folio.')
            .show(200);
            $('#reproceso').hide(200);
          }
          else {
            $('#lavado').html('');
            $.each(result, function( index, value ) {
              $('#lavado').append("<option value=" + value.id + ">" + value.id_carga + ".- " + value.lavado + "</option>")
            });
            $('#alerta').hide(200);
            $('#reproceso').show(200);
            $('#corte_folio').val($('#folio').val());
          }
        }
      });
    }
  });
  $('#reproceso').submit(function() {
    return confirm('¿Está seguro de dar de alta este reproceso?');
  });
  if ($('#folio').val() != '') $('#folio').keyup();
});
</script>
<div class="container-fluid">
  <div class="table">
    <div class="row">
      <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
        <h3 class="white">Nuevo reproceso</h3>
        <table class="table table-striped">
          <tbody>
            <th>Folio</th>
            <td><?php echo form_input($input_folio); ?></td>
          </tbody>
        </table>
        <div class="alert alert-info" role="alert" id="alerta">Escribe el número de folio.</div>
        <form action="reproceso" method="post" enctype="multipart/form-data" name="reproceso" id="reproceso">
          <input type="hidden" name="corte_folio" id="corte_folio">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <tr>
                <th>Carga - Lavado</th>
                <td><select class="form-control" name="lavado" id="lavado">
                </select></td>
              </tr>
              <tr>
                <th>Proceso seco</th>
                <td>
                  <select class="form-control" name="proceso" id="proceso">
                    <?php foreach ($procesos as $key => $value): ?>
                      <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
              <tr>
                <th>Costo</th>
                <td><input type="number" class="form-control" required step="any" name="costo" id="costo" value="0"></td>
              </tr>
              <tr>
                <th>Piezas</th>
                <td><input type="number" class="form-control" required name="piezas" id="piezas" value="0"></td>
              </tr>
            </table>
            <div class="mx-auto">
              <input type="submit" value="Aceptar" class="btn btn-primary">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
