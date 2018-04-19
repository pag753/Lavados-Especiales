<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(document).ready(function(){
  $('#folio').keyup(function(){
    $.ajax({
      url : "<?php echo base_url() ?>index.php/ajax/salidaInterna",
      data : { folio : $('#folio').val() },
      dataType : 'text',
      type : 'POST',
      success: function(result){
      $("#complemento").html(result);
    }});
  });
  $( "form" ).submit(function( event ){
    var suma=0;
    for (var i = 0; i < $('#cargas').val(); i++)
    suma+=parseInt($('#piezas_parcial'+i).val());
    suma+=parseInt($('#muestras').val());
    if(suma!=$('#piezas').val()){
      alert("La suma de las piezas y las muestras no son iguales que el total");
      return false;
    }
    else{
      var fechabd=$('#fechabd').val();
      var fecha=$('#fecha').val().substr(0,10);
      var fbd=new Date(fechabd.split("-")[0],fechabd.split("-")[1],fechabd.split("-")[2]);
      var f=new Date(fecha.split("-")[0],fecha.split("-")[1],fecha.split("-")[2]);
      if(f>=fbd)
        return true;
      else{
        alert("La fecha que ingresó no puede ser anterior a la de su autorización");
        return false;
      }
    }
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="<?php echo base_url(); ?>index.php/Gestion/salidaInterna/" method="post" enctype="multipart/form-data">
        <h1>Salida Interna</h1>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="text" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required="true" />
          </div>
        </div>
        <div class="form-group row">
          <label for="fecha" class="col-3 col-form-label">Fecha</label>
          <div class="col-9">
            <input type='datetime-local' name='fecha' id='fecha' readonly='true' class='form-control' value='<?php echo date("Y-m-d")."T00:00" ?>'/>
          </div>
        </div>
        <div id="complemento" name="complemento">
          <div class="alert alert-info" role="alert">
            Ingresa el número de folio.
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
