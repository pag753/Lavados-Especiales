<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aportaciones = 0;
$retiros = 0;
?>
<script type="text/javascript">
function editar(id) {
  $('#aportacionE').val(($('#aportacion'+id).text().trim() == 'Retiro')? 0 : 1);
  $('#fechaE').val($('#fecha'+id).text());
  $('#razonE').val($('#razon'+id).text());
  $('#cantidadE').val($('#cantidad'+id).text());
  $('#idE').val(id);
  $('#editar').modal('show');
}
function eliminar(id){
  var r = confirm("¿Está seguro de que desea elimiar el ahorro seleccionado?");
  if (r) {
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      type: "POST",
      url: "eliminarAhorro",
      data: { "id": id },
      success: function(res) {
        if (res.respuesta)
        location.reload();
      },
      dataType: "json"
    });
  }
}
$(document).ready(function() {
  $('#tabla').DataTable({
    language: {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    },
    "lengthMenu": [ 5, 10, 20, 50, 100 ],
  });
});
</script>
<input type="hidden" name="bandera" id="bandera" value="">
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="col-12">
        <h3>Ahorros del usuario <?php echo $usuario[0]['nombre_completo']; ?></h3>
      </div>
      <div class='table-responsive'>
        <table id="tabla" class="table" style="background: rgba(255, 255, 255, 0.9);">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Fecha</th>
              <th>Cantidad $</th>
              <th>Editar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ahorros as $key => $value): ?>
              <tr>
                <td id="aportacion<?php echo $value['id']; ?>"><?php echo ($value['aportacion']!=0) ? "Aportación" : "Retiro" ; ($value['aportacion']!=0) ? $aportaciones += $value['cantidad'] : $retiros += $value['cantidad'] ;?></td>
                <td id="fecha<?php echo $value['id'] ?>"><?php echo $value['fecha']; ?></td>
                <td id="cantidad<?php echo $value['id'] ?>"><?php echo $value['cantidad']; ?></td>
                <td>
                  <button type="button" class="btn btn-success" onclick="editar(<?php echo $value['id']; ?>)">
                    <i class="far fa-edit"></i>
                  </button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="eliminar(<?php echo $value['id']; ?>)">
                    <i class="far fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center">
        <div class="p-2">
          <button type="button" name="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo">
            <i class="fas fa-plus"></i> Nuevo
          </button>
        </div>
        <div class="p-2">
          <button type="button" name="button" class="btn btn-info" data-toggle="modal" data-target="#total">
            <i class="fas fa-eye"></i> Ver total
          </button>
        </div>
      </div>
    </div>
  </div>
</diV>
<div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo ahorro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="new" name="new" action="nuevoAhorro" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="razon" class="col-3 col-form-label">Tipo</label>
            <div class="col-9">
              <select class="form-control" id="aportacion" name="aportacion">
                <option value="1">Aportación</option>
                <option value="0">Retiro</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="fecha" class="col-3 col-form-label">Fecha</label>
            <div class="col-9">
              <input class="form-control" name="fecha" id="fecha" required value="<?php echo date("Y-m-d"); ?>" type="date">
            </div>
          </div>
          <div class="form-group row">
            <label for="cantidad" class="col-3 col-form-label">Cantidad</label>
            <div class="col-9">
              <input type="number" step="any" class="form-control" name="cantidad" id="cantidad" placeholder="Cantidad" required>
            </div>
          </div>
          <input type="hidden" name="id" id="id" value="<?php echo $usuario[0]['id'] ?>">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="guardar" id="guardar" type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar ahorro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit" name="edit" action="editarAhorro" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="aportacionE" class="col-3 col-form-label">Tipo</label>
            <div class="col-9">
              <select class="form-control" name="aportacionE" id="aportacionE">
                <option value="1">Aportación</option>
                <option value="0">Retiro</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="fechaE" class="col-3 col-form-label">Fecha</label>
            <div class="col-9">
              <input class="form-control" name="fechaE" id="fechaE" value="" type="date">
            </div>
          </div>
          <div class="form-group row">
            <label for="cantidadE" class="col-3 col-form-label">Cantidad</label>
            <div class="col-9">
              <input type="number" step="any" class="form-control" name="cantidadE" id="cantidadE" placeholder="Cantidad" required>
            </div>
          </div>
          <input type="hidden" name="idE" id="idE" value=""> <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $usuario[0]['id'] ?>">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="guardar" id="guardar" type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="total" tabindex="-1" role="dialog" aria-labelledby="total" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ver total de ahorros</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table" style="background: rgba(255, 255, 255, 0.9);">
          <tbody>
            <tr>
              <td>Total de aportaciones</td>
              <td>$<?php echo $aportaciones; ?></td>
            </tr>
            <tr>
              <td>Total de retiros</td>
              <td>$<?php echo $retiros; ?></td>
            </tr>
            <tr>
              <td>Total de ahorro</td>
              <td>$<?php echo $aportaciones - $retiros; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
