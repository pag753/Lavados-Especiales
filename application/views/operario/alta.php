<?php
defined('BASEPATH') or exit('No direct script access allowed');
$input_folio = array(
  'type' => 'number',
  'name' => 'folio',
  'id' => 'folio',
  'class' => 'form-control',
  'value' => set_value('folio', @$folio),
  'placeholder' => 'Ingrese número de folio'
);
?>
<script type="text/javascript">
$(document).ready(function() {
  $("#folio").focus().keyup(function() {
    $.ajax({
      error: function(request, status, error){folio
        window.location.replace("<?php echo base_url() ?>");
      },
      type: "POST",
      url: "<?php echo base_url() ?>/index.php/ajax/operarioProcesos",
      data: { folio: $("#folio").val() },
      success: function(res) {
        if (res.datos[0] == '<') $('#mensaje').html(res.datos)
        else {
          console.log(res.datos);
          var cadena = "<p class='text-primary'>Seleccione el proceso en el que desea agregar la producción correspondiente del corte con folio " + $("#folio").val() + "</p><div class='table-responsive'><table class='table table-striped'><thead><tr><th># Carga</th><th>Lavado</th><th>Proceso seco</th><th>Color de hilo</th><th>Tipo</th><th>Cerra el proceso</th></tr></thead><tbody>";
          $.each(res.datos, function( index, value ) {
            cadena += "<tr><td>" + value.id_carga + "</td><td>" + value.lavado + "</td><td>" + value.proceso + "</td><td>" + value.color_hilo + "</td><td>" + value.tipo + "</td><td><a title='Cerrar este proceso' href='alta?id=" + value.id + "&f=" + $("#folio").val() + "&c=" + value.id_carga + "&p=" + value.idproceso + "&nc=" + value.lavado + "&np=" + value.proceso + "&il=" + value.idlavado + "&m=" + res.corte[0].marca + "&cl=" + res.corte[0].cliente + "&pie=" + res.corte[0].piezas + "&color_hilo=" + value.color_hilo + "&tipo=" + value.tipo + "'><i class='far fa-edit'></i></a></td></tr>"
          });
          cadena += "</tbody></table></div>";
          $('#mensaje').html(cadena);
        }
      },
      dataType: "json"
    });
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <h3>Cerrar proceso</h3>
      <div class="form-group row">
        <label for="folio" class="col-3 col-form-label">Folio</label>
        <div class="col-9">
          <?php echo form_input($input_folio); ?>
        </div>
      </div>
      <div id="mensaje" style="background-color: rgba(255,255,255,.8)">
        <div class="alert alert-info" role="alert">Inserta el número de folio.</div>
      </div>
    </div>
  </div>
</div>
