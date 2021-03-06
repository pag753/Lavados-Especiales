<?php
defined('BASEPATH') or exit('No direct script access allowed');
$input_folio = array(
  'name' => 'folio',
  'id' => 'folio',
  'readonly' => 'true',
  'type' => 'number',
  'class' => 'form-control',
  'value' => $corte
);
$input_fecha = array(
  'name' => 'fecha',
  'id' => 'fecha',
  'type' => 'date',
  'class' => 'form-control',
  'value' => set_value('fecha', date("Y-m-d")),
  'readonly' => 'true'
);
$input_corte = array(
  'name' => 'corte',
  'id' => 'corte',
  'type' => 'text',
  'class' => 'form-control',
  'placeholder' => 'Inserte corte',
  'required' => 'true'
);
$input_piezas = array(
  'name' => 'piezas',
  'id' => 'piezas',
  'type' => 'number',
  'class' => 'form-control',
  'placeholder' => 'Inserte # de piezas',
  'required' => 'true'
);
$input_imagen = array(
  'name' => 'mi_imagen',
  'id' => 'mi_imagen',
  'type' => 'file',
  'class' => 'form-control-file',
  'onchange' => 'cambio(this.files);',
  'accept' => 'image/png, .jpeg, .jpg, image/gif',
);
foreach ($maquileros as $key => $value) $opciones_maquilero[$value['id']] = $value['nombre'];
$select_maquilero = array(
  'name' => 'maquilero',
  'id' => 'maquilero',
  'class' => 'form-control'
);
$opciones_cliente[- 1] = "Seleccione el cliente";
foreach ($clientes as $key => $value) $opciones_cliente[$value['id']] = $value['nombre'];
$select_cliente = array(
  'name' => 'cliente',
  'id' => 'cliente',
  'class' => 'form-control'
);
foreach ($tipos as $key => $value) $opciones_tipo[$value['id']] = $value['nombre'];
$select_tipo = array(
  'name' => 'tipo',
  'id' => 'tipo',
  'class' => 'form-control'
);
?>
<script type="text/javascript">
function cambio(c) {
  file = c[0];
  var img = document.createElement("img");
  img.classList.add("img-fluid");
  img.file = file;
  $('#nuevaImagen').html(img); // Assuming that "preview" is the div output where the content will be displayed.
  var reader = new FileReader();

  reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
  reader.readAsDataURL(file);
}
$(document).ready(function() {
  $("#corte").focus();
  $("#ojales").hide();
  $("#ojal").click(function(){
    if ($(this).is(":checked")) $("#ojales").show();
    else {
      $("#ojales").hide();
      $("#cantidadOjales").val(0);
    }
  });
  $('#cliente').change(function() {
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      url: "<?php echo base_url() ?>index.php/ajax/gestionMarcas",
      data: { cliente: $('#cliente').val() },
      type: 'POST',
      dataType: 'text',
      success: function(result){
        $('#marcas').html(result);
      }
    });
  });
  $("form").submit(function( event ) {
    var val=$("#cliente").val();
    if(parseInt(val)==-1) {
      alert("Por favor escoja un cliente.");
      return false;
    }
    else return true;
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="alta" method="post" enctype="multipart/form-data">
        <h1>Alta de Corte</h1>
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
          <label for="corte" class="col-3 col-form-label">Corte</label>
          <div class="col-9">
            <?php echo form_input($input_corte); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="piezas" class="col-3 col-form-label">Piezas</label>
          <div class="col-9">
            <?php echo form_input($input_piezas); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="cliente" class="col-3 col-form-label">Cliente</label>
          <div class="col-9">
            <?php echo form_dropdown($select_cliente,$opciones_cliente,set_value('cliente',@$datos_corte[0]->cliente)); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="marca" class="col-3 col-form-label">Marca</label>
          <div class="col-9">
            <div id='marcas'>
              <div class="alert alert-warning" role="alert">Escoja primero el cliente.</div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <label for="maquilero" class="col-3 col-form-label">Maquilero</label>
          <div class="col-9">
            <?php echo form_dropdown($select_maquilero,$opciones_maquilero,set_value('maquilero',@$datos_corte[0]->maquilero)); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="tipo" class="col-3 col-form-label">Tipo</label>
          <div class="col-9">
            <?php echo form_dropdown($select_tipo,$opciones_tipo,set_value('tipo',@$datos_corte[0]->tipo)); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="ojal" class="col-3 col-form-label">¿Lleva ojal?</label>
          <div class="col-9">
            <div class="form-check">
              <label class="form-check-label"> <input class="form-check-input" type="checkbox" name="ojal" id="ojal"> Sí
              </label>
            </div>
          </div>
        </div>
        <div class="form-group row" id="ojales">
          <label for="ojales" class="col-3 col-form-label">Cantidad de ojales</label>
          <div class="col-9">
            <input class="form-control" placeholder="Escribe cantidad de ojales" type="number" name="cantidadOjales" id="cantidadOjales" value="0">
          </div>
        </div>
        <div class="form-group row">
          <label for="imagen" class="col-3 col-form-label">Imágen</label>
          <div class="col-9">
            <?php echo form_input($input_imagen); ?>
          </div>
        </div>
        <div id="nuevaImagen" class="ml-auto"></div>
        <div class="ml-auto">
          <input type="submit" class="btn btn-primary" value="Aceptar" />
        </div>
      </form>
    </div>
  </div>
</div>
