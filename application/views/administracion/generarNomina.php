<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (count($nomina) == 0)  $id = 1;
else $id = $nomina[0]['id'] + 1;
$prod = null;
$desc = null;
$ahorr = null;
$saldant = null;
$total = 0;
//obtener las producciones(nomina)
foreach ($produccion as $key => $value)
{
  if (!isset($prod[$value['id']])) $prod[$value['id']] = $value['costo'];
  else $prod[$value['id']] += $value['costo'];
}
//obtener los descuentos
foreach ($descuentos as $key => $value)
{
  if (!isset($desc[$value['usuario_id']])) $desc[$value['usuario_id']] = $value['cantidad'];
  else $desc[$value['usuario_id']] += $value['cantidad'];
}
//obtener los ahorros
foreach ($ahorros as $key => $value)
{
  if ($value['aportacion'] == 1)
  {
    if (!isset($ahorr[$value['usuario_id']])) $ahorr[$value['usuario_id']] = $value['cantidad'];
    else $ahorr[$value['usuario_id']] += $value['cantidad'];
  }
  else
  {
    if (!isset($ahorr[$value['usuario_id']])) $ahorr[$value['usuario_id']] = -$value['cantidad'];
    else $ahorr[$value['usuario_id']] -= $value['cantidad'];
  }
}
//obtener los saldos anteriores
foreach ($nomina as $key => $value)
{
  $saldant[$value['usuario_id']] = ($value['total']-$value['pagado']);
}
?>
<style media="screen">
th {
  text-align: center;
}
</style>
<script type="text/javascript">
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
    "lengthMenu": [ 10, 20, 50, 100 ],
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="col-12">
        <h3>Generar <?php echo $descripcion; ?></h3>
      </div>
      <div class='table-responsive'>
        <form action="verNomina" method="post" id="verNomina" name="verNomina">
          <input type="hidden" name="idNomina" id="idNomina" value="<?php echo $id ?>">
          <input type="hidden" name="descripcion" id="descripcion" value="<?php echo $descripcion ?>">
          <div class="table-responsive">
            <table class="table table-hover" name="tabla" id="tabla">
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
              <tbody><?php foreach ($operarios as $key => $value): ?>
                <tr>
                  <td>
                    <input type="hidden" name="nombre[<?php echo $value['id'] ?>]" id="nombre[<?php echo $value['id'] ?>]" value="<?php echo $value['nombre_completo']; ?>">
                    <label><?php echo $value['nombre_completo']; ?></label>
                  </td>
                  <td>
                    <input type="hidden" name="puesto[<?php echo $value['id'] ?>]" id="puesto[<?php echo $value['id'] ?>]" value="<?php echo $value['puesto']; ?>">
                    <label><?php echo $value['puesto']; ?></label>
                  </td>
                  <td>
                    <input type="hidden" id="saldo_anterior_<?php echo $value['id'] ?>" name="saldo_anterior[<?php echo $value['id'] ?>]" value="<?php $s  = (isset($saldant[$value['id']])) ? $saldant[$value['id']] : 0 ; echo $s; ?>">
                    <label><i class="fas fa-dollar-sign"></i><?php echo $s; ?></label>
                  </td>
                  <td>
                    <input type="hidden" id="nomina_<?php echo $value['id'] ?>" name="nomina[<?php echo $value['id'] ?>]" value="<?php $p  = (isset($prod[$value['id']])) ? $prod[$value['id']] : 0 ; echo $p; ?>">
                    <i class="fas fa-dollar-sign"></i><label ><?php echo $p; ?></label>
                  </td>
                  <td class="table-primary">
                    <input type="hidden" id="ahorro_anterior_<?php echo $value['id'] ?>" name="ahorro_anterior[<?php echo $value['id'] ?>]" value="<?php $a  = (isset($ahorr[$value['id']])) ? $ahorr[$value['id']] : 0 ; echo $a; ?>">
                    <i class="fas fa-dollar-sign"></i><label><?php echo $a; ?></label>
                  </td>
                  <td class="table-primary"><div class="input-group input-group-sm mb-3"><input type="number" required class="form-control" step="any" id="ahorro_abono_<?php echo $value['id'] ?>" name="ahorro_abono[<?php echo $value['id'] ?>]" value="0" oninput="calcula(<?php echo $value['id'] ?>);"></div></td>
                  <td class="table-primary">
                    <input type="hidden" id="ahorro_saldo_<?php echo $value['id'] ?>" name="ahorro_saldo[<?php echo $value['id'] ?>]" value="<?php echo $a ?>">
                    <i class="fas fa-dollar-sign"></i><label id="td_ahorro_saldo_<?php echo $value['id'];?>"><?php echo $a ?></label>
                  </td>
                  <td class="table-success"><div class="input-group input-group-sm mb-3"><input type="number" required class="form-control" step="any" id="bonos_<?php echo $value['id'] ?>" name="bonos[<?php echo $value['id'] ?>]" value="0" oninput="calcula(<?php echo $value['id'] ?>);"></div></td>
                  <td class="table-danger">
                    <input type="hidden" id="descuentos_anterior_<?php echo $value['id'] ?>" name="descuentos_anterior[<?php echo $value['id'] ?>]" value="<?php $d  = (isset($desc[$value['id']])) ? $desc[$value['id']] : 0 ; echo $d; ?>">
                    <i class="fas fa-dollar-sign"></i><label><?php echo $d; ?></label>
                  </td>
                  <td class="table-danger"><div class="input-group input-group-sm mb-3"><input type="number" required class="form-control" step="any" id="descuentos_abono_<?php echo $value['id'] ?>" name="descuentos_abono[<?php echo $value['id'] ?>]" value="0" oninput="calcula(<?php echo $value['id'] ?>);"></div></td>
                  <td class="table-danger">
                    <input type="hidden" id="descuentos_saldo_<?php echo $value['id'] ?>" name="descuentos_saldo[<?php echo $value['id'] ?>]" value="<?php echo $d ?>">
                    <i class="fas fa-dollar-sign"></i><label id="td_descuentos_saldo_<?php echo $value['id'];?>"><?php echo $d ?></label>
                  </td>
                  <td>
                    <input type="hidden" id="total_<?php echo $value['id'] ?>" name="total[<?php echo $value['id'] ?>]" value="<?php $total += $s+$p; echo $s+$p; ?>">
                    <i class="fas fa-dollar-sign"></i><label id="td_total_<?php echo $value['id'];?>"><?php echo $s+$p; ?></label>
                  </td>
                  <td><div class="input-group input-group-sm mb-3"><input type="number" required class="form-control" step="any" id="pagado_<?php echo $value['id'] ?>" name="pagado[<?php echo $value['id'] ?>]" value="0" oninput="pagado();"></div></td>
                </tr><?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <center class="col-auto">
            <input type="submit" class="btn btn-primary" value="Generar">
          </center>
        </form>
        <table class="table table-hover">
          <tr>
            <th>Total</th>
            <td>
              <i class="fas fa-dollar-sign"></i>
              <label name="totalTotal" id="totalTotal"><?php echo $total ?></label>
            </td>
          </tr>
          <tr>
            <th>Total a pagar</th>
            <td>
              <i class="fas fa-dollar-sign"></i>
              <label name="totalPagar" id="totalPagar">0</label>
            </td>
          </tr>
          <tr>
            <th>Diferencia</th>
            <td>
              <i class="fas fa-dollar-sign"></i>
              <label name="diferencia" id="diferencia">0</label>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</diV>
