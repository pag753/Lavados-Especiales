<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$input_fechai = array(
  'name' => 'fechai',
  'id' => 'fechai',
  'type' => 'datetime-local',
  'class' => 'tcal',
  'value' => set_value('fecha',date("Y-m-d")."T00:00:00"),
  'class' => 'form-control',
);
$input_fechaf = array(
  'name' => 'fechaf',
  'id' => 'fechaf',
  'type' => 'datetime-local',
  'class' => 'tcal',
  'value' => set_value('fecha',date("Y-m-d")."T00:00:00"),
  'class' => 'form-control',
);
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="generaReporte" method="post" enctype="multipart/form-data" target="_blank">
        <h1>Generar Reporte</h1>
        <div class="form-group row">
          <label for="reporte" class="col-3 col-form-label">Tipo de Reporte</label>
          <div class="col-9">
            <select name="reporte" id="reporte" class="form-control">
              <option value="1">Cortes en almacen</option>
              <option value="2">Cortes autorizados</option>
              <option value="3">Cortes entregados</option>
              <option value="4">Cortes en proceso</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="fechai" class="col-3 col-form-label">Fecha inicial</label>
          <div class="col-9">
            <?php echo form_input($input_fechai); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="fechaf" class="col-3 col-form-label">Fecha final</label>
          <div class="col-9">
            <?php echo form_input($input_fechaf); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="corte" class="col-3 col-form-label">Corte</label>
          <div class="col-9">
            <input type="text" name="corte" placeholder="Escribir si es necesario" class="form-control">
          </div>
        </div>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="number" name="folio" placeholder="Escribir si es necesario" class="form-control">
          </div>
        </div>
        <div class="form-group row">
          <label for="cliente_id" class="col-3 col-form-label">Cliente</label>
          <div class="col-9">
            <select name="cliente_id" class="form-control">
              <option value="0">Escoja si es necesario</option>
              <?php foreach ($clientes as $key => $value): ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="marca_id" class="col-3 col-form-label">Marca</label>
          <div class="col-9">
            <select name="marca_id" class="form-control">
              <option value="0">Escoja si es necesario</option>
              <?php foreach ($marcas as $key => $value): ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="maquilero_id" class="col-3 col-form-label">Maquilero</label>
          <div class="col-9">
            <select name="maquilero_id" class="form-control">
              <option value="0">Escoja si es necesario</option>
              <?php foreach ($maquileros as $key => $value): ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="tipo_pantalon_id" class="col-3 col-form-label">Tipo de pantalón</label>
          <div class="col-9">
            <select name="tipo_pantalon_id" class="form-control">
              <option value="0">Escoja si es necesario</option>
              <?php foreach ($tipos as $key => $value): ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="check" class="col-3 col-form-label">Incluir imágenes</label>
          <div class="col-9">
            <input type="checkbox" name="check">
          </div>
        </div>
        <div class="offset-sm-2 col-sm-10">
          <input type="submit" name="aceptar" class="btn btn-primary" value="Aceptar"/>
        </div>
      </form>
    </div>
  </div>
</div>
