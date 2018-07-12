<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
<?php if (isset($cortesJson)): ?>
var cortesJson = <?php echo json_encode($cortesJson); ?>;
function verInfo(id) {
  $('#imagenModal').html(cortesJson[id].imagen);
  $('#folioModal').html(cortesJson[id].folio);
  $('#corteModal').html(cortesJson[id].corte);
  $('#marcaModal').html(cortesJson[id].marca);
  $('#maquileroModal').html(cortesJson[id].maquilero);
  $('#clienteModal').html(cortesJson[id].cliente);
  $('#tipoModal').html(cortesJson[id].tipo);
  $('#fechaModal').html(cortesJson[id].fecha);
  $('#piezasModal').html(cortesJson[id].piezas);
  $('#ojalesModal').html(cortesJson[id].ojales);
  $('#infoCorte').modal('show');
}
<?php endif; ?>
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 offset-md-1" style="background-color: rgba(255,255,255,0.7)">
      <h3>Reporte de cortes con ojal del <?php echo $this->input->get()['fechaInicial'] ?> al <?php echo $this->input->get()['fechaFinal'] ?></h3>
      <?php if (isset($cortesJson)): ?>
        <form action="reporteOjal" method="post" target="_blank">
          <input type="hidden" name="fechaInicial" value="<?php echo $this->input->get()['fechaInicial'] ?>">
          <input type="hidden" name="fechaFinal" value="<?php echo $this->input->get()['fechaFinal'] ?>">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Folio de corte</th>
                  <th>Número de piezas</th>
                  <th>Número de ojales</th>
                  <th>Fecha de entrada del corte</th>
                  <th>Ver con detalle</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cortes as $key => $value): ?>
                  <tr>
                    <td><?php echo $value['folio'] ?></td>
                    <td>
                      <?php echo $value['piezas'] ?>
                      <input type="hidden" name="piezas[<?php echo $value['folio'] ?>]" value="<?php echo $value['piezas'] ?>">
                    </td>
                    <td>
                      <?php echo $value['ojales'] ?>
                      <input type="hidden" name="ojales[<?php echo $value['folio'] ?>]" value="<?php echo $value['ojales'] ?>">
                    </td>
                    <td>
                      <?php echo $value['fecha'] ?>
                      <input type="hidden" name="fecha[<?php echo $value['folio'] ?>]" value="<?php echo $value['fecha'] ?>">
                    </td>
                    <td>
                      <button type="button" class="btn btn-info" onclick="verInfo(<?php echo $value['folio'] ?>)" title="Ver la información general del corte">
                        <i class="fas fa-info"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <button type="submit" title="Ver versión en PDF de esta información" class="btn btn-info"><i class="far fa-file-pdf"></i></button>
        </form>
      <?php else: ?>
        <div class="alert alert-danger" role="alert">No hay cortes en este rango de fechas.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php if (isset($cortesJson)): ?>
  <div class="modal fade" id="infoCorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <td id="imagenModal"></td>
                    </tr>
                    <tr>
                      <td>Folio</td>
                      <td id="folioModal"></td>
                    </tr>
                    <tr>
                      <td>Corte</td>
                      <td id="corteModal"></td>
                    </tr>
                    <tr>
                      <td>Marca</td>
                      <td id="marcaModal"></td>
                    </tr>
                    <tr>
                      <td>Maquilero</td>
                      <td id="maquileroModal"></td>
                    </tr>
                    <tr>
                      <td>Cliente</td>
                      <td id="clienteModal"></td>
                    </tr>
                    <tr>
                      <td>Tipo</td>
                      <td id="tipoModal"></td>
                    </tr>
                    <tr>
                      <td>Fecha de entrada</td>
                      <td id="fechaModal"></td>
                    </tr>
                    <tr>
                      <td>Piezas</td>
                      <td id="piezasModal"></td>
                    </tr>
                    <tr>
                      <td>Ojales</td>
                      <td id="ojalesModal"></td>
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
<?php endif; ?>
