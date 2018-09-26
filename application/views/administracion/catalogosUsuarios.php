<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
function editar(id) {
  $('#nombreE').val($('#nombre'+id).text());
  $('#tipo_usuario_idE').val($('#idTipoUsuario_'+id).val());
  $('#nombre_completoE').val($('#nombre_completo'+id).text());
  $('#direccionE').val($('#direccion'+id).text());
  $('#telefonoE').val($('#telefono'+id).text());
  $('#puesto_idE').val($('#puesto_id_'+id).val());
  if ($('#activo'+id).text() == 'No') $('#activoE').val(0);
  else $('#activoE').val(1);
  $('#id').val(id);
  $('#editar').modal('show');
}
$(document).ready(function() {
  var r='';
  $("#guardar").click(function(event) {
    $.ajax({
      error: function(request, status, error){
        window.location.replace("<?php echo base_url() ?>");
      },
      url: "<?php echo base_url(); ?>index.php/ajax/existeUsuario",
      data: { nombre: $("#nombre").val() },
      type: "POST",
      dataType: "text",
      success: function(result){
        if (result=="yes")  alert("El nombre de usuario ya existe, intente con otro por favor");
        else $("#new").submit();
      }
    });
  });
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
        <h3>Catálogo de Usuarios</h3>
      </div>
      <div class='table-responsive'>
        <table id="tabla" class="table" style="background: rgba(255, 255, 255, 0.9);">
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Tipo de usuario</th>
              <th>Puesto</th>
              <th>Nombre Completo</th>
              <th>Dirección</th>
              <th>Teléfono</th>
              <th>Activo</th>
              <th>Editar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $key => $value): ?>
              <tr>
                <td id="nombre<?php echo $value['id'] ?>"><?php echo $value['nombre']; ?></td>
                <td id="tipoUsuario<?php echo $value['id'] ?>"><?php foreach ($TipoUsuario as $key2 => $value2): if ($value['tipo_usuario_id']==$value2['id']): echo $value2['tipo_usuariocol']; ?>
                  <input type="hidden" name="idTipoUsuario_<?php echo $value['id'] ?>" id="idTipoUsuario_<?php echo $value['id'] ?>" value="<?php echo $value2['id'] ?>"><?php break; endif; endforeach; ?>
                </td>
                <td><?php foreach ($puestos as $key2 => $value2): if ($value['puesto_id']==$value2['id']): echo $value2['nombre']; ?>
                  <input type="hidden" name="puesto_id_<?php echo $value['id'] ?>" id="puesto_id_<?php echo $value['id'] ?>" value="<?php echo $value2['id'] ?>"><?php break; endif; endforeach; ?>
                </td>
                <td id="nombre_completo<?php echo $value['id'] ?>"><?php echo $value['nombre_completo']; ?></td>
                <td id="direccion<?php echo $value['id'] ?>"><?php echo $value['direccion']; ?></td>
                <td id="telefono<?php echo $value['id'] ?>"><?php echo $value['telefono']; ?></td>
                <td id="activo<?php echo $value['id'] ?>"><?php echo ($value['activo'] == 1)? "Sí":"No" ?></td>
                <td><a href="#" onclick="editar(<?php echo $value['id']; ?>)"><i class="far fa-edit"></i>Editar</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mx-auto">
        <button type="button" name="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo">
          <i class="fa fa-user-plus" aria-hidden="true"></i> Nuevo
        </button>
      </div>
    </div>
  </div>
</diV>
<div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="new" name="new" action="nuevoUsuario" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="nombre" class="col-3 col-form-label">Usuario</label>
            <div class="col-9">
              <input type="text" name="nombre" id="nombre" placeholder="Nombre del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="tipo_usuario_id" class="col-3 col-form-label">Tipo de Usuario</label>
            <div class="col-9">
              <select class="form-control" name="tipo_usuario_id" id="tipo_usuario_id">
                <?php foreach ($TipoUsuario as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['tipo_usuariocol'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="puesto_id" class="col-3 col-form-label">Puesto</label>
            <div class="col-9">
              <select class="form-control" name="puesto_id" id="puesto_id">
                <?php foreach ($puestos as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="pass" class="col-3 col-form-label">Contraseña</label>
            <div class="col-9">
              <input type="password" class="form-control" name="pass" id="pass" value="" placeholder="Contraseña del usuario" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="nombre_completo" class="col-3 col-form-label">Nombre Completo</label>
            <div class="col-9">
              <input type="text" name="nombre_completo" id="nombre_completo" placeholder="Nombre completo del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="direccion" class="col-3 col-form-label">Dirección</label>
            <div class="col-9">
              <input type="text" name="direccion" id="direccion" placeholder="Dirección del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="telefono" class="col-3 col-form-label">Teléfono</label>
            <div class="col-9">
              <input type="text" name="telefono" id="telefono" placeholder="Teléfono del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="activo" class="col-3 col-form-label">Activo</label>
            <div class="col-9">
              <select id="activo" name="activo" class="form-control">
                <option value="1">Sí</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="guardar" id="guardar" type="button" class="btn btn-primary">Guardar</button>
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
        <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="editarUsuario" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" id="id">
          <div class="form-group row">
            <label for="nombreE" class="col-3 col-form-label">Usuario</label>
            <div class="col-9">
              <input readonly type="text" name="nombreE" id="nombreE" placeholder="Nombre del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="tipo_usuario_idE" class="col-3 col-form-label">Tipo de Usuario</label>
            <div class="col-9">
              <select class="form-control" name="tipo_usuario_idE" id="tipo_usuario_idE">
                <?php foreach ($TipoUsuario as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['tipo_usuariocol'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="puesto_idE" class="col-3 col-form-label">Puesto</label>
            <div class="col-9">
              <select class="form-control" name="puesto_idE" id="puesto_idE">
                <?php foreach ($puestos as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="pass" class="col-3 col-form-label">Contraseña</label>
            <div class="col-9">
              <input type="password" class="form-control" id="passE" name="passE" value="" placeholder="Cambiar contraseña">
            </div>
          </div>
          <div class="form-group row">
            <label for="nombre_completoE" class="col-3 col-form-label">Nombre Completo</label>
            <div class="col-9">
              <input type="text" name="nombre_completoE" id="nombre_completoE" placeholder="Nombre completo del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="direccionE" class="col-3 col-form-label">Dirección</label>
            <div class="col-9">
              <input type="text" name="direccionE" id="direccionE" placeholder="Dirección del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="telefonoE" class="col-3 col-form-label">Teléfono</label>
            <div class="col-9">
              <input type="text" name="telefonoE" id="telefonoE" placeholder="Teléfono del usuario" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="activoE" class="col-3 col-form-label">Activo</label>
            <div class="col-9">
              <select id="activoE" name="activoE" class="form-control">
                <option value="1">Sí</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
