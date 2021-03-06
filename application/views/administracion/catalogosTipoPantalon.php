<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
function editar(id){
  $('#nombreE').val($('#nombre'+id).text());
  $('#id').val(id);
  $('#editar').modal('show');
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
        "sSortDescending": ": Activar para ordenar la columna de maneLavadora descendente"
      }
    },
    "lengthMenu": [ 5, 10, 20, 50, 100 ],
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-10 col-md-10 col-xs-6 offset-lg-1 offset-md-1 offset-xs-3">
      <div class="col-12">
        <h3>Catálogo de Tipos de Pantalón</h3>
      </div>
      <div class='table-responsive'>
        <table id="tabla" class="table" style="background: rgba(255, 255, 255, 0.9);">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Editar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $key => $value): ?>
              <tr>
                <td id="nombre<?php echo $value['id'] ?>"><?php echo $value['nombre']; ?></td>
                <td><a href="#" onclick="editar(<?php echo $value['id']; ?>)"><i class="far fa-edit"></i>Editar</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mx-auto">
        <button type="button" name="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo">
          <i class="fas fa-plus"></i> Nuevo
        </button>
      </div>
    </div>
  </div>
</diV>
<div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo tipo de pantalón</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="nuevoTipo" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="nombre" class="col-3 col-form-label">Nombre</label>
            <div class="col-9">
              <input type="text" name="nombre" id="nombre" placeholder="Nombre del tipo de pantalón" required class="form-control">
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
        <h5 class="modal-title" id="exampleModalLabel">Editar tipo de pantalón</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="editarTipo" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" id="id">
          <div class="form-group row">
            <label for="nombreE" class="col-3 col-form-label">Nombre</label>
            <div class="col-9">
              <input type="text" name="nombreE" id="nombreE" placeholder="Nombre del tipo de pantalón" required class="form-control">
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
