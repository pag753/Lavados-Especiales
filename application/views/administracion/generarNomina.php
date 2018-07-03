<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (count($nomina) == 0) $id = 1;
else $id = $nomina[0]['id'] + 1;
$prod = null;
$desc = null;
$ahorr = null;
$saldant = null;
$total = 0;
// obtener las producciones(nomina)
foreach ($produccion as $key => $value)
{
  if (! isset($prod[$value['id']])) $prod[$value['id']] = $value['costo'];
  else $prod[$value['id']] += $value['costo'];
}
// sumarle a $prod los producción de reprocesos
foreach ($reprocesos as $key => $value)
{
  if (! isset($prod[$value['usuario_id']])) $prod[$value['usuario_id']] = $value['costo'];
  else $prod[$value['usuario_id']] += $value['costo'];
}
// obtener los descuentos
foreach ($descuentos as $key => $value)
{
  if (! isset($desc[$value['usuario_id']])) $desc[$value['usuario_id']] = $value['cantidad'];
  else $desc[$value['usuario_id']] += $value['cantidad'];
}
// obtener los ahorros
foreach ($ahorros as $key => $value)
{
  if ($value['aportacion'] == 1)
  {
    if (! isset($ahorr[$value['usuario_id']])) $ahorr[$value['usuario_id']] = $value['cantidad'];
    else $ahorr[$value['usuario_id']] += $value['cantidad'];
  }
  else
  {
    if (! isset($ahorr[$value['usuario_id']])) $ahorr[$value['usuario_id']] = - $value['cantidad'];
    else $ahorr[$value['usuario_id']] -= $value['cantidad'];
  }
}
// obtener los saldos anteriores
foreach ($nomina as $key => $value) $saldant[$value['usuario_id']] = ($value['total'] - $value['pagado']);
?>
<style media="screen">
th {
  text-align: center;
}
</style>
<script type="text/javascript">
<?php if (count($produccion) != 0): ?>
function cambioProduccionProcesoSeco(idUsuario,idProduccion)
{
  //Cambiar la clase del renglón
  var clase="";
  var cantidad = $('#cantidad_pagar_produccion_proceso_seco_'+idProduccion).val() * 1;
  clase = $('#tr_produccion_proceso_seco_'+idProduccion).attr('class').split(' ')[0];
  aumentaODisminuye(clase,$('#estado_nomina_proceso_seco_'+idProduccion).val() * 1,idUsuario,cantidad);
  switch ($('#estado_nomina_proceso_seco_'+idProduccion).val() * 1)
  {
    case 1:
    $('#razonProduccionProcesoSeco'+idProduccion).prop("readonly",true);
    $('#razonProduccionProcesoSeco'+idProduccion).val("");
    clase = "table-success";
    break;
    case 2:
    $('#razonProduccionProcesoSeco'+idProduccion).prop("readonly",false);
    clase = "table-warning";
    break;
    default:
    $('#razonProduccionProcesoSeco'+idProduccion).prop("readonly",false);
    clase = "table-danger";
    break;
  }
  $('#tr_produccion_proceso_seco_'+idProduccion).attr('class',clase);
}
<?php endif ?>
<?php if (count($pendientes_produccion) != 0): ?>
function cambioPendientesProcesoSeco(idUsuario,idProduccion)
{
  //Cambiar la clase del renglón
  var clase="";
  var cantidad = $('#cantidad_pagar_pendientes_proceso_seco'+idProduccion).val() * 1;
  clase = $('#tr_pendientes_proceso_seco_'+idProduccion).attr('class').split(' ')[0];
  aumentaODisminuye(clase,$('#estado_nomina_pendientes_proceso_seco'+idProduccion).val() * 1,idUsuario,cantidad);
  switch ($('#estado_nomina_pendientes_proceso_seco'+idProduccion).val() * 1)
  {
    case 1:
    clase = "table-success";
    $('#razonPendientesProcesoSeco'+idProduccion).prop( "readonly", true );
    $('#razonPendientesProcesoSeco'+idProduccion).val("");
    break;
    case 2:
    $('#razonPendientesProcesoSeco'+idProduccion).prop( "readonly", false );
    clase = "table-warning";
    break;
    default:
    $('#razonPendientesProcesoSeco'+idProduccion).prop( "readonly", false );
    clase = "table-danger";
    break;
  }
  $('#tr_pendientes_proceso_seco_'+idProduccion).attr('class',clase);
}
<?php endif ?>
<?php if (count($reprocesos) != 0): ?>
function cambioReproceso(idUsuario,idProduccion)
{
  //Cambiar la clase del renglón
  var clase="";
  var cantidad = $('#cantidad_pagar_reproceso'+idProduccion).val() * 1;
  clase = $('#tr_reproceso_'+idProduccion).attr('class').split(' ')[0];
  aumentaODisminuye(clase,$('#estado_nomina_reproceso'+idProduccion).val() * 1,idUsuario,cantidad);
  switch ($('#estado_nomina_reproceso'+idProduccion).val() * 1)
  {
    case 1:
    $('#razonReproceso'+idProduccion).prop( "readonly", true );
    $('#razonReproceso'+idProduccion).val('');
    clase = "table-success";
    break;
    case 2:
    $('#razonReproceso'+idProduccion).prop( "readonly", false );
    clase = "table-warning";
    break;
    default:
    $('#razonReproceso'+idProduccion).prop( "readonly", false );
    clase = "table-danger";
    break;
  }
  $('#tr_reproceso_'+idProduccion).attr('class',clase);
}
<?php endif; if (count($pendientes_reproceso) != 0): ?>
function cambioPendientesReproceso(idUsuario,idProduccion)
{
  //Cambiar la clase del renglón
  var clase="";
  var cantidad = $('#cantidad_pagar_pendientes_reproceso'+idProduccion).val() * 1;
  clase = $('#tr_pendeientes_reproceso_'+idProduccion).attr('class').split(' ')[0];
  aumentaODisminuye(clase,$('#estado_nomina_pendientes_reproceso'+idProduccion).val() * 1,idUsuario,cantidad);
  switch ($('#estado_nomina_pendientes_reproceso'+idProduccion).val() * 1)
  {
    case 1:
    $('#razonPendientesReproceso'+idProduccion).prop( "readonly", true );
    $('#razonPendientesReproceso'+idProduccion).val('');
    clase = "table-success";
    break;
    case 2:
    $('#razonPendientesReproceso'+idProduccion).prop( "readonly", false );
    clase = "table-warning";
    break;
    default:
    $('#razonPendientesReproceso'+idProduccion).prop( "readonly", false );
    clase = "table-danger";
    break;
  }
  $('#tr_pendeientes_reproceso_'+idProduccion).attr('class',clase);
}
<?php endif; ?>
function aumentaODisminuye(clase,id,idUsuario,cantidad)
{
  switch (clase) {
    case 'table-success':
    if (id  == 2 || id == 3)  disminuyeTotalUsuario(idUsuario,cantidad);
    break;
    case 'table-warning': case 'table-danger':
    if (id  == 1) aumentaTotalUsuario(idUsuario,cantidad);
    break;
  }
}
function aumentaTotalUsuario(idUsuario,cantidad)
{
  $('#nomina_'+idUsuario).val(($('#nomina_'+idUsuario).val() * 1) + cantidad);
  $('#label_nomina_'+idUsuario).html($('#nomina_'+idUsuario).val());
  calcula(idUsuario);
}
function disminuyeTotalUsuario(idUsuario,cantidad)
{
  $('#nomina_'+idUsuario).val(($('#nomina_'+idUsuario).val() * 1) - cantidad);
  $('#label_nomina_'+idUsuario).html($('#nomina_'+idUsuario).val());
  calcula(idUsuario);
}
function calcula(id)
{
  $('#ahorro_saldo_'+id).val(
    ($('#ahorro_anterior_'+id).val() * 1) +
    ($('#ahorro_abono_'+id).val()) * 1
  );
  $('#td_ahorro_saldo_'+id).html($('#ahorro_saldo_'+id).val());
  $('#descuentos_saldo_'+id).val(
    ($('#descuentos_anterior_'+id).val() * 1) -
    ($('#descuentos_abono_'+id).val() * 1)
  );
  $('#td_descuentos_saldo_'+id).html($('#descuentos_saldo_'+id).val());
  $('#total_'+id).val(
    ($('#nomina_'+id).val() * 1) +
    ($('#saldo_anterior_'+id).val() * 1) -
    ($('#descuentos_abono_'+id).val() * 1) -
    ($('#ahorro_abono_'+id).val() * 1) +
    ($('#bonos_'+id).val() * 1)
  );
  $('#td_total_'+id).html($('#total_'+id).val());
  var totales = $("[id^='total_']");
  var total = 0;
  $.each(totales, function( index, value ) {
    total += (value.value * 1);
  });
  $('#totalTotal').html(total);
  var pagados = $("[id^='pagado_']");
  totalPagar = 0;
  $.each(pagados, function( index, value ) {
    totalPagar += (value.value * 1);
  });
  $('#diferencia').val(total - totalPagar);
}
function pagado()
{
  var pagados = $("[id^='pagado_']");
  totalPagar = 0;
  $.each(pagados, function( index, value ) {
    totalPagar += (value.value * 1);
  });
  $('#totalPagar').html(totalPagar);
  $('#diferencia').html(($('#totalTotal').html() * 1) - totalPagar);
}
$(document).ready(function() {
  $('#verNomina').submit(function() {
    return confirm('¿Estás seguro de generar la nómina?');
  });
  var tablas = ['#tablaProdProcSec','#tablaPenProcSec','#tablaRepr','#tablaPenReproc','#tabla'];
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
      "lengthMenu": [ 200, 500, 1000, 2000, 3000 ],
    });
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="col-12">
        <div class="mx-auto">
          <h3>Generar <?php echo $descripcion; ?></h3>
        </div>
      </div>
      <form action="verNomina" method="post" id="verNomina" name="verNomina" target="_blank">
        <input type="hidden" name="idNomina" id="idNomina" value="<?php echo $id ?>"> <input type="hidden" name="descripcion" id="descripcion" value="<?php echo $descripcion ?>">
        <?php if (count($produccion) != 0): ?>
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
                        <th>Nombre del operario</th>
                        <th>Folio</th>
                        <th>Carga o lavado</th>
                        <th>Proceso</th>
                        <th>Piezas Trabajadas</th>
                        <th>Costo unitario</th>
                        <th>Total</th>
                        <th>¿Se pagará?</th>
                        <th>Razón por la que no se pagará</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($produccion as $key => $value): ?>
                        <tr id="tr_produccion_proceso_seco_<?php echo $value['id_produccion'];?>" class="table-success">
                          <td><?php echo $value['usuario_nombre'] ?></td>
                          <td><?php echo $value['folio'] ?></td>
                          <td><?php echo $value['lavado'] ?></td>
                          <td><?php echo $value['proceso'] ?></td>
                          <td><?php echo $value['piezas'] ?></td>
                          <td>$<?php echo $value['precio'] ?></td>
                          <td><input type="hidden" id="cantidad_pagar_produccion_proceso_seco_<?php echo $value['id_produccion'] ?>" name="cantidad_pagar_produccion_proceso_seco_[<?php echo $value['id_produccion'] ?>]" value="<?php echo $value['costo'] ?>">$<?php echo $value['costo'] ?>
                          </td>
                          <td><select class="form-control" onchange="cambioProduccionProcesoSeco(<?php echo $value['id'] ?>,<?php echo $value['id_produccion'] ?>)" class="form-control" name="estado_nomina_proceso_seco[<?php echo $value['id_produccion'] ?>]" id="estado_nomina_proceso_seco_<?php echo $value['id_produccion'] ?>">
                            <option value="1" selected>Se pagará</option>
                            <option value="2">Quedará pendiente</option>
                            <option value="3">No se pagará jamás</option>
                          </select></td>
                          <td><textarea readonly id="razonProduccionProcesoSeco<?php echo $value['id_produccion'] ?>" name="razonProduccionProcesoSeco[<?php echo $value['id_produccion'] ?>]" class="form-control"><?php echo $value['razon'] ?></textarea></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        <?php endif; if (count($pendientes_produccion) != 0): ?>
          <div class="card">
            <div class="card-header">
              <a data-toggle="collapse" href="#penProcSeco" role="button" aria-expanded="true" aria-controls="penProcSeco"> <strong>Tabla de pendientes en producción de proceso seco.</strong>
              </a>
            </div>
            <div class="collapse" id="penProcSeco">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="tablaPenProcSec">
                    <thead>
                      <tr>
                        <th>Nombre del operario</th>
                        <th>Folio</th>
                        <th>Carga o lavado</th>
                        <th>Proceso</th>
                        <th>Piezas Trabajadas</th>
                        <th>Costo unitario</th>
                        <th>Total</th>
                        <th>¿Se pagará?</th>
                        <th>Razón por la que no se pagará</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($pendientes_produccion as $key => $value): ?>
                        <tr id="tr_pendientes_proceso_seco_<?php echo $value['id_produccion'];?>" class="table-warning">
                          <td><?php echo $value['usuario_nombre'] ?></td>
                          <td><?php echo $value['folio'] ?></td>
                          <td><?php echo $value['lavado'] ?></td>
                          <td><?php echo $value['proceso'] ?></td>
                          <td><?php echo $value['piezas'] ?></td>
                          <td>$<?php echo $value['precio'] ?></td>
                          <td><input type="hidden" id="cantidad_pagar_pendientes_proceso_seco<?php echo $value['id_produccion'] ?>" name="cantidad_pagar_pendientes_proceso_seco[<?php echo $value['id_produccion'] ?>]" value="<?php echo $value['costo'] ?>">$<?php echo $value['costo'] ?>
                          </td>
                          <td><select class="form-control" onchange="cambioPendientesProcesoSeco(<?php echo $value['id'] ?>,<?php echo $value['id_produccion'] ?>)" class="form-control" name="estado_nomina_pendientes_proceso_seco[<?php echo $value['id_produccion'] ?>]" id="estado_nomina_pendientes_proceso_seco<?php echo $value['id_produccion'] ?>">
                            <option value="1">Se pagará</option>
                            <option value="2" selected>Quedará pendiente</option>
                            <option value="3">No se pagará jamás</option>
                          </select></td>
                          <td><textarea id="razonPendientesProcesoSeco<?php echo $value['id_produccion'] ?>" name="razonPendientesProcesoSeco[<?php echo $value['id_produccion'] ?>]" class="form-control"><?php echo $value['razon'] ?></textarea></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><?php endif; if (count($reprocesos) != 0): ?>
            <div class="card">
              <div class="card-header">
                <a data-toggle="collapse" href="#repr" role="button" aria-expanded="true" aria-controls="repr"><strong>Tabla de producción de reprocesos.</strong> </a>
              </div>
              <div class="collapse" id="repr">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover" id="tablaRepr">
                      <thead>
                        <tr>
                          <th>Nombre del operario</th>
                          <th>Folio</th>
                          <th>Carga o lavado</th>
                          <th>Proceso</th>
                          <th>Piezas Trabajadas</th>
                          <th>Costo unitario</th>
                          <th>Total</th>
                          <th>¿Se pagará?</th>
                          <th>Razón por la que no se pagará</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($reprocesos as $key => $value): ?>
                          <tr id="tr_reproceso_<?php echo $value['id_produccion_reproceso'];?>" class="table-success">
                            <td><?php echo $value['usuario_nombre'] ?></td>
                            <td><?php echo $value['folio'] ?></td>
                            <td><?php echo $value['lavado'] ?></td>
                            <td><?php echo $value['proceso'] ?></td>
                            <td><?php echo $value['piezas'] ?></td>
                            <td>$<?php echo $value['precio'] ?></td>
                            <td><input type="hidden" id="cantidad_pagar_reproceso<?php echo $value['id_produccion_reproceso'] ?>" name="cantidad_pagar_reproceso[<?php echo $value['id_produccion_reproceso'] ?>]" value="<?php echo $value['costo'] ?>">
                              $<?php echo $value['costo'] ?>
                            </td>
                            <td><select class="form-control" onchange="cambioReproceso(<?php echo $value['usuario_id'] ?>,<?php echo $value['id_produccion_reproceso'] ?>)" class="form-control" name="estado_nomina_reproceso[<?php echo $value['id_produccion_reproceso'] ?>]" id="estado_nomina_reproceso<?php echo $value['id_produccion_reproceso'] ?>">
                              <option value="1" selected>Se pagará</option>
                              <option value="2">Quedará pendiente</option>
                              <option value="3">No se pagará jamás</option>
                            </select></td>
                            <td><textarea readonly id="razonReproceso<?php echo $value['id_produccion_reproceso'] ?>" name="razonReproceso[<?php echo $value['id_produccion_reproceso'] ?>]" class="form-control"><?php echo $value['razon'] ?></textarea></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; if (count($pendientes_reproceso) != 0): ?>
            <div class="card">
              <div class="card-header">
                <a data-toggle="collapse" href="#penRepr" role="button" aria-expanded="true" aria-controls="penRepr"> <strong>Tabla de producción de pendientes de reprocesos.</strong>
                </a>
              </div>
              <div class="collapse" id="penRepr">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover" id="tablaPenReproc">
                      <thead>
                        <tr>
                          <th>Nombre del operario</th>
                          <th>Folio</th>
                          <th>Carga o lavado</th>
                          <th>Proceso</th>
                          <th>Piezas Trabajadas</th>
                          <th>Costo unitario</th>
                          <th>Total</th>
                          <th>¿Se pagará?</th>
                          <th>Razón por la que no se pagará</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($pendientes_reproceso as $key => $value): ?>
                          <tr id="tr_pendeientes_reproceso_<?php echo $value['id_produccion_reproceso'];?>" class="table-warning">
                            <td><?php echo $value['usuario_nombre'] ?></td>
                            <td><?php echo $value['folio'] ?></td>
                            <td><?php echo $value['lavado'] ?></td>
                            <td><?php echo $value['proceso'] ?></td>
                            <td><?php echo $value['piezas'] ?></td>
                            <td>$<?php echo $value['precio'] ?></td>
                            <td><input type="hidden" id="cantidad_pagar_pendientes_reproceso<?php echo $value['id_produccion_reproceso'] ?>" name="cantidad_pagar_pendientes_reproceso[<?php echo $value['id_produccion_reproceso'] ?>]" value="<?php echo $value['costo'] ?>">
                              $<?php echo $value['costo'] ?>
                            </td>
                            <td><select class="form-control" onchange="cambioPendientesReproceso(<?php echo $value['usuario_id'] ?>,<?php echo $value['id_produccion_reproceso'] ?>)" class="form-control" name="estado_nomina_pendientes_reproceso[<?php echo $value['id_produccion_reproceso'] ?>]" id="estado_nomina_pendientes_reproceso<?php echo $value['id_produccion_reproceso'] ?>">
                              <option value="1">Se pagará</option>
                              <option value="2" selected>Quedará pendiente</option>
                              <option value="3">No se pagará jamás</option>
                            </select></td>
                            <td><textarea id="razonPendientesReproceso<?php echo $value['id_produccion_reproceso'] ?>" name="razonPendientesReproceso[<?php echo $value['id_produccion_reproceso'] ?>]" class="form-control"><?php echo $value['razon'] ?></textarea></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <div class="card">
            <div class="card-header">
              <a data-toggle="collapse" href="#nom" role="button" aria-expanded="true" aria-controls="nom"> <strong>Resúmen final.</strong>
              </a>
            </div>
            <div class="collapse" id="nom">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="tabla">
                    <thead>
                      <tr>
                        <th rowspan="2">Nombre</th>
                        <th rowspan="2">Puesto</th>
                        <th rowspan="2">Saldo anterior</th>
                        <th rowspan="2">Nómina</th>
                        <th colspan="3" class="table-primary">Ahorros</th>
                        <th rowspan="2" class="table-success">Bonos</th>
                        <th colspan="3" class="table-danger">Descuentos</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">Pagado</th>
                      </tr>
                      <tr>
                        <th class="table-primary">Anterior</th>
                        <th class="table-primary">Abono</th>
                        <th class="table-primary">Saldo</th>
                        <th class="table-danger">Anterior</th>
                        <th class="table-danger">Abono</th>
                        <th class="table-danger">Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($operarios as $key => $value): ?>
                        <tr>
                          <td><input type="hidden" name="nombre[<?php echo $value['id'] ?>]" id="nombre[<?php echo $value['id'] ?>]" value="<?php echo $value['nombre_completo']; ?>"> <label><?php echo $value['nombre_completo']; ?></label></td>
                          <td><input type="hidden" name="puesto[<?php echo $value['id'] ?>]" id="puesto[<?php echo $value['id'] ?>]" value="<?php echo $value['puesto']; ?>"> <label><?php echo $value['puesto']; ?></label></td>
                          <td><input type="hidden" id="saldo_anterior_<?php echo $value['id'] ?>" name="saldo_anterior[<?php echo $value['id'] ?>]" value="<?php $s  = (isset($saldant[$value['id']])) ? $saldant[$value['id']] : 0 ; echo $s; ?>"> <label><i class="fas fa-dollar-sign"></i><?php echo $s; ?></label></td>
                          <td><input type="hidden" id="nomina_<?php echo $value['id'] ?>" name="nomina[<?php echo $value['id'] ?>]" value="<?php $p  = (isset($prod[$value['id']])) ? $prod[$value['id']] : 0 ; echo $p; ?>"> <i class="fas fa-dollar-sign"></i><label id="label_nomina_<?php echo $value['id'];?>"><?php echo $p; ?></label></td>
                          <td class="table-primary"><input type="hidden" id="ahorro_anterior_<?php echo $value['id'] ?>" name="ahorro_anterior[<?php echo $value['id'] ?>]" value="<?php $a  = (isset($ahorr[$value['id']])) ? $ahorr[$value['id']] : 0 ; echo $a; ?>"> <i class="fas fa-dollar-sign"></i><label><?php echo $a; ?></label></td>
                          <td class="table-primary"><div class="input-group input-group-sm mb-3">
                            <input type="number" required class="form-control" step="any" id="ahorro_abono_<?php echo $value['id'] ?>" name="ahorro_abono[<?php echo $value['id'] ?>]" value="0" oninput="calcula(<?php echo $value['id'] ?>);">
                          </div></td>
                          <td class="table-primary"><input type="hidden" id="ahorro_saldo_<?php echo $value['id'] ?>" name="ahorro_saldo[<?php echo $value['id'] ?>]" value="<?php echo $a ?>"> <i class="fas fa-dollar-sign"></i><label id="td_ahorro_saldo_<?php echo $value['id'];?>"><?php echo $a ?></label></td>
                          <td class="table-success"><div class="input-group input-group-sm mb-3">
                            <input type="number" required class="form-control" step="any" id="bonos_<?php echo $value['id'] ?>" name="bonos[<?php echo $value['id'] ?>]" value="0" oninput="calcula(<?php echo $value['id'] ?>);">
                          </div></td>
                          <td class="table-danger"><input type="hidden" id="descuentos_anterior_<?php echo $value['id'] ?>" name="descuentos_anterior[<?php echo $value['id'] ?>]" value="<?php $d  = (isset($desc[$value['id']])) ? $desc[$value['id']] : 0 ; echo $d; ?>"> <i class="fas fa-dollar-sign"></i><label><?php echo $d; ?></label></td>
                          <td class="table-danger"><div class="input-group input-group-sm mb-3">
                            <input type="number" required class="form-control" step="any" id="descuentos_abono_<?php echo $value['id'] ?>" name="descuentos_abono[<?php echo $value['id'] ?>]" value="0" oninput="calcula(<?php echo $value['id'] ?>);">
                          </div></td>
                          <td class="table-danger"><input type="hidden" id="descuentos_saldo_<?php echo $value['id'] ?>" name="descuentos_saldo[<?php echo $value['id'] ?>]" value="<?php echo $d ?>"> <i class="fas fa-dollar-sign"></i><label id="td_descuentos_saldo_<?php echo $value['id'];?>"><?php echo $d ?></label></td>
                          <td><input type="hidden" id="total_<?php echo $value['id'] ?>" name="total[<?php echo $value['id'] ?>]" value="<?php $total += $s+$p; echo $s+$p; ?>"> <i class="fas fa-dollar-sign"></i><label id="td_total_<?php echo $value['id'];?>"><?php echo $s+$p; ?></label></td>
                          <td><div class="input-group input-group-sm mb-3">
                            <input type="number" required class="form-control" step="any" id="pagado_<?php echo $value['id'] ?>" name="pagado[<?php echo $value['id'] ?>]" value="0" oninput="pagado();">
                          </div></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="mx-auto">
            <input type="submit" class="btn btn-primary" value="Generar nómina">
          </div>
        </form>
        <div class="card">
          <div class="card-header">
            <a data-toggle="collapse" href="#tot" role="button" aria-expanded="false" aria-controls="tot"> </a>
            <h5>Totales.</h5>
          </div>
          <div class="collapse" id="tot">
            <div class="card-body">
              <table class="table table-hover">
                <tr>
                  <th>Total</th>
                  <td><i class="fas fa-dollar-sign"></i> <label id="totalTotal"><?php echo $total ?></label></td>
                </tr>
                <tr>
                  <th>Total a pagar</th>
                  <td><i class="fas fa-dollar-sign"></i> <label id="totalPagar">0</label></td>
                </tr>
                <tr>
                  <th>Diferencia</th>
                  <td><i class="fas fa-dollar-sign"></i> <label id="diferencia">0</label></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
