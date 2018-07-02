<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
  $(document).ready(function() {
    $("#folio").focus();
    $("#info").hide();
    $("#info").click(function() {
      $("#infoCorte").modal("show");
    });
    $('#folio').keyup(function() {
      $.ajax({
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
          else
            $("#info").hide();
          $("#complemento").html(result.respuesta);
        }
      });
    });
  });
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="salidaExterna" method="post" enctype="multipart/form-data">
        <h1>Salida Externa</h1>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required />
          </div>
        </div>
        <div id="complemento">
          <div class="alert alert-info" role="alert">Ingresa el número de folio.</div>
        </div>
        <div>
          <button type="button" class="btn btn-info" name="info" id="info">
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
