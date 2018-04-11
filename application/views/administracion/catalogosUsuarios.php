<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
function editar(id){
  $('#nombreE').val($('#nombre'+id).text());
  $('#tipo_usuario_idE').val($('#idTipoUsuario_'+id).val());
  $('#nombre_completoE').val($('#nombre_completo'+id).text());
  $('#direccionE').val($('#direccion'+id).text());
  $('#telefonoE').val($('#telefono'+id).text());
  $('#id').val(id);
  $('#editar').modal('show');
}
$(document).ready(function() {

  $( "#nuevo" ).submit(function( event ) {
    var r;
    $.ajax({
      url: "<?php echo base_url(); ?>index.php/ajax/existeUsuario/"+$("#nombre").val(),
      success: function(result){
        r=result;
      }});
      if (r="yes") {
        alert("El nombre de usuario ya existe, intente con otro por favor");
        return false;
      }
      else
        return true;
  });

  $('#tabla').DataTable({
    language: {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    },
    "lengthMenu": [ 5, 10, 20, 50, 100 ],
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="col-12">
        <h3>Catálogo de Usuarios</h3>
      </div>
      <table name="tabla" id="tabla" class="table" style="background:rgba(255,255,255,0.9);">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Tipo de usuario</th>
            <th>Nombre Completo</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $value): ?>
          <tr>
            <td name="nombre<?php echo $value['id'] ?>" id="nombre<?php echo $value['id'] ?>"><?php echo $value['nombre']; ?></td>
            <td name="tipoUsuario<?php echo $value['id'] ?>" id="tipoUsuario<?php echo $value['id'] ?>">
              <?php
                  foreach ($TipoUsuario as $key2 => $value2)
                  {
                    if ($value['tipo_usuario_id']==$value2['id'])
                    {
                      echo $value2['tipo_usuariocol'];
                      echo "<input type='hidden' name='idTipoUsuario_".$value['id']."' id='idTipoUsuario_".$value['id']."' value=".$value2['id'].">";
                      break;
                    }
                  }
              ?>
            </td>
            <td name="nombre_completo<?php echo $value['id'] ?>" id="nombre_completo<?php echo $value['id'] ?>"><?php echo $value['nombre_completo']; ?></td>
            <td name="direccion<?php echo $value['id'] ?>" id="direccion<?php echo $value['id'] ?>"><?php echo $value['direccion']; ?></td>
            <td name="telefono<?php echo $value['id'] ?>" id="telefono<?php echo $value['id'] ?>"><?php echo $value['telefono']; ?></td>
            <td><a href="#" onclick="editar(<?php echo $value['id']; ?>)"><i class="far fa-edit">Editar</i></a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <center>
        <button type="button" name="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo"><i class="fa fa-user-plus" aria-hidden="true"></i> Nuevo</button>
      </center>
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
        <form  id="nuevo" name="nuevo" action="<?php echo base_url(); ?>index.php/administracion/nuevoUsuario" method="post" enctype="multipart/form-data">
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
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
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
        <form action="<?php echo base_url(); ?>index.php/administracion/editarUsuario" method="post" enctype="multipart/form-data">
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
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
