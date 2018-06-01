<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#nombre_completo").focus();
  });
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="<?php echo $link ?>" method="post" enctype="multipart/form-data">
        <h1>Cambiar datos personales</h1>
        <div class="form-group row">
          <label for="nombre_completo" class="col-3 col-form-label">Nombre completo</label>
          <div class="col-9">
            <input type="text" name="nombre_completo" id="nombre_completo" placeholder="Escribe el nombre completo" required class="form-control" value="<?php echo $data[0]['nombre_completo'] ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="direccion" class="col-3 col-form-label">Dirección</label>
          <div class="col-9">
            <input type="text" name="direccion" id="direccion" placeholder="Escribe la dirección" required class="form-control" value="<?php echo $data[0]['direccion'] ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="telefono" class="col-3 col-form-label">Teléfono</label>
          <div class="col-9">
            <input type="text" name="telefono" id="telefono" placeholder="Escribe el teléfono" required class="form-control" value="<?php echo $data[0]['telefono'] ?>">
          </div>
        </div>
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-success" value="Aceptar"><i class="far fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
