<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
$(document).ready(function() {
  $("form").submit(function( event ) {
    if ($('#piezas').val() * 1 + $('#defectos').val() * 1 == $('#piezasC').html() * 1) {
      return confirm('¿Está seguro de querer reportar?')
    }
    else {
      alert('La suma de piezas reportadas mas los defectos no es igual a las piezas de la carga.');
      return false;
    }
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
          <form action="reportar" method="post" id="form">
            <input type="hidden" name="id" value="<?php echo $this->input->get()['id'] ?>">
            <input type="hidden" name="orden" id="orden" value="<?php echo $orden ?>" />
            <input type="hidden" name="folio" value="<?php echo $this->input->get()['f'] ?>">
            <div class='table-responsive'>
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th>Folio</th>
                    <td><?php echo $f; ?></td>
                  </tr>
                  <tr>
                    <th>Marca</th>
                    <td><?php echo $this->input->get()['m'] ?></td>
                  </tr>
                  <tr>
                    <th>Cliente</th>
                    <td><?php echo $this->input->get()['cl'] ?></td>
                  </tr>
                  <tr>
                    <th># piezas del corte</th>
                    <td><?php echo $this->input->get()['pie'] ?></td>
                  </tr>
                  <tr>
                    <th>Color de hilo</th>
                    <td><?php echo $this->input->get()['color_hilo'] ?></td>
                  </tr>
                  <tr>
                    <th>Tipo</th>
                    <td><?php echo $this->input->get()['tipo'] ?></td>
                  </tr>
                  <tr>
                    <th># de carga</th>
                    <td><?php echo $this->input->get()['c'] ?></td>
                  </tr>
                  <tr>
                    <th>Lavado</th>
                    <td><?php echo strtoupper($nombreCarga); ?></td>
                  </tr>
                  <tr>
                    <th>Proceso</th>
                    <td><?php echo strtoupper($nombreProceso); ?></td>
                  </tr>
                  <tr>
                    <th>Piezas de la carga</th>
                    <td id="piezasC"><?php echo $piezas ?></td>
                  </tr>
                  <tr>
                    <th>Piezas reportadas por lavandería</th>
                    <td>
                      <input type="number" name="piezas" value="0" id="piezas" class="form-control">
                    </td>
                  </tr>
                  <tr>
                    <th>Defectos de lavandería</th>
                    <td>
                      <input type="number" name="defectos" value="0" id="defectos" class="form-control">
                    </td>
                  </tr>
                  <?php if (count($faltantes) > 0): ?>
                    <tr>
                      <th>Siguiente proceso</th>
                      <td>
                        <select name='siguiente' id='siguiente' class="form-control">
                          <?php foreach ($faltantes as $key => $value): ?>
                            <option value="<?php echo $value['id']?>"><?php echo strtoupper($value['proceso'])?></option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="ml-auto">
              <input type="submit" value="Aceptar" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
