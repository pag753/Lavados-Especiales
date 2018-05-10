<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
  $(document).ready(function(){
    $("#info").hide();
    $("#info").click(function() {
      $("#infoCorte").modal("show");
    });
    $('#folio').keyup(function(){
      $.ajax({
        url: "<?php echo base_url() ?>index.php/ajax/salidaAlmacen",
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
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h1>Entrega a Almacen</h1>
        </div>
        <div class="card-body">
          <input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese folio" required="true" />
        </div>
      </div>
      <div id="complemento" name="complemento">
        <div class="alert alert-info" role="alert">
          Ingresa el número de folio.
        </div>
      </div>
      <div>
        <button type="button" class="btn btn-info" name="info" id="info" ><i class="fas fa-info"></i></button>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="infoCorte" name="infoCorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Información del corte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <td>Imágen</td>
                    <td name="imagenModal" id="imagenModal"></td>
                  </tr>
                  <tr>
                    <td>Folio</td>
                    <td name="folioModal" id="folioModal"></td>
                  </tr>
                  <tr>
                    <td>Corte</td>
                    <td name="corteModal" id="corteModal"></td>
                  </tr>
                  <tr>
                    <td>Marca</td>
                    <td name="marcaModal" id="marcaModal"></td>
                  </tr>
                  <tr>
                    <td>Maquilero</td>
                    <td name="maquileroModal" id="maquileroModal"></td>
                  </tr>
                  <tr>
                    <td>Cliente</td>
                    <td name="clienteModal" id="clienteModal"></td>
                  </tr>
                  <tr>
                    <td>Tipo</td>
                    <td name="tipoModal" id="tipoModal"></td>
                  </tr>
                  <tr>
                    <td>Fecha de entrada</td>
                    <td name="fechaModal" id="fechaModal"></td>
                  </tr>
                  <tr>
                    <td>Piezas</td>
                    <td name="piezasModal" id="piezasModal"></td>
                  </tr>
                  <tr>
                    <td>Ojales</td>
                    <td name="ojalesModal" id="ojalesModal"></td>
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
