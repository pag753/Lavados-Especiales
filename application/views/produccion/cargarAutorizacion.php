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
<script>
function eliminar(renglon) {
  $("#renglon"+renglon).remove();
  var numero=$("#numero");
  numero.val(parseInt(numero.val())-1);
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
  $("#boton").click(function() {
    var numero=$("#numero");
    var numero2=$("#numero2");
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      url: "<?php echo base_url() ?>index.php/ajax/agregarRenglonProduccion",
      data: { numero: numero2.val() },
      dataType: 'text',
      type: 'POST',
      success: function(result){
        $('#tabla tbody').append(result);
        $('#proceso_seco'+numero2.val() ).multiselect({
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
        numero2.val(parseInt(numero2.val())+1);
        numero.val(parseInt(numero.val())+1);
      }
    });
  });
  $('#autorizar').submit(function() {
    var numero=$("#numero").val();
    if (numero==0) {
      alert("Debe agregar por lo menos un lavado.");
      return false;
    }
    else {
      if (!$('#respuesta input').length) {
        return false;
      }
      else {
        var lavados = $("[name*='lavado']");
        var id = -100;
        var bandera;
        $.each(lavados, function( index, value ) {
          if (id == value.value*1) {
            bandera = true;
          }
          else bandera = false;
          id = value.value*1;
        });
        if (bandera) {
          alert('No pueden haber 2 o más lavados iguales, favor de rectificar.');
          return false;
        }
        else return true;
      }
    }
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form name="autorizar" id="autorizar" action="autorizar" method="post">
        <h1>Autorizar Corte</h1>
        <input type="hidden" name="numero" id="numero" value="0"> <input type="hidden" name="numero2" id="numero2" value="0">
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
          <div class="col-12">
            <table id="tabla" class="table">
              <thead>
                <tr>
                  <th>Lavado</th>
                  <th>Proceso Seco</th>
                  <th>Eliminar</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12">
            <div class="mx-auto">
              <button type="button" name="boton" id="boton" class="btn btn-success">
                <i class="fas fa-plus"></i> Agregar Lavado
              </button>
            </div>
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
