<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
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
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="col-12">
        <h3>Nómina</h3>
      </div>
      <div class='table-responsive'>
        <table id="tabla" class="table" style="background: rgba(255, 255, 255, 0.9);">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Descripcion</th>
              <th>Ver</th>
              <th>Ver con detalles</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $key => $value): ?>
              <tr>
                <td><?php echo $value['fecha']; ?></td>
                <td><?php echo $value['descripcion']; ?></td>
                <td><a href="verNomina?id=<?php echo $value['id'] ?>" target="_blank"><button type="button" class="btn btn-primary">
                  <i class="fas fa-eye"></i>
                </button></a></td>
                <td>
                  <a href="verNominaDetalles?id=<?php echo $value['id'] ?>" target="_blank"><button type="button" class="btn btn-info">
                    <i class="fas fa-info"></i>
                  </button></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="mx-auto">
          <a href="nuevaNomina"><button type="button" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo
          </button></a>
        </div>
      </div>
    </div>
  </div>
</diV>
