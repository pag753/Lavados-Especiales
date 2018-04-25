<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
$input_folio = array(
  'name' => 'folio',
  'id' => 'folio',
  'type' => 'text',
  'class' => 'form-control',
  'placeholder' => 'Ingresa folio de corte',
  'value' => set_value('folio',@$datos_corte->folio),
  'required' => 'true',
);
$input_fecha = array(
  'name' => 'fecha',
  'id' => 'fecha',
  'type' => 'datetime-local',
  'class' => 'form-control',
  'value' => set_value('fecha',date("Y-m-d")."T00:00"),
  'readonly' => 'true',
  'required' => 'true',
);
?>
<script>
$(document).ready(function() {
  $('#folio').keyup(function() {
    $.ajax({
      url: "<?php echo base_url() ?>index.php/ajax/autorizacionCorte",
      data: { folio: $('#folio').val() },
      dataType: 'text',
      type: 'POST',
      success: function(result) {
      $("#respuesta").html(result);
    }});
  });
  $( "form" ).on( "click", "button", function() {
    if (this.name=="boton") {
      var numero=$( "#numero" );
      var numero2=$( "#numero2" );
      $.ajax({
        url: "<?php echo base_url() ?>index.php/ajax/agregarRenglonProduccion",
        data: { numero: numero2.val() },
        dataType: 'text',
        type: 'POST',
        success: function(result){
          $( '#tabla tbody' ).append(result);
          $( '#proceso_seco'+numero2.val() ).multiselect({
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
            //buttonContainer: '<div class="dropdown" />',
            buttonClass: 'btn btn-secondary'
          });
          numero2.val(parseInt(numero2.val())+1);
          numero.val(parseInt(numero.val())+1);
        }
      });
    }
    else {
      if (this.id.substring(0,8)=="eliminar") {
        var renglon=this.id.substring(8);
        $( "#renglon"+renglon ).remove();
        var numero=$( "#numero" );
        numero.val(parseInt(numero.val())-1);
      }
    }
  });
  $('#autorizar').submit(function() {
    var numero=$("#numero").val();
    if (numero==0) {
      alert("Debe agregar por lo menos un lavado.");
      return false;
    }
    else {
      for (var i = 0; i < numero; i++) {
        if ($("#proceso_seco"+i).val()=="") {
          alert("Existe uno o varios lavados sin proceso.");
          return false;
        }
      }
      return true;
    }
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form name="autorizar" id="autorizar" action="<?php echo base_url(); ?>index.php/produccion/autorizar" method="post">
        <h1>Autorizar Corte</h1>
        <input type="hidden" name="numero" id="numero" value="0">
        <input type="hidden" name="numero2" id="numero2" value="0">
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
            <table name="tabla" id="tabla" class="table">
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
            <center>
              <button type="button" name="boton" id="boton" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Lavado</button>
            </center>
          </div>
        </div>
        <div class="form-group">
          <div name='respuesta' id='respuesta'>
            <div class="alert alert-info" role="alert">
              Escriba el número del corte.
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
