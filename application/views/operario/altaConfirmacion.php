<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
$input_folio = array('type'    => 'text',
                     'name'    => 'folio',
                     'id'      => 'folio',
                     'class'   => 'bootstrap',
                     'value'   => set_value('folio',@$folio),);
?>
<script>
$(document).ready(function(){
  $("form").submit(function( event ){
    var total=parseInt($("#total").val());
    var trabajadas=parseInt($("#piezas_trabajadas").val());
    var defectos=parseInt($("#defectos").val());
    if($("#siguente")!=null){
      if(parseInt($("#siguente").val())==-1){
        alert("Seleccione una opción válida en siguente");
        return false;
      }
      else
        return true;
    }
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Datos generales</h3>
      <form action="<?php echo base_url(); ?>index.php/operario/registro" method="post" enctype="multipart/form-data">
        <table class="table table-bordered">
          <tr class="danger">
            <th>Folio</th>
            <th>Carga</th>
            <th>Proceso</th>
            <th>Trabajadas</th>
            <th>Defectos</th>
            <th>Total</th>
          </tr>
          <tr>
            <th><?php echo $folio; ?></th>
            <th><?php echo strtoupper($nombreCarga); ?></th>
            <th><?php echo strtoupper($nombreProceso); ?></th>
            <th><?php echo $trabajadas ?></th>
            <th><?php echo $defectos ?></th>
            <th><?php echo $piezas ?></th>
          </tr>
        </table>
        <table>
          <?php if ($trabajadas+$defectos==$piezas): ?>
            <?php if (count($faltantes)!=0): ?>
              <tr>
                <th class="blue">Siguente:</th>
                <th>
                  <select name='siguente' id='siguente' class="form-control">
                    <option value="-1">SELECCIONE UNA OPCIÓN</option>";
                    <?php foreach ($faltantes as $key => $value): ?>
                      <option value="<?php echo $value['idproceso']?>"><?php echo strtoupper($value['proceso'])?></option>;
                    <?php endforeach; ?>
                  </select>
                </th>
              </tr>
            <?php endif; ?>
          <?php endif; ?>
        </table>
        <input type="hidden" name="proceso" id="proceso" value="<?php echo $proceso ?>"/>
        <input type="hidden" name="carga" id="carga" value="<?php echo $carga ?>"/>
        <input type="hidden" name="orden" id="orden" value="<?php echo $orden ?>"/>
        <?php if ($trabajadas+$defectos==$piezas): ?>
          <input type="submit" name="aceptar" id="aceptar" value="Aceptar"/>
        <?php else: ?>
          <h6 class="blue">La suma de las piezas de producción y defecectos no son iguales al total. Favor de revisar con los operarios</h6>
        <?php endif; ?>
        <h3>Para ver los datos epecíficos siga bajando</h3>
      </form>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Datos específicos</h3>
      <div class="form-group row">
        <table class="table table-bordered">
          <tr class="danger">
            <th>Empleado</th>
            <th>Piezas</th>
            <th>Defectos</th>
            <th>Fecha</th>
          </tr>
          <?php foreach ($query as $key => $value): ?>
            <tr>
              <th><?php echo $value['usuario'] ?></th>
              <th><?php echo $value['piezas'] ?></th>
              <th><?php echo $value['defectos'] ?></th>
              <th><?php echo $value['fecha'] ?></th>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>
