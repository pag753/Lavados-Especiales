<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aportaciones = 0;
$retiros = 0;
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
        <h3>Caja de ahorro</h3>
      </div>
      <div class='table-responsive'>
        <table id="tabla" class="table"
          style="background: rgba(255, 255, 255, 0.9);">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Fecha</th>
              <th>Cantidad $</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ahorros as $key => $value): ?>
              <tr>
              <td id="aportacion<?php echo $value['id']; ?>">
                  <?php echo ($value['aportacion']!=0) ? "Aportación" : "Retiro" ; ($value['aportacion']!=0) ? $aportaciones += $value['cantidad'] : $retiros += $value['cantidad'] ;?>
                </td>
              <td id="fecha<?php echo $value['id'] ?>"><?php echo $value['fecha']; ?></td>
              <td id="cantidad<?php echo $value['id'] ?>"><?php echo $value['cantidad']; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center">
        <div class="p-2">
          <button type="button" name="button" class="btn btn-info"
            data-toggle="modal" data-target="#total">
            <i class="fas fa-eye"></i> Ver total
          </button>
        </div>
      </div>
    </div>
  </div>
</diV>
<div class="modal fade" id="total" tabindex="-1" role="dialog"
  aria-labelledby="total" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ver total de
          ahorros</h5>
        <button type="button" class="close" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table"
          style="background: rgba(255, 255, 255, 0.9);">
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
