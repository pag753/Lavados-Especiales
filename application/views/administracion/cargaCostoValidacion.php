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
  $('#info').hide().click(function() {
    $('#infoCorte').modal('show');
  });
  $("#folio").focus().keyup(function() {
    $('#info').hide();
    if($('#folio').val() == "") {
      $('#cargas').html("<div class='col-12'><div class='alert alert-info' role='alert'>Escribe el número de folio.</div></div>");
    }
    else {
      $.ajax({
        error: function(request, status, error){
          window.location.replace("<?php echo base_url() ?>");
          //console.log(request);
        },
        url: "<?php echo base_url() ?>index.php/ajax/costosAdministracion",
        data: { folio: $('#folio').val() },
        type: 'POST',
        dataType: "json",
        success: function(result) {
          if (result.datos[0] == "<") {
            $('#cargas').html(result.datos);
          }
          else {
            //console.log(result);
            $('#info').show();
            $("#imagenModal").html(result.corte.imagen);
            $("#folioModal").html(result.corte.folio);
            $("#corteModal").html(result.corte.corte);
            $("#marcaModal").html(result.corte.marca);
            $("#maquileroModal").html(result.corte.maquilero);
            $("#clienteModal").html(result.corte.cliente);
            $("#tipoModal").html(result.corte.tipo);
            $("#fechaModal").html(result.corte.fecha);
            $("#piezasModal").html(result.corte.piezas);
            var cadena = "<table class='table table-striped'><thead><tr><th># Carga</th><th>Lavado</th><th>Color de hilo</th><th>Tipo</th><th>Editar</th></tr></thead><tbody>";
            $.each(result.datos, function(index,val){
              cadena += "<tr><td>" + val.id_carga + "</td><td>" + val.lavado + "</td><td>" + val.color_hilo + "</td><td>" + val.tipo + "</td><td><a href='costos?id=" + val.id + "&folio=" + $('#folio').val() + "&carga=" + val.id_carga + "&color_hilo=" + val.color_hilo + "&id_carga=" + val.id_carga + "&tipo=" + val.tipo + "'><i class='fa fa-info' aria-hidden='true'></i></a></td></tr>";
            });
            cadena +="</tbody></table>";
            $('#cargas').html(cadena);
          }
        }
      });
    }
  });
if ($("#folio").val() != "" ) {
  $("#folio").keyup();
}
});
</script>
<div class="container-fluid">
  <div class="table">
    <div class="row">
      <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
        <h3 class="white"><?php echo $texto1; ?></h3>
        <!--<form action="costos" id="costos" method="get" enctype="multipart/form-data">-->
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9"><?php echo form_input($input_folio); ?></div>
        </div>
        <div id="cargas" class="form-group row">
          <div class="col-12"><div class="alert alert-info" role="alert">Escribe el número de folio.</div></div>
        </div>
        <button type="button" id="info" class="btn btn-info"><i class="fa fa-info" aria-hidden="true"></i></button>
        <!--</form>-->
      </div>
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
