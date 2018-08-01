<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
function alta(id) {
  $.ajax({
    error: function(request, status, error){
      window.location.replace("<?php echo base_url() ?>");
    },
    url: "<?php echo base_url() ?>index.php/gestion/salidaExterna",
    data: {
      id : id,
    },
    type: 'POST',
    dataType: 'json',
    success: function(result) {
      window.location.reload(true);
    }
  });
}
$(document).ready(function() {
  $("#folio").focus();
  $("#info").hide();
  $("#info").click(function() {
    $("#infoCorte").modal("show");
  });
  $('#folio').keyup(function() {
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      url: "<?php echo base_url() ?>index.php/ajax/salidaExterna",
      data: { folio: $('#folio').val() },
      type: 'POST',
      dataType: 'json',
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
          $("#ojalesModal").html(result.info.ojales);
          $("#info").show();
        }
        else $("#info").hide();
        if (result.respuesta[0] == '<') $("#complemento").html(result.respuesta);
        else {
          if (result.respuesta.length == 0) $("#complemento").html('<div class="alert alert-warning" role="alert">No hay cargas que registrar en almacén para este corte.</div>');
          else {
            var cadena = "<strong>Seleccione la carga que desea dar salida externa.</strong><table class='table table-bordered' id='tabla'><thead><tr><th># Carga</th><th>Lavado</th><th>Color de hilo</th><th>Tipo</th><th>Dar salida externa</th></tr></thead><tbody class='table-success'>";
            $.each(result.respuesta,function(index,value) {
              cadena += "<tr class='table-succes'><td>" + value.idcarga + "</td><td>" + value.lavado + "</td><td>" + value.color_hilo + "</td><td>" + value.tipo + "</td><td><button type='button' class='btn btn-info' onclick='alta(" + value.id_corte_autorizado + ")' title='Dar clic aquí para dar salida externa a esta carga.'><i class='fas fa-arrow-up'></i></button></td></tr>";
            });
            cadena += "</tbody></table>";
            $("#complemento").html(cadena);
          }
        }
      }
    });
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <form action="salidaExterna" method="post" enctype="multipart/form-data">
        <h1>Salida Externa</h1>
        <table class="table table-striped table-bordered">
          <tbody>
            <th>Folio</th>
            <td><input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required /></td>
          </tbody>
        </table>
        <div id="complemento">
          <div class="alert alert-info" role="alert">Ingresa el número de folio.</div>
        </div>
        <div>
          <button type="button" class="btn btn-info" name="info" id="info" title="Dar clic aquí para ver la información del corte.">
            <i class="fas fa-info"></i>
          </button>
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
                  <tr>
                    <td>Ojales</td>
                    <td id="ojalesModal"></td>
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
