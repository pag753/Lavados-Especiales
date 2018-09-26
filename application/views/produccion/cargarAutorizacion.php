<?php
defined('BASEPATH') or exit('No direct script access allowed');
$input_folio = array(
  'name' => 'folio',
  'id' => 'folio',
  'type' => 'number',
  'class' => 'form-control',
  'placeholder' => 'Ingresa folio de corte',
  'value' => set_value('folio', @$datos_corte->folio),
  'required' => 'true'
);
$input_fecha = array(
  'name' => 'fecha',
  'id' => 'fecha',
  'type' => 'datetime',
  'class' => 'form-control',
  'value' => set_value('fecha', date("Y/m/d")),
  'readonly' => 'true',
  'required' => 'true'
);
?>
<script type="text/javascript">
var opslavados = "<option label='Selecciona'>Selecciona</option>";
$.each(<?php echo json_encode($lavados); ?>, function(index,val){
  opslavados += "<option value='" + val.id + "'>" + val.nombre + "</option>";
});
var opsprocesos = "";
$.each(<?php echo json_encode($procesos); ?>, function(index,val){
  opsprocesos += "<option value='" + val.id + "'>" + val.nombre + "</option>";
});
function carga(num) {
  $('#tabla tbody').html("");
  for (var i = 1; i <= num; i++) {
    var result = "<tr><td>" + i + "</td><td><select required name='lavado[" + i + "]' class='form-control'>"
    + opslavados + "</select></td><td><select required name='proceso_seco[" + i + "][]' id='proceso_seco" + i + "' class='form-control' multiple='multiple'>"
    + opsprocesos + "</select></td><td><input type='text' class='form-control' name='color_hilo[" + i + "]'></td><td><input type='text' class='form-control' name='tipo[" + i + "]'></td></tr>";
    $('#tabla tbody').append(result);
    if ($(window).width() >= 700) {
      $('#proceso_seco' + i).multiselect({
        nonSelectedText: '¡Selecciona!',
        buttonWidth: '100%',
        maxHeight: '150',
        numberDisplayed: 1,
        templates: {
          li: '<li><a class="dropdown-item"><label class="m-0 pl-2 pr-0"></label></a></li>',
          ul: ' <ul class="multiselect-container dropdown-menu p-1 m-0"></ul>',
          button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown" data-flip="false"><span class="multiselect-selected-text"></span> <b class="caret"></b></button>',
          filter: '<li class="multiselect-item filter"><div class="input-group m-0"><input class="form-control multiselect-search" type="text"></div></li>',
          filterClearBtn: '<span class="input-group-btn"><button class="btn btn-secondary multiselect-clear-filter" type="button"><i class="fas fa-minus-circle"></i></button></span>'
        },
        buttonClass: 'btn btn-secondary'
      });
    }
  }
}
$(document).ready(function() {
  $("#folio").focus().keyup(function() {
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      url: "<?php echo base_url() ?>index.php/ajax/autorizacionCorte",
      data: { folio: $('#folio').val() },
      dataType: 'json',
      type: 'POST',
      success: function(result) {
        if (result.info!=''){
          $("#imagenModal").html(result.info.imagen);
          $("#folioModal").html(result.info.folio);
          $("#corteModal").html(result.info.corte);
          $("#marcaModal").html(result.info.marca);
          $("#maquileroModal").html(result.info.maquilero);
          $("#clienteModal").html(result.info.cliente);
          $("#tipoModal").html(result.info.tipo);
          $("#fechaModal").html(result.info.fecha);
          $("#piezasModal").html(result.info.piezas);
          $("#info").show();
        }
        else $("#info").hide();
        $("#respuesta").html(decodeURIComponent(escape(result.respuesta)));
      }
    });
  });
  $("#info").click(function() {
    $("#infoCorte").modal("show");
  }).hide();
  $('#autorizar').submit(function() {
    return ($('#respuesta input').length);
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <form name="autorizar" id="autorizar" action="autorizar" method="post">
        <h1>Autorizar Corte</h1>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <?php echo form_input($input_folio); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="fecha" class="col-3 col-form-label">Fecha</label>
          <div class="col-9">
            <?php echo form_input($input_fecha); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="cargas" class="col-3 col-form-label">Cargas</label>
          <div class="col-9">
            <input type="number" name="cargas" value="0" onkeyup="carga(this.value);" onchange="carga(this.value);" class="form-control">
          </div>
        </div>
        <div class="form-group row">
          <div class="table-responsive col-12">
            <table id="tabla" class="table">
              <thead>
                <tr>
                  <td>#</th>
                  <th>Lavado</th>
                  <th>Proceso Seco</th>
                  <th>Color de hilo</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="form-group">
          <div class="offset-11">
            <button type="button" class="btn btn-info" name="info" id="info">
              <i class="fas fa-info"></i>
            </button>
          </div>
          <div id='respuesta'>
            <div class="alert alert-info" role="alert">Escriba el número del corte.</div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="infoCorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Información del corte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <td>Imágen</td>
                    <td id="imagenModal"></td>
                  </tr>
                  <tr>
                    <td>Folio</td>
                    <td id="folioModal"></td>
                  </tr>
                  <tr>
                    <td>Corte</td>
                    <td id="corteModal"></td>
                  </tr>
                  <tr>
                    <td>Marca</td>
                    <td id="marcaModal"></td>
                  </tr>
                  <tr>
                    <td>Maquilero</td>
                    <td id="maquileroModal"></td>
                  </tr>
                  <tr>
                    <td>Cliente</td>
                    <td id="clienteModal"></td>
                  </tr>
                  <tr>
                    <td>Tipo</td>
                    <td id="tipoModal"></td>
                  </tr>
                  <tr>
                    <td>Fecha de entrada</td>
                    <td id="fechaModal"></td>
                  </tr>
                  <tr>
                    <td>Piezas</td>
                    <td id="piezasModal"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
