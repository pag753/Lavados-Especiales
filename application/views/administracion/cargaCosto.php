<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3><?php echo $texto1 ?></h3>
      <form action="<?php echo base_url(); ?>index.php/administracion/costos/<?php echo $folio."_".$carga ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="folio" id="folio" value="<?php echo $folio; ?>"/>
        <input type="hidden" name="corte" id="corte" value="<?php echo $corte; ?>"/>
        <input type="hidden" name="marca" id="marca" value="<?php echo $marca; ?>"/>
        <input type="hidden" name="maquilero" id="maquilero" value="<?php echo $maquilero; ?>"/>
        <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente; ?>"/>
        <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>"/>
        <input type="hidden" name="piezas" id="piezas" value="<?php echo $piezas; ?>"/>
        <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>"/>
        <input type="hidden" name="carga" id="carga" value="<?php echo $carga; ?>"/>
        <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado; ?>"/>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="text" name="folio" class="form-control" readonly value="<?php echo $folio; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="lavado" class="col-3 col-form-label">Lavado</label>
          <div class="col-9">
            <input type="text" name="lavado" class="form-control" readonly value="<?php echo strtoupper($lavado) ?>">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12">
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
                      <td><input type="text" readonly="true" class="form-control" name="proc[<?php echo $key ?>]" value="<?php echo strtoupper($value) ?>"/></td>
                      <td><input type="number" step="any" required placeholder="Inserte costo" class="form-control" name="costo[<?php echo $key ?>]" value="<?php echo $costos[$key] ?>"></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <button type="button" name="informacion" id="boton" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">Ver detalles</button>
        <center>
          <a href="<?php echo base_url() ?>index.php/administracion/costos/"><button name="regresar" id="regresar" type="button" class="btn btn-secondary">Regresar</button></a>
          <input type="submit" class="btn btn-primary" value="Aceptar"/>
        </center>
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
