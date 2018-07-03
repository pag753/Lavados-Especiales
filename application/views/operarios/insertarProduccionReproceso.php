<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
$(document).ready(function() {
  $('#formulario').submit(function() {
    return confirm('¿Está seguro de registrar esta producción de reproceso?')
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Alta de producción de reproceso</h3>
      <form action="insertarReproceso" method="post" name="formulario" id="formulario">
        <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo ?>"> <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
        <table class="table table-striped table-hover" id="tabla" style="background: rgba(255, 255, 255, 0.9)">
          <tbody>
            <tr>
              <th>Carga o lavado</th>
              <td><?php echo $lavado ?></td>
            </tr>
            <tr>
              <th>Proceso</th>
              <td><?php echo $proceso ?></td>
            </tr>
            <tr>
              <th>Número de piezas</th>
              <td><input class="form-control" type="number" id="piezas" name="piezas" value="<?php echo $piezas ?>" required></td>
            </tr>
            <tr>
              <th>Número de defectos</th>
              <td><input class="form-control" type="number" id="defectos" name="defectos" value="<?php echo $defectos ?>" required></td>
            </tr>
          </tbody>
        </table>
        <div class="mx-auto">
          <input type="submit" value="Aceptar" class="btn btn-primary">
        </div>
      </form>
    </div>
  </div>
</div>
