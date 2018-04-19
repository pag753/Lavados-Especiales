<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if(!isset($folio)): ?>
<?php
$input_folio = array('type'       => 'text',
                     'name'       => 'folio',
                     'id'         => 'folio',
                     'class'      => 'form-control',
                     'value'      => set_value('folio',@$folio),
                     'placeholder'=>'Inserte número de folio',
                     );
?>
<script>
$(document).ready(function(){
  $('#folio').keyup(function(){
    $('#procesos').html('');
    $('#valida').html('');
    $.ajax({
      url: "<?php echo base_url() ?>index.php/ajax/operarioCargas",
      data : { folio : $('#folio').val() },
      dataType : 'text',
      type : "POST",
      success: function(result){
        $("#cargas").html(result);
        $( '#carga' ).change(function(){
          $('#valida').html('');
          $.ajax({
            url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos',
            data : { folio : $('#folio').val() , carga : $('#carga').val() },
            dataType : 'text',
            type : 'POST',
            success: function(result){
              $('#procesos').html(result);
              $( '#proceso' ).change(function(){
                $.ajax({
                  url: '<?php echo base_url() ?>index.php/ajax/operarioValida',
                  data : { folio : $('#folio').val() , carga : $('#carga').val() , proceso : $('#proceso').val() },
                  dataType : 'text',
                  type : 'POST',
                  success: function(result){
                    $('#valida').html(result);
                  }
                });
              });
            }
          });
        });
      }
    });
  });
  $( '#carga' ).change(function(){
    $.ajax({
      url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos',
      data : { folio : $('#folio').val() , carga : $('#carga').val() },
      dataType : 'text',
      type : 'POST',
      success: function(result){
        $('#procesos').html(result);
      }
    });
  });
  $( '#proceso' ).change(function(){
    $.ajax({
      url: '<?php echo base_url() ?>index.php/ajax/operarioValida',
      data : { folio : $('#folio').val() , carga : $('#carga').val() , proceso : $('#proceso').val() },
      dataType : 'text',
      type : 'POST',
      success: function(result){
        $('#valida').html(result);
      }
    });
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Alta de producción</h3>
      <form action="<?php $url; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <?php echo form_input($input_folio); ?>
          </div>
        </div>
        <div id="cargas" name="cargas" class="form-group row">
        </div>
        <div id="procesos" name="procesos" class="form-group row">
        </div>
        <div id="valida" name="valida" class="form-group row">
        </div>
      </form>
    </div>
  </div>
</div>
<?php else: ?>.
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Alta de producción</h3>
      <form action="<?php echo $url; ?>/1" method="post" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <input type="text" name="folio" readonly="true" class="form-control" value="<?php echo $folio ?>"/>
          </div>
        </div>
        <div class="form-group row">
          <label for="nombreCarga" class="col-3 col-form-label">Carga</label>
          <div class="col-9">
            <input type="text" name="nombreCarga" readonly="true" class="form-control" value="<?php echo strtoupper($nombreCarga) ?>"/>
          </div>
        </div>
        <div class="form-group row">
          <label for="nombreProceso" class="col-3 col-form-label">Proceso:</label>
          <div class="col-9">
            <input type="text" name="nombreProceso" readonly="true" class="form-control" value="<?php echo strtoupper($nombreProceso) ?>"/>
          </div>
        </div>
        <div class="form-group row">
          <label for="piezas" class="col-3 col-form-label">Piezas de producción:</label>
          <div class="col-9">
            <input type="number" name="piezas"  id="piezas"  required="true" class="form-control" placeholder="Inserte el valor" value="<?php echo $piezas ?>"/>
          </div>
        </div>
        <div class="form-group row">
          <label for="defectos" class="col-3 col-form-label">Defectos:</label>
          <div class="col-9">
            <input type="number" name="defectos" id="defectos"  required="true" class="form-control" placeholder="Inserte el valor" value="<?php echo $defectos ?>"/>
          </div>
        </div>
        <input type="hidden" name="carga" id="carga" value="<?php echo $carga ?>"/>
        <input type="hidden" name="proceso" id="proceso" value="<?php echo $proceso ?>"/>
        <input type="hidden" name="nuevo" id="nuevo" value="<?php echo $nuevo ?>"/>
        <input type="hidden" name="idprod" id="idprod" value="<?php echo $idprod ?>"/>
        <input type="hidden" name="usuarioid" id="usuarioid" value="<?php echo $usuarioid ?>"/>
        <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $idlavado ?>"/>
        <input type="submit" value="Aceptar" class="btn btn-primary"/>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
