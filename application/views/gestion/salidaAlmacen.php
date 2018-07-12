<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
function alta(idl) {
  $.ajax({
    error: function(request, status, error){
      window.location.replace("<?php echo base_url() ?>");
    },
    url: "<?php echo base_url() ?>index.php/gestion/salidaAlmacen",
    data: {
      folio: $('#folio').val(),
      idl: idl,
    },
    type: 'POST',
    dataType: 'text',
    success: function(result) {
      window.location.reload(true);
    }
  });
}
$(document).ready(function(){
  $("#complemento2").hide();
  $("#info").hide().click(function() {
    $("#infoCorte").modal("show");
  });
  $("#folio").focus().keyup(function(){
    $("#complemento2").hide();
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      url: "<?php echo base_url() ?>index.php/ajax/salidaAlmacen",
      data: { folio: $('#folio').val() },
      type: 'POST',
      dataType: 'json',
      success: function(result) {
        $('#tabla tbody').html('');
        if (result.info != ''){
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
            var cadena = "<div class='card'><div class='card-header'><a data-toggle='collapse' href='#ver' role='button' aria-expanded='true' aria-controls='ver'><strong>Datos específicos del folio " + result.info.folio + "</strong></a></div><div class='collapse' id='ver'><div class='card-body'><div class='table-responsive'><table class='table table-bordered'><thead><tr><th>Carga</th><th>Lavado</th><th>Proceso</th><th>Piezas Trabajdas</th><th>Defectos</th><th>Estado</th><th>Orden</th><th>Fecha de registro</th><th>Usuario que registró</th></tr></thead><tbody>";
            $("#complemento2").show();
            $.each(result.respuesta,function(index,value) {
              var idcarga = value.idcarga;
              var lavado = value.lavado;
              var proceso = value.proceso;
              var piezas = value.piezas;
              var defectos = value.defectos;
              var status = value.status;
              var clase = '';
              if (!$('#renglon' + idcarga).length) $('#tabla tbody').append("<tr id='renglon" + idcarga + "'><td>" + lavado + "</td><td><button type='button' class='btn btn-info' onclick='alta(" + value.idlavado + ")'><i class='fas fa-arrow-up'></i></button></td></tr>").attr('class','table-success')
              switch (status * 1)
              {
                case 0:
                // rojo
                status = "No registrado";
                clase = "table-danger";
                piezas = 'Ninguna';
                defectos = 'Ninguno';
                $('#renglon' + idcarga).html("<td>" + lavado + "</td><td>No se puede dar salida a almacén a este lavado porque tiene procesos sin cerrar, favor de verificar con los operarios y el encargado.</td>").attr('class','table-danger');
                break;
                case 1:
                // azul
                status = "Listo para registrar";
                clase = "table-primary";
                piezas = 'Ninguna';
                defectos = 'Ninguno';
                $('#renglon' + idcarga).html("<td>" + lavado + "</td><td>No se puede dar salida a almacén a este lavado porque tiene procesos sin cerrar, favor de verificar con los operarios y el encargado.</td>").attr('class','table-danger');
                break;
                case 2:
                // verde

                status = "Registrado";
                clase = "table-success";
                break;
                default:
                break;
              }
              var orden = value.orden;
              if (orden*1 == 0) orden = 'Ninguno';
              var fecha = value.fecha;
              if (fecha == "0000-00-00") fecha = "Sin fecha";
              usuario = value.usuario;
              if (usuario == "DEFAULT") usuario = "Por defecto";
              cadena += "<tr class='" + clase + "'><td>" + idcarga + "</td><td>" + lavado + "</td><td>" + proceso + "</td><td>" + piezas + "</td><td>" + defectos + "</td><td>" + status + "</td><td>" + orden + "</td><td>" + fecha + "</td><td>" + usuario + "</td></tr>";
            });
            cadena += "</tbody></table></div></div></div>";
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
      <div class="card">
        <div class="card-header">
          <h1>Entrega a Almacen</h1>
        </div>
        <div class="card-body">
          <input type="number" name="folio" id="folio" class="form-control" placeholder="Ingrese el número de folio" required autofocus title="Ingrese el número de folio" />
        </div>
      </div>
      <div id="complemento">
        <div class="alert alert-info" role="alert">Ingresa el número de folio.</div>
      </div>
      <div id="complemento2">
        <div class="card">
          <div class="card-header">
            <strong>Tabla de cargas</strong>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="tabla">
                <thead>
                  <tr>
                    <th>Lavado</th>
                    <th>Dar de alta a almacén</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <p><strong>Nota: </strong>Si nota que no aparecen cargas es porque ya fueron dadas de alta en el almacén.</p>
          </div>
        </div>
      </div>
      <div class="mx-auto">
        <button type="button" class="btn btn-info" name="info" id="info" title="Ver la información general del corte">
          <i class="fas fa-info"></i>
        </button>
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
        <div class="container">
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
