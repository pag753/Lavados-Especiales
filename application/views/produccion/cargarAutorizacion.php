<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(document).ready(function(){
  $('#folio').keyup(function(){
    $.ajax({url: "<?php echo base_url() ?>index.php/ajax/autorizacionCorte/"+$('#folio').val(), success: function(result){
      $("#respuesta").html(result);
    }});
  });
  $( "form" ).on( "click", "button", function(){
    if(this.name=="boton"){
      var numero=$( "#numero" );
      $.ajax({url: "<?php echo base_url() ?>index.php/ajax/agregarRenglonProduccion/"+numero.val(), success: function(result){
        $( '#tabla tbody' ).append(result);
        $( '#proceso_seco'+numero.val() ).multiselect({
          nonSelectedText: '¡Selecciona!',
          buttonWidth: '100%',
          dropRight: true,
          maxHeight: 300,
          numberDisplayed: 1
        });
        numero.val(parseInt(numero.val())+1);
      }});
    }
    else{
      if(this.id.substring(0,8)=="eliminar"){
        var renglon=this.id.substring(8);
        $( "#renglon"+renglon ).remove();
        var numero=$( "#numero" );
        numero.val(parseInt(numero.val())-1);
      }
    }
  });
});
</script>
<?php
$input_folio = array('name'       => 'folio',
                     'id'         => 'folio',
                     'type'       => 'text',
                     'class'      => 'form-control',
                     'placeholder'=> 'Ingresa folio de corte',
                     'value'      => set_value('folio',@$datos_corte->folio),
                     'required'   => 'true',
                   );

$input_fecha = array('name'    => 'fecha',
                     'id'      => 'fecha',
                     'type'    => 'datetime-local',
                     'class'   => 'form-control',
                     'value'   => set_value('fecha',date("Y-m-d")."T00:00"),
                     'readonly'=> 'true',
                  'required'   => 'true',
                );
?>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="<?php echo base_url(); ?>index.php/produccion/autorizar" method="post">
        <h1>Autorizar Corte</h1>
        <input type="hidden" name="numero" id="numero" value="0">
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
          <div name='respuesta' id='respuesta' class="row col-12">
            <h3>Escriba el número del corte</h3>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
