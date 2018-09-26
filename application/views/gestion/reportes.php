<?php
defined('BASEPATH') or exit('No direct script access allowed');
$input_fechai = array(
  'name' => 'fechai',
  'id' => 'fechai',
  'type' => 'date',
  'class' => 'tcal',
  'value' => set_value('fecha', date("Y-m-d")),
  'class' => 'form-control',
  'title' => 'Fecha inicial',
);
$input_fechaf = array(
  'name' => 'fechaf',
  'id' => 'fechaf',
  'type' => 'date',
  'class' => 'tcal',
  'value' => set_value('fecha', date("Y-m-d")),
  'class' => 'form-control',
  'title' => 'Fecha final',
);
?>
<script type="text/javascript">
function cambio(val) {
  if (val * 1  != 3) {
    $('#fi').attr('hidden','');
    $('#ff').attr('hidden','');
    $('#c').attr('hidden','');
    $('#f').attr('hidden','');
    $('#cl').attr('hidden','');
    $('#m').attr('hidden','');
    $('#maq').attr('hidden','');
    $('#ti').attr('hidden','');
  }
  else {
    $('#fi').removeAttr('hidden','false');
    $('#ff').removeAttr('hidden','false');
    $('#c').removeAttr('hidden','false');
    $('#f').removeAttr('hidden','false');
    $('#cl').removeAttr('hidden','false');
    $('#m').removeAttr('hidden','false');
    $('#maq').removeAttr('hidden','false');
    $('#ti').removeAttr('hidden','false');
  }
}
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="generaReporte" method="get" enctype="multipart/form-data" target="_blank">
        <h1>Generar Reporte</h1>
        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th>Tipo de Reporte</th>
                <td>
                  <select name="reporte" id="reporte" class="form-control" required onchange="cambio(this.value)">
                    <option label="Selecciona el tipo de reporte">Selecciona el tipo de reporte</option>
                    <option value="1">Cortes en almacen de entrada</option>
                    <option value="2">Cortes autorizados</option>
                    <option value="3">Cortes entregados al cliente</option>
                    <option value="4">Cortes en proceso</option>
                    <option value="5">Cortes en almacen de salida</option>
                  </select>
                </td>
              </tr>
              <tr id="fi" hidden>
                <th>Fecha inicial</th>
                <td><?php echo form_input($input_fechai); ?></td>
              </tr>
              <tr id="ff" hidden>
                <th>Fecha final</th>
                <td><?php echo form_input($input_fechaf); ?></td>
              </tr>
              <tr id="c" hidden>
                <th>Corte</th>
                <td>
                  <input type="text" name="corte" placeholder="Escribir si es necesario" class="form-control" title="Corte">
                </td>
              </tr>
              <tr id="f" hidden>
                <th>Folio</th>
                <td>
                  <input type="number" name="folio" placeholder="Escribir si es necesario" class="form-control" title="Número de folio">
                </td>
              </tr>
              <tr id="cl" hidden>
                <th>Cliente</th>
                <td>
                  <select name="cliente_id" class="form-control" title="Cliente">
                    <option value="0">Escoja si es necesario</option>
                    <?php foreach ($clientes as $key => $value): ?>
                      <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
              <tr id="m" hidden>
                <th>Marca</th>
                <td>
                  <select name="marca_id" class="form-control" title="Marca">
                    <option value="0">Escoja si es necesario</option>
                    <?php foreach ($marcas as $key => $value): ?>
                      <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
              <tr id="maq" hidden>
                <th>Maquilero</th>
                <td>
                  <select name="maquilero_id" class="form-control" title="Maquilero">
                    <option value="0">Escoja si es necesario</option>
                    <?php foreach ($maquileros as $key => $value): ?>
                      <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
              <tr id="ti" hidden>
                <th>Tipo de pantalón</th>
                <td>
                  <select name="tipo_pantalon_id" class="form-control" title="Tipo de pantalón">
                    <option value="0">Escoja si es necesario</option>
                    <?php foreach ($tipos as $key => $value): ?>
                      <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="ml-auto">
          <input type="submit" name="aceptar" class="btn btn-primary" value="Aceptar" title="Generar el reporte" />
        </div>
      </form>
    </div>
  </div>
</div>
