<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
var ban = false;
$(document).ready(function() {
  $("#folio").focus().keyup(function() {
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
        //console.log(request.responseText);
      },
      url: "<?php echo base_url() ?>index.php/ajax/primerProceso",
      data: { folio: $('#folio').val() },
      dataType: 'json',
      type: 'POST',
      success: function(result) {
        if (result.info != ''){
          console.log(result);
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
          ban = true;
          var cadena = "";
          cadena += "<table class='table table-striped'><thead><tr><th># Carga</th><th>Lavado</th><th>Color de hilo</th><th>Tipo</th><th># Piezas</th><th>Abrir con el proceso</th></tr></thead><tbody>";
          $.each(result.respuesta, function(index,val){
            cadena += "<tr><td>"  + val.id_carga + "</td><td>"  + val.lavado + "</td><td>"  + val.color_hilo + "</td><td>"  + val.tipo + "</td><td><input type='text' class='form-control' name='piezas[" + val.id + "]' readonly value='" + val.piezas + "'></td><td><select name='abrirCon[" + val.id + "]' class='form-control'>";
            $.each(val.procesos, function(index2,val2){
              cadena += "<option value='" + val2.id + "'>" + val2.nombre + "</option>"
            });
            "</select></td></tr>"
          });
          cadena += "</tbody></table><input type='submit' class='btn btn-primary' value='Aceptar'>";
          $("#respuesta").html(cadena);
        }
        else {
          $("#info").hide();
          $("#respuesta").html(result.respuesta);
        }
      }
    });
  });
  $("#info").click(function() {
    $("#infoCorte").modal("show");
  }).hide();
  $('#primerProceso').submit(function() {
    return ban;
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <form name="primerProceso" id="primerProceso" action="primerProceso" method="post">
        <h1>Abrir primer proceso de cargas</h1>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="number" name="folio" value="" id="folio" class="form-control" placeholder="Ingresa folio de corte" required="true">
          </div>
        </div>
        <div class="form-group">
          <div id='respuesta'>
            <div class="alert alert-info" role="alert">Escriba el número del corte.</div>
          </div>
          <div class="offset-11">
            <button type="button" class="btn btn-info" name="info" id="info">
              <i class="fas fa-info"></i>
            </button>
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
