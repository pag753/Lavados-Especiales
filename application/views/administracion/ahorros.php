<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
$(document).ready(function(){
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
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="col-12">
        <h3>Ahorros</h3>
      </div>
      <div class='table-responsive'>
        <table id="tabla" class="table" style="background: rgba(255, 255, 255, 0.9);">
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Nombre Completo</th>
              <th>Ver ahorro</th>
            </tr>
          </thead>
          <tbody><?php foreach ($data as $key => $value): ?>
            <tr>
              <td><?php echo $value['nombre']; ?></td>
              <td><?php echo $value['nombre_completo']; ?></td>
              <td><a href="ahorros?id=<?php echo $value['id']; ?>">
                  <button type="button" class="btn btn-light">
                    <i class="fas fa-eye"></i>
                  </button>
              </a></td>
            </tr><?php endforeach; ?>
		</tbody>
        </table>
      </div>
    </div>
  </div>
</diV>
