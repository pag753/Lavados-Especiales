<?php
defined('BASEPATH') or exit('No direct script access allowed');
foreach ($produccion as $key => $value)
{
  // Arreglo de proceso seco
  if (! isset($p[$value['idlavado']][$value['idproceso']]))
  {
    $p[$value['idlavado']][$value['idproceso']] = array(
      'lavado' => $value['lavado'],
      'proceso' => $value['proceso'],
      'piezas' => $value['piezas'],
      'costo' => $value['costo'],
      'total' => $value['total'],
      'carga' => $value['carga']
    );
  }
  else
  {
    $p[$value['idlavado']][$value['idproceso']]['piezas'] += $value['piezas'];
    $p[$value['idlavado']][$value['idproceso']]['total'] += $value['total'];
  }
}
// Arreglo de reprocesos
foreach ($reproceso as $key => $value)
{
  if (! isset($r[$value['lavado_id']][$value['proceso_seco_id']]))
  {
    $r[$value['lavado_id']][$value['proceso_seco_id']] = array(
      'lavado' => $value['lavado_nombre'],
      'proceso' => $value['proceso_seco'],
      'piezas' => $value['piezas'],
      'costo' => $value['costo'],
      'total' => $value['total'],
      'carga' => $value['carga']
    );
  }
  else
  {
    $r[$value['lavado_id']][$value['proceso_seco_id']]['piezas'] += $value['piezas'];
    $r[$value['lavado_id']][$value['proceso_seco_id']]['total'] += $value['total'];
  }
}
// Acumuladores
$totalProduccion = 0;
$totalReprocesos = 0;
?>
<script type="text/javascript">
var tablas = [];
<?php if (count($produccion) != 0): ?>
var produccion = <?php echo json_encode($produccion); ?>;
tablas.push("#tablaProdProcSec");
<?php else: ?>
var produccion = "";
<?php endif; ?>
<?php if (count($reproceso) != 0): ?>
var reproceso = <?php echo json_encode($reproceso); ?>;
tablas.push("#tablaReprocesos");
<?php else: ?>
var reproceso = "";
<?php endif; ?>
function modal(indice,lavado,proceso) {
  var t = ""
  /*nombre  piezas  costo  total */
  $('#tablaModal tbody').html('');
  if(indice == 1) {
    $.each(produccion, function( index, value ) {
      if (lavado == value.idlavado && proceso == value.idproceso) {
        t = "Información del proceso "+value.proceso+" del lavado "+value.lavado;
        $('#tablaModal tbody').append('<tr><td>'+value.nombre_completo+'</td><td>'+value.piezas+'</td><td>$'+value.costo+'</td><td>$'+value.total+'</td></tr>')
      }
    });
  }
  else {
    $.each(reproceso, function( index, value ) {
      if (lavado == value.lavado_id && proceso == value.proceso_seco_id) {
        t = "Información del reproceso "+value.proceso_seco+" del lavado "+value.lavado_nombre;
        $('#tablaModal tbody').append('<tr><td>'+value.usuario_nombre+'</td><td>'+value.piezas+'</td><td>$'+value.costo+'</td><td>$'+value.total+'</td></tr>')
      }
    });
  }
  $('#tituloModal').html(t);
  $('#modal').modal('show');
}
function modalInfo()
{
  $('#modalInfo').modal('show');
}
$(document).ready(function() {
  $.each(tablas, function( index, value ) {
    $( value ).DataTable({
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
      "lengthMenu": [ 50, 100, 200, 500, 1000 ],
    });
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="col-12">
        <div class="mx-auto">
          <h3>
            Reporte de costos del corte con <a href="#" onclick="modalInfo()">folio <?php echo $this->input->get()['folio']; ?></a>
          </h3>
        </div>
      </div>
      <form action="reporteCostos" method="post" id="reporteCostos" name="reporteCostos" target="_blank">
        <input type="hidden" name="id" value="<?php echo $this->input->get()['folio'] ?>">
        <?php if (isset($p)): ?>
          <div class="card">
            <div class="card-header">
              <a data-toggle="collapse" href="#prodProcSeco" role="button" aria-expanded="true" aria-controls="prodProcSeco"> <strong>Tabla de producción de proceso seco.</strong>
              </a>
            </div>
            <div class="collapse" id="prodProcSeco">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="tablaProdProcSec">
                    <thead>
                      <tr>
                        <th>Carga - lavado</th>
                        <th>Proceso</th>
                        <th>Piezas trabajadas</th>
                        <th>Costo unitario</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody><?php foreach ($p as $key => $value): foreach ($value as $key2 => $value2): ?>
                      <tr onclick="modal(1,<?php echo $key; ?>,<?php echo $key2; ?>)" data-toggle="tooltip" data-placement="top" title="De click para saber la información de este proceso">
                        <td>
                          <input type="hidden" name="lavadoProduccion[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['carga'] . " " . $value2['lavado'] ?>">
                          <?php echo $value2['carga'] . " " . $value2['lavado'] ?>
                        </td>
                        <td>
                          <input type="hidden" name="procesoProduccion[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['proceso'] ?>">
                          <?php echo $value2['proceso'] ?>
                        </td>
                        <td>
                          <input type="hidden" name="piezasProduccion[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['piezas'] ?>">
                          <?php echo $value2['piezas'] ?>
                        </td>
                        <td>
                          <input type="hidden" name="costoProduccion[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['costo'] ?>">
                          $<?php echo $value2['costo'] ?>
                        </td>
                        <td>
                          <input type="hidden" name="totalProduccion[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['total'] ?>">
                          $<?php echo $value2['total']; $totalProduccion += $value2['total']; ?>
                        </td>
                      </tr><?php endforeach; endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php if (isset($r)): ?>
          <div class="card">
            <div class="card-header">
              <a data-toggle="collapse" href="#reproocesos" role="button" aria-expanded="true" aria-controls="reproocesos"> <strong>Tabla de producción de reprocesos.</strong>
              </a>
            </div>
            <div class="collapse" id="reproocesos">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="tablaReprocesos">
                    <thead>
                      <tr>
                        <th>Carga o lavado</th>
                        <th>Proceso</th>
                        <th>Costo unitario</th>
                        <th>Piezas trabajadas</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($r as $key => $value):
                        foreach ($value as $key2 => $value2): ?>
                        <tr onclick="modal(2,<?php echo $key; ?>,<?php echo $key2; ?>)" data-toggle="tooltip" data-placement="top" title="De click para saber la información de este reproceso">
                          <td><input type="hidden" name="lavadoProduccionReprocesos[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['lavado'] ?>">
                            <?php echo $value2['lavado'] ?>
                          </td>
                          <td><input type="hidden" name="procesoProduccionReprocesos[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['proceso'] ?>">
                            <?php echo $value2['proceso'] ?>
                          </td>
                          <td><input type="hidden" name="piezasProduccionReprocesos[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['piezas'] ?>">
                            <?php echo $value2['piezas'] ?>
                          </td>
                          <td><input type="hidden" name="costoProduccionReprocesos[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['costo'] ?>">
                            $<?php echo $value2['costo'] ?>
                          </td>
                          <td><input type="hidden" name="totalProduccionReprocesos[<?php echo $key;?>][<?php echo $key2;?>]" value="<?php echo $value2['total'] ?>">
                            $<?php echo $value2['total']; $totalReprocesos += $value2['total']; ?>
                          </td>
                        </tr>
                      <?php endforeach;
                    endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="card">
        <div class="card-header">
          <a data-toggle="collapse" href="#nom" role="button" aria-expanded="true" aria-controls="nom"> <strong>Totales</strong>
          </a>
        </div>
        <div class="collapse" id="nom">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="tabla">
                <tbody>
                  <tr>
                    <th>Total de produccion de proceso seco</th>
                    <td><input type="hidden" name="totalProduccion-" value="<?php echo $totalProduccion ?>">
                      $<?php echo $totalProduccion ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Total de producción de reprocesos</th>
                    <td><input type="hidden" name="totalReprocesos-" value="<?php echo $totalReprocesos ?>">
                      $<?php echo $totalReprocesos ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td><input type="hidden" name="total-" value="<?php echo $totalProduccion+$totalReprocesos ?>">
                      $<?php echo $totalProduccion+$totalReprocesos ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto">
        <button type="submit" class="btn btn-primary btn-lg" data-toggle="tooltip" data-placement="top" title="Adquirir versión impresa">
          <i class="fa fa-print"></i>
        </button>
      </div>
    </form>
  </div>
</div>
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="tablaModal" class="table table-hover">
            <thead>
              <tr>
                <th>Nombre del operario</th>
                <th>Piezas trabajadas</th>
                <th>Costo unitario</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Información del corte con folio <?php echo $this->input->get()['folio'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="tablaModal" class="table table-hover">
            <tbody>
              <tr>
                <th>Imágen</th>
                <td><?php echo $corte['imagen'] ?></td>
              </tr>
              <tr>
                <th>Fecha de entrada</th>
                <td><?php echo $corte['fecha'] ?></td>
              </tr>
              <tr>
                <th>Corte</th>
                <td><?php echo $corte['corte'] ?></td>
              </tr>
              <tr>
                <th>Marca</th>
                <td><?php echo $corte['marca'] ?></td>
              </tr>
              <tr>
                <th>Maquilero</th>
                <td><?php echo $corte['maquilero'] ?></td>
              </tr>
              <tr>
                <th>Cliente</th>
                <td><?php echo $corte['cliente'] ?></td>
              </tr>
              <tr>
                <th>Tipo de pantalón o prenda</th>
                <td><?php echo $corte['tipo'] ?></td>
              </tr>
              <tr>
                <th>Número de piezas</th>
                <td><?php echo $corte['piezas'] ?></td>
              </tr>
              <tr>
                <th>Número de ojales</th>
                <td><?php echo $corte['ojales'] ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
