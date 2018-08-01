<?php
defined('BASEPATH') or exit('No direct script access allowed');
$totalPiezasTrabajadas = 0;
$totalDefectos = 0;
$piezasRegistradas = $reproceso['piezas_trabajadas'];
foreach ($reprocesos as $key => $value)
{
  $totalPiezasTrabajadas += $value['piezas'];
  $totalDefectos += $value['defectos'];
}
?>
<script>
$(document).ready(function() {
  <?php if (($piezasRegistradas - ($totalPiezasTrabajadas + $totalDefectos)) == 0): ?>
  $("#cerrarReproceso").submit(function( event ) {
    return confirm('¿Está seguro de querer cerrar el reproceso?');
  });
  <?php endif; ?>
  $('#especificos').DataTable({
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
    <div class="col-lg-10 col-md-12 offset-lg-1">
      <div class="card">
        <div class="card-header">
          <strong>Datos Generales</strong>
        </div>
        <div class="card-body">
          <div class='table-responsive'>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Folio</th>
                  <td><?php echo $folio ?></td>
                </tr>
                <tr>
                  <th>Marca</th>
                  <td><?php echo $marca ?></td>
                </tr>
                <tr>
                  <th>Cliente</th>
                  <td><?php echo $cliente ?></td>
                </tr>
                <tr>
                  <th>Color de hilo</th>
                  <td><?php echo $color_hilo ?></td>
                </tr>
                <tr>
                  <th>Tipo</th>
                  <td><?php echo $tipo ?></td>
                </tr>
                <tr>
                  <th># Carga</th>
                  <td><?php echo $carga ?></td>
                </tr>
                <tr>
                  <th>Lavado</th>
                  <td><?php echo $lavado ?></td>
                </tr>
                <tr>
                  <th>Proceso</th>
                  <td><?php echo $proceso ?></td>
                </tr>
                <tr>
                  <th>Piezas registradas</th>
                  <td><?php echo $piezasRegistradas ?></td>
                </tr>
                <tr>
                  <th>Piezas trabajadas</th>
                  <td><?php echo $totalPiezasTrabajadas ?></td>
                </tr>
                <tr>
                  <th>Defectos</th>
                  <td><?php echo $totalDefectos ?></td>
                </tr>
                <tr>
                  <th>Piezas faltantes</th>
                  <td><?php echo $piezasRegistradas - ($totalPiezasTrabajadas + $totalDefectos) ?></td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <a data-toggle="collapse" href="#ver" role="button" aria-expanded="false" aria-controls="ver"><strong>Datos Específicos</strong> </a>
        </div>
        <div class="collapse" id="ver">
          <div class="card-body">
            <?php if (count($reprocesos) > 0): ?>
              <div class='table-responsive'>
                <table class="table table-bordered" id="especificos">
                  <thead>
                    <tr class="danger">
                      <th>Empleado</th>
                      <th>Piezas que registró</th>
                      <th>Defectos que registró</th>
                      <th>Fecha en que registró</th>
                    </tr>
                  </thead>
                  <tbody><?php foreach ($reprocesos as $key => $value): ?>
                    <tr>
                      <td><?php echo $value['nombre'] ?></td>
                      <td><?php echo $value['piezas'] ?></td>
                      <td><?php echo $value['defectos'] ?></td>
                      <td><?php echo $value['fecha'] ?></td>
                    </tr><?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos de reproceso registrados.</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php if (($piezasRegistradas - ($totalPiezasTrabajadas + $totalDefectos)) == 0): ?>
        <div class="card">
          <div class="card-body">
            <form action="cerrarReproceso" name="cerrarReproceso" id="cerrarReproceso" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $reproceso['id'] ?>"> <input type="hidden" name="piezas_trabajadas" id="piezas_trabajadas" value="<?php echo $totalPiezasTrabajadas; ?>"> <input type="hidden" name="defectos" id="defectos" value="<?php echo $totalDefectos ?>">
              <div class="mx-auto">
                <input type="submit" name="aceptar" id="aceptar" value="Cerrar el reproceso" class="btn btn-primary" />
              </div>
            </form>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-danger" role="alert">La suma de las piezas de producción y defecectos no son iguales a las piezas registradas. Favor de revisar con los operarios.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
