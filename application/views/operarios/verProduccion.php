<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Ver Producción</h3>
      <form action="<?php echo base_url().'index.php/'.$data.'/ver' ?>"
        target="_blank" method="post" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="fechaInicio" class="col-3 col-form-label">Fecha de
            inicio</label>
          <div class="col-9">
            <input class="form-control" type="date"
              value="<?php echo date('Y-m-d'); ?>" name="fechaInicio"
              id="fechaInicio">
          </div>
        </div>
        <div class="form-group row">
          <label for="fechaFinal" class="col-3 col-form-label">Fecha
            final</label>
          <div class="col-9">
            <input class="form-control" type="date"
              value="<?php echo date('Y-m-d'); ?>" name="fechaFinal"
              id="fechaFinal">
          </div>
        </div>
        <input type="submit" name="aceptar" value="Generar"
          class="btn btn-primary">
      </form>
    </div>
  </div>
</div>