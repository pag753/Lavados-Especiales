<?php
defined('BASEPATH') OR exit('No direct script access allowed');
foreach ($nominasProduccion as $key => $value)
{
  if (!isset($nominas[$value['id_nomina']]))
  {
    $nominas[$value['id_nomina']] = array(
      'fecha' => $value['fecha'],
    );
  }
}
foreach ($nominasProduccionReproceso as $key => $value)
{
  if (!isset($nominas[$value['id_nomina']]))
  {
    $nominas[$value['id_nomina']] = array(
      'fecha' => $value['fecha'],
    );
  }
}
if (isset($nominas)): ?>
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
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
      <div class="col-12">
        <h3>Ver nóminas</h3>
      </div>
      <?php if (isset($nominas)): ?>
        <div class='table-responsive'>
          <table name="tabla" id="tabla" class="table" style="background:rgba(255,255,255,0.9);">
            <thead>
              <tr>
                <th>Descripción</th>
                <th>Ver</th>
              </tr>
            </thead>
            <tbody><?php foreach ($nominas as $key => $value): ?>
              <tr>
                <td>Nómina generada el <?php echo $value['fecha'] ?></td>
                <td><a href="verNominas?id=<?php echo $key ?>&fecha=<?php echo $value['fecha'] ?>"><button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button></a></td>
              </tr><?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-danger" role="alert">
          No existen registro de nómina
        </div>
      <?php endif; ?>
    </div>
  </div>
</diV>
