<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
$input_folio = array('type'    => 'text',
                     'name'    => 'folio',
                     'id'      => 'folio',
                     'class'   => 'form-control',
                     'value'   => set_value('folio',@$folio),
                     'placeholder'=>'Ingrese número de folio',
                     );
?>
<script>
$(document).ready(function(){
  $('#folio').keyup(function(){
    $('#procesos').html('');
    $('#valida').html('');
    $.ajax({url: "<?php echo base_url() ?>index.php/ajax/operarioCargas/"+$('#folio').val(), success: function(result){
      $("#cargas").html(result);
      $( '#carga' ).change(function(){
        $('#valida').html('');
        $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos/'+$('#folio').val()+'_'+$('#carga').val(), success: function(result){
          $('#procesos').html(result);
          $( '#proceso' ).change(function(){
            $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioValida/'+$('#folio').val()+'_'+$('#carga').val()+'_'+$('#proceso').val(), success: function(result){
              $('#valida').html(result);
            }});
          });
        }});
      });
    }});
  });
  $( '#carga' ).change(function(){
    $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos/'+$('#folio').val()+'_'+$('#carga').val(), success: function(result){
      $('#procesos').html(result);
    }});
  });
  $( '#proceso' ).change(function(){
    $.ajax({url: '<?php echo base_url() ?>index.php/ajax/operarioValida/'+$('#folio').val()+'_'+$('#carga').val()+'_'+$('#proceso').val(), success: function(result){
      $('#valida').html(result);
    }});
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Información del corte</h3>
      <form action="<?php echo base_url(); ?>index.php/operario/alta" method="post" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <?php echo form_input($input_folio); ?>
          </div>
        </div>
        <div id="cargas" name="cargas" class="form-group row">
          <h5 class="col-12">Escriba el número de folio</h5>
        </div>
        <div id="procesos" name="procesos" class="form-group row">
        </div>
        <div id="valida" name="valida" class="form-group row">
        </div>
      </form>
    </div>
  </div>
</div>
