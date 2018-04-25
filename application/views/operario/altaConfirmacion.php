<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(document).ready(function() {
  $("form").submit(function( event ) {
    var total=parseInt($("#total").val());
    var trabajadas=parseInt($("#piezas_trabajadas").val());
    var defectos=parseInt($("#defectos").val());
    if ($("#siguiente")!=null) {
      if (parseInt($("#siguiente").val())==-1) {
        alert("Seleccione una opción válida en siguiente");
        return false;
      }
      else return true;
    }
  });
  $('#especificos').DataTable({
    language: {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    },
    "lengthMenu": [ 5, 10, 20, 50, 100 ],
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-10 col-md-12 offset-lg-1">
      <div class="card">
        <div class="card-header">
          <strong>Datos Generales</strong>
        </div>
        <div class="card-body">
          <div class='table-responsive'>
            <table class="table table-bordered">
              <thead>
                <tr class="danger">
                  <th>Folio</th>
                  <th>Carga</th>
                  <th>Proceso</th>
                  <th>Trabajadas</th>
                  <th>Defectos</th>
                  <th>Registradas</th>
                  <th>Faltantes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $folio; ?></td>
                  <td><?php echo strtoupper($nombreCarga); ?></td>
                  <td><?php echo strtoupper($nombreProceso); ?></td>
                  <td><?php echo $trabajadas ?></td>
                  <td><?php echo $defectos ?></td>
                  <td><?php echo $piezas ?></td>
                  <td><?php echo $piezas-($trabajadas+$defectos); ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" href="#ver" role="button" aria-expanded="false" aria-controls="ver">
          <div class="card-header">
            <strong>Datos Específicos</strong>
          </div>
        </a>
        <div class="collapse" id="ver">
          <div class="card-body">
            <div class='table-responsive'>
              <table class="table table-bordered" name="especificos" id="especificos">
                <thead>
                  <tr class="danger">
                    <th>Empleado</th>
                    <th>Piezas</th>
                    <th>Defectos</th>
                    <th>Fecha</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($query as $key => $value): ?>
                    <tr>
                      <td><?php echo $value['usuario'] ?></td>
                      <td><?php echo $value['piezas'] ?></td>
                      <td><?php echo $value['defectos'] ?></td>
                      <td><?php echo $value['fecha'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php if ($trabajadas+$defectos==$piezas): ?>
        <div class="card">
          <?php if (count($faltantes)!=0): ?>
            <div class="card-header">
              <strong>Seleccione el siguiente proceso.</strong>
            </div>
          <?php else: ?>
            <div class="card-header">
              <strong>Cerrar el último proceso de la carga.</strong>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <form action="<?php echo base_url(); ?>index.php/operario/registro" method="post" enctype="multipart/form-data">
              <input type="hidden" name="proceso" id="proceso" value="<?php echo $proceso ?>"/>
              <input type="hidden" name="carga" id="carga" value="<?php echo $carga ?>"/>
              <input type="hidden" name="orden" id="orden" value="<?php echo $orden ?>"/>
              <input type="hidden" name="folio" id="folio" value="<?php echo $folio ?>">
              <input type="hidden" name="piezas_trabajadas" id="piezas_trabajadas" value="<?php echo $trabajadas; ?>">
              <input type="hidden" name="defectos" id="defectos" value="<?php echo $defectos ?>">
              <?php if (count($faltantes)!=0): ?>
                <div class="form-group row">
                  <select name='siguiente' id='siguiente' class="form-control">
                    <option value="-1">SELECCIONE UNA OPCIÓN</option>
                    <?php foreach ($faltantes as $key => $value): ?>
                      <option value="<?php echo $value['idproceso']?>"><?php echo strtoupper($value['proceso'])?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php endif; ?>
              <input type="submit" name="aceptar" id="aceptar" value="Aceptar" class="btn btn-primary"/>
            </form>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-danger" role="alert">
          La suma de las piezas de producción y defecectos no son iguales a las piezas registradas. Favor de revisar con los operarios.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
