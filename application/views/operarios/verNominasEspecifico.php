<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container-fluid">
  <div class="row">
    <h3>Datos de la nómina generada el <?php echo $fecha ?></h3>
    <form action="verNominas" method="post" class="col-12" target="_blank">
      <input type="hidden" name="fecha" value="<?php echo $fecha ?>">
      <div class="card col-md-6 col-lg-6 offset-md-3 col-lg-3">
        <a data-toggle="collapse" href="#especificos" role="button" aria-expanded="false" aria-controls="especificos">
          <div class="card-header">
            <center><h4>Datos específicos de la nómina.</h4></center>
          </div>
        </a>
        <div class="collapse" id="especificos">
          <div class="card-body">
            <div class='table-responsive'>
              <table class="table" style="background:rgba(255,255,255,0.9);">
                <tbody>
                  <tr>
                    <th>Saldo anterior</th>
                    <td>
                      $<?php echo $nomina['saldo_anterior'] ?>
                      <input type="hidden" name="saldo_anterior" value="<?php echo $nomina['saldo_anterior'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Total de producción</th>
                    <td>
                      $<?php echo $nomina['nomina'] ?>
                      <input type="hidden" name="nomina" value="<?php echo $nomina['nomina'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Saldo anterior de descuentos</th>
                    <td>
                      $<?php echo $nomina['descuentos_anterior'] ?>
                      <input type="hidden" name="descuentos_anterior" value="<?php echo $nomina['descuentos_anterior'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Aportación a los descuentos</th>
                    <td>
                      $<?php echo $nomina['descuentos_abono'] ?>
                      <input type="hidden" name="descuentos_abono" value="<?php echo $nomina['descuentos_abono'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Saldo de descuentos</th>
                    <td>
                      $<?php echo $nomina['descuentos_saldo'] ?>
                      <input type="hidden" name="descuentos_saldo" value="<?php echo $nomina['descuentos_saldo'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Saldo anterior de ahorro</th>
                    <td>
                      $<?php echo $nomina['ahorro_anterior'] ?>
                      <input type="hidden" name="ahorro_anterior" value="<?php echo $nomina['ahorro_anterior'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Aportación al ahorro</th>
                    <td>
                      $<?php echo $nomina['ahorro_abono'] ?>
                      <input type="hidden" name="ahorro_abono" value="<?php echo $nomina['ahorro_abono'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Saldo de ahorro</th>
                    <td>
                      $<?php echo $nomina['ahorro_saldo'] ?>
                      <input type="hidden" name="ahorro_saldo" value="<?php echo $nomina['ahorro_saldo'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Bonos</th>
                    <td>
                      $<?php echo $nomina['bonos'] ?>
                      <input type="hidden" name="bonos" value="<?php echo $nomina['bonos'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>
                      $<?php echo $nomina['total'] ?>
                      <input type="hidden" name="total" value="<?php echo $nomina['total'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Cantidad que se pagó</th>
                    <td>
                      $<?php echo $nomina['pagado'] ?>
                      <input type="hidden" name="pagado" value="<?php echo $nomina['pagado'] ?>">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="card col-12">
        <a data-toggle="collapse" href="#produccion" role="button" aria-expanded="false" aria-controls="produccion">
          <div class="card-header">
          <center><h4>Datos de la producción en proceso seco.</h4></center>
          </div>
        </a>
        <div class="collapse" id="produccion">
          <div class="card-body">
            <?php if (count($produccion)>0): ?>
              <div class='table-responsive'>
                <table name="tabla" id="tabla" class="table" style="background:rgba(255,255,255,0.9);">
                  <thead>
                    <tr>
                      <th>Folio del corte</th>
                      <th>Fecha de registro</th>
                      <th>Carga o lavado</th>
                      <th>Proceso</th>
                      <th>Piezas registradas</th>
                      <th>Defectos registrados</th>
                      <th>Precio unitario</th>
                      <th>Cantidad para pagar</th>
                      <th>Estado de nómina</th>
                      <th>Razón por la que no se pagó</th>
                    </tr>
                  </thead>
                  <tbody><?php foreach ($produccion as $key => $value):
                    switch ($value['estado']) {
                      case 0:
                      $razon = "No se ha pagado";
                      $clase = "table-primary";
                      break;
                      case 1:
                      $razon = "Se pagó";
                      $clase = "table-success";
                      break;
                      case 2:
                      $razon = "Está pendiente";
                      $clase = "table-warning";
                      break;
                      default:
                      $razon = "No se pagará";
                      $clase = "table-danger";
                      break;
                    }?>
                    <tr class="<?php echo $clase ?>">
                      <td>
                        <?php echo $value['folio'] ?>
                        <input type="hidden" name="produccion_folio[<?php echo $key ?>]" value="<?php echo $value['folio'] ?>">
                      </td>
                      <td>
                        <?php echo $value['fecha'] ?>
                        <input type="hidden" name="produccion_fecha[<?php echo $key ?>]" value="<?php echo $value['fecha'] ?>">
                      </td>
                      <td>
                        <?php echo $value['lavado'] ?>
                        <input type="hidden" name="produccion_lavado[<?php echo $key ?>]" value="<?php echo $value['lavado'] ?>">
                      </td>
                      <td>
                        <?php echo $value['proceso'] ?>
                        <input type="hidden" name="produccion_proceso[<?php echo $key ?>]" value="<?php echo $value['proceso'] ?>">
                      </td>
                      <td>
                        <?php echo $value['piezas'] ?>
                        <input type="hidden" name="produccion_piezas[<?php echo $key ?>]" value="<?php echo $value['piezas'] ?>">
                      </td>
                      <td>
                        <?php echo $value['defectos'] ?>
                        <input type="hidden" name="produccion_defectos[<?php echo $key ?>]" value="<?php echo $value['defectos'] ?>">
                      </td>
                      <td>
                        $<?php echo $value['cantidad_pagar']/$value['piezas'] ?>
                        <input type="hidden" name="produccion_unitario[<?php echo $key ?>]" value="<?php echo $value['cantidad_pagar']/$value['piezas'] ?>">
                      </td>
                      <td>
                        $<?php echo $value['cantidad_pagar'] ?>
                        <input type="hidden" name="produccion_cantidad_pagar[<?php echo $key ?>]" value="<?php echo $value['cantidad_pagar'] ?>">
                      </td>
                      <td>
                        <?php echo $razon ?>
                        <input type="hidden" name="produccion_razon[<?php echo $key ?>]" value="<?php echo $razon ?>">
                      </td>
                      <td>
                        <?php echo $value['razon_pagar'] ?>
                        <input type="hidden" name="produccion_razon_pagar[<?php echo $key ?>]" value="<?php echo $value['razon_pagar'] ?>">
                      </td>
                    </tr><?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">
                No existen registros de proceso seco
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="card col-12">
        <a data-toggle="collapse" href="#reprocesos" role="button" aria-expanded="false" aria-controls="reprocesos">
          <div class="card-header">
            <center><h4>Datos de la producción de reprocesos.</h4></center>
          </div>
        </a>
        <div class="collapse" id="reprocesos">
          <div class="card-body">
            <?php if (count($produccionReprocesos)>0): ?>
              <div class='table-responsive'>
                <table name="tabla" id="tabla" class="table" style="background:rgba(255,255,255,0.9);">
                  <thead>
                    <tr>
                      <th>Folio del corte</th>
                      <th>Fecha de registro</th>
                      <th>Carga o lavado</th>
                      <th>Proceso</th>
                      <th>Piezas registradas</th>
                      <th>Defectos registrados</th>
                      <th>Precio unitario</th>
                      <th>Cantidad para pagar</th>
                      <th>Estado de nómina</th>
                      <th>Razón por la que no se pagó</th>
                    </tr>
                  </thead>
                  <tbody><?php foreach ($produccionReprocesos as $key => $value):
                    switch ($value['estado']) {
                      case 0:
                      $razon = "No se ha pagado";
                      $clase = "table-primary";
                      break;
                      case 1:
                      $razon = "Se pagó";
                      $clase = "table-success";
                      break;
                      case 2:
                      $razon = "Está pendiente";
                      $clase = "table-warning";
                      break;
                      default:
                      $razon = "No se pagará";
                      $clase = "table-danger";
                      break;
                    }?>
                    <tr class="<?php echo $clase ?>">
                      <td>
                        <?php echo $value['folio'] ?>
                        <input type="hidden" name="reprocesos_folio[<?php echo $key ?>]" value="<?php echo $value['folio'] ?>">
                      </td>
                      <td>
                        <?php echo $value['fecha'] ?>
                        <input type="hidden" name="reprocesos_fecha[<?php echo $key ?>]" value="<?php echo $value['fecha'] ?>">
                      </td>
                      <td>
                        <?php echo $value['lavado'] ?>
                        <input type="hidden" name="reprocesos_lavado[<?php echo $key ?>]" value="<?php echo $value['lavado'] ?>">
                      </td>
                      <td>
                        <?php echo $value['proceso'] ?>
                        <input type="hidden" name="reprocesos_proceso[<?php echo $key ?>]" value="<?php echo $value['proceso'] ?>">
                      </td>
                      <td>
                        <?php echo $value['piezas'] ?>
                        <input type="hidden" name="reprocesos_piezas[<?php echo $key ?>]" value="<?php echo $value['piezas'] ?>">
                      </td>
                      <td>
                        <?php echo $value['defectos'] ?>
                        <input type="hidden" name="reprocesos_defectos[<?php echo $key ?>]" value="<?php echo $value['defectos'] ?>">
                      </td>
                      <td>
                        $<?php echo $value['cantidad_pagar']/$value['piezas'] ?>
                        <input type="hidden" name="reprocesos_unitario[<?php echo $key ?>]" value="<?php echo $value['cantidad_pagar']/$value['piezas'] ?>">
                      </td>
                      <td>
                        $<?php echo $value['cantidad_pagar'] ?>
                        <input type="hidden" name="reprocesos_cantidad_pagar[<?php echo $key ?>]" value="<?php echo $value['cantidad_pagar'] ?>">
                      </td>
                      <td>
                        <?php echo $razon ?>
                        <input type="hidden" name="reprocesos_razon[<?php echo $key ?>]" value="<?php echo $razon ?>">
                      </td>
                      <td>
                        <?php echo $value['razon_pagar'] ?>
                        <input type="hidden" name="reprocesos_razon_pagar[<?php echo $key ?>]" value="<?php echo $value['razon_pagar'] ?>">
                      </td>
                    </tr><?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">
                No existen registros de reproceso.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="mx-auto">
        <button type="submit" class="btn btn-info btn-lg"><i class="far fa-file-pdf"></i></button>
      </div>
    </form>
  </div>
</div>
