<?php defined('BASEPATH') or exit('No direct script access allowed');
$contador = 0;
?>
<script type="text/javascript">
function ver(id) {
  $.ajax({
    dataType: "json",
    data: {folio: id},
    url: "<?php echo base_url(); ?>index.php/ajax/detalleCorte",
    type: "POST",
    error: function(xhr,status,error) {
      window.location.replace("<?php echo base_url() ?>");
    },
    success: function(result) {
      $('#imagen').html(result.imagen);
      $('#folio').html(result.folio);
      $('#marca').html(result.marca);
      $('#maquilero').html(result.maquilero);
      $('#cliente').html(result.cliente);
      $('#tipo').html(result.tipo);
      $('#fecha').html(result.fecha);
      $('#piezas').html(result.piezas);
      $('#ojales').html(result.ojales);
      $('#modal').modal('show');
    },
  });
}
</script>
<div class="container-fluid">
  <div class="row">
    <form action="reporte4" method="post" target="_blank" class="col-12">
      <h3>Reporte de cargas en producción.</h3>
      <?php if (count($data) > 0): ?>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Folio del corte</th>
                <th>Carga o lavado</th>
                <th>Proceso abierto</th>
                <th>Ver detalles del corte</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $key => $value): ?>
                <tr>
                  <td>
                    <?php echo $value['folio'] ?>
                    <input type="hidden" name="folio[<?php echo $contador; ?>]" value="<?php echo $value['folio'] ?>">
                  </td>
                  <td>
                    <?php echo $value['lavado'] ?>
                    <input type="hidden" name="lavado[<?php echo $contador;?>]" value="<?php echo $value['lavado'] ?>">
                  </td>
                  <td>
                    <?php echo $value['proceso'] ?>
                    <input type="hidden" name="proceso[<?php echo $contador; $contador ++; ?>]" value="<?php echo $value['proceso'] ?>">
                  </td>
                  <td><a href="#" onclick="ver(<?php echo $value['folio']; ?>)" title="Ver los detalles del corte."><i class="fas fa-eye"></i></a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-danger" role="alert">
          No hay cargas.
        </div>
      <?php endif; ?>
      <div class="mx-auto">
        <button type="submit" class="btn btn-primary" title="Generar archivo PDF"><i class="far fa-file-pdf"></i></button>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th>Imágen</th>
                <td id="imagen"></td>
              </tr>
              <tr>
                <th>Folio</th>
                <td id="folio"></td>
              </tr>
              <tr>
                <th>Marca</th>
                <td id="marca"></td>
              </tr>
              <tr>
                <th>Maquilero</th>
                <td id="maquilero"></td>
              </tr>
              <tr>
                <th>Cliente</th>
                <td id="cliente"></td>
              </tr>
              <tr>
                <th>Tipo de pantalón</th>
                <td id="tipo"></td>
              </tr>
              <tr>
                <th>Fecha de entrada</th>
                <td id="fecha"></td>
              </tr>
              <tr>
                <th>Piezas</th>
                <td id="piezas"></td>
              </tr>
              <tr>
                <th>Ojales</th>
                <td id="ojales"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
