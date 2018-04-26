<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
  function editar(id) {
    $('#nombreE').val($('#nombre'+id).text());
    $('#direccionE').val($('#direccion'+id).text());
    $('#telefonoE').val($('#telefono'+id).text());
    $('#id').val(id);
    $('#editar').modal('show');
  }
  $(document).ready(function() {
    $( '#tabla' ).DataTable({
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
<div class="container">
  <div class="row">
    <div class="col-lg-10 col-md-10 col-xs-6 offset-lg-1 offset-md-1 offset-xs-3">
      <div class="col-12">
        <h3>Catálogo de Clientes</h3>
      </div>
      <div class='table-responsive'>
        <table name="tabla" id="tabla" class="table" style="background:rgba(255,255,255,0.9);">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Dirección</th>
              <th>Teléfono</th>
              <th>Editar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $key => $value): ?>
              <tr>
                <td name="nombre<?php echo $value['id'] ?>" id="nombre<?php echo $value['id'] ?>"><?php echo $value['nombre']; ?></td>
                <td name="direccion<?php echo $value['id'] ?>" id="direccion<?php echo $value['id'] ?>"><?php echo $value['direccion']; ?></td>
                <td name="telefono<?php echo $value['id'] ?>" id="telefono<?php echo $value['id'] ?>"><?php echo $value['telefono']; ?></td>
                <td><a href="#" onclick="editar(<?php echo $value['id']; ?>)"><i class="far fa-edit"></i>Editar</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
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
        <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(); ?>index.php/administracion/nuevoCliente" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="nombre" class="col-3 col-form-label">Nombre</label>
            <div class="col-9">
              <input type="text" name="nombre" id="nombre" placeholder="Nombre del cliente" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="direccion" class="col-3 col-form-label">Dirección</label>
            <div class="col-9">
              <input type="text" name="direccion" id="direccion" placeholder="Dirección del cliente" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="telefono" class="col-3 col-form-label">Teléfono</label>
            <div class="col-9">
              <input type="text" name="telefono" id="telefono" placeholder="Teléfono del cliente" required class="form-control">
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
        <h5 class="modal-title" id="exampleModalLabel">Editar cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(); ?>index.php/administracion/editarCliente" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" id="id">
          <div class="form-group row">
            <label for="nombreE" class="col-3 col-form-label">Nombre</label>
            <div class="col-9">
              <input type="text" name="nombreE" id="nombreE" placeholder="Nombre del cliente" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="direccionE" class="col-3 col-form-label">Dirección</label>
            <div class="col-9">
              <input type="text" name="direccionE" id="direccionE" placeholder="Dirección del cliente" required class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="telefonoE" class="col-3 col-form-label">Teléfono</label>
            <div class="col-9">
              <input type="text" name="telefonoE" id="telefonoE" placeholder="Teléfono del cliente" required class="form-control">
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
