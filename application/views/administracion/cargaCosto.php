<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
$(document).ready(function() {
  $('form').submit(function() {
    return confirm('¿Está seguro de cambiar los costos?');
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3><?php echo $texto1 ?></h3>
      <form action="costos" method="post" enctype="multipart/form-data">
        <input type="hidden" name="folio" id="folio" value="<?php echo $folio; ?>" /> <input type="hidden" name="corte" id="corte" value="<?php echo $corte; ?>" /> <input type="hidden" name="marca" id="marca" value="<?php echo $marca; ?>" />
        <input type="hidden" name="maquilero" id="maquilero" value="<?php echo $maquilero; ?>" />
        <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente; ?>" />
        <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
        <input type="hidden" name="piezas" id="piezas" value="<?php echo $piezas; ?>" />
        <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>" />
        <input type="hidden" name="carga" id="carga" value="<?php echo $carga; ?>" />
        <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado; ?>" />
        <input type="hidden" name="folio" value="<?php echo $folio; ?>">
        <input type="hidden" name="lavado" value="<?php echo strtoupper($lavado) ?>">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th>Folio</th>
              <td><?php echo $folio; ?></td>
            </tr>
            <tr>
              <th># Carga</th>
              <td><?php echo $this->input->get()['carga'] ?></td>
            </tr>
            <tr>
              <th>Lavado</th>
              <td><?php echo strtoupper($lavado) ?></td>
            </tr>
            <tr>
              <th>Color de hilo</th>
              <td><?php echo $this->input->get()['color_hilo'] ?></td>
            </tr>
            <tr>
              <th>Tipo</th>
              <td><?php echo $this->input->get()['tipo'] ?></td>
            </tr>
          </tbody>
        </table>
        <div class="form-group row">
          <div class="col-12">
            <h5>Tabla de costos</h5>
            <p><strong>Nota: </strong>Asegurese de que las fases de lavandería tengan costos de -1 y snow de 0.</p>
            <div class='table-responsive'>
              <table class="table">
                <thead>
                  <tr>
                    <th>Proceso</th>
                    <th>Costo $</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($procesos as $key => $value): ?>
                    <tr>
                      <td><?php echo strtoupper($value['proceso']) ?></td>
                      <td>
                        <input required type="number" step="any" required placeholder="Inserte costo" class="form-control" name="costo[<?php echo $value['id'] ?>]" value="<?php echo $value['costo'] ?>">
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="mx-auto">
          <a href="<?php echo base_url() ?>index.php/administracion/costos/<?php echo $this->input->get()['folio'] ?>">
            <button name="regresar" id="regresar" type="button" class="btn btn-secondary">Regresar</button>
          </a>
          <input type="submit" class="btn btn-primary" value="Aceptar" />
          <button type="button" name="informacion" id="boton" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Información del corte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <td>Imágen</td>
                    <td><?php echo $imagen; ?></td>
                  </tr>
                  <tr>
                    <td>Folio</td>
                    <td><?php echo $folio; ?></td>
                  </tr>
                  <tr>
                    <td>Corte</td>
                    <td><?php echo $corte; ?></td>
                  </tr>
                  <tr>
                    <td>Marca</td>
                    <td><?php echo $marca; ?></td>
                  </tr>
                  <tr>
                    <td>Maquilero</td>
                    <td><?php echo $maquilero; ?></td>
                  </tr>
                  <tr>
                    <td>Cliente</td>
                    <td><?php echo $cliente; ?></td>
                  </tr>
                  <tr>
                    <td>Tipo</td>
                    <td><?php echo $tipo; ?></td>
                  </tr>
                  <tr>
                    <td>Fecha de entrada</td>
                    <td><?php echo $fecha; ?></td>
                  </tr>
                  <tr>
                    <td>Piezas</td>
                    <td><?php echo $piezas; ?></td>
                  </tr>
                  <tr>
                    <td>Ojales</td>
                    <td><?php echo $ojales; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
