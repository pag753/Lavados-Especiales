<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$input_folio = array(
  'type' => 'number',
  'name' => 'folio',
  'id' => 'folio',
  'class' => 'form-control',
  'value' => set_value('folio',@$folio),
  'placeholder' => 'Ingrese número de folio',
);
?>
<script>
$(document).ready(function() {
  $('form').submit(function() {
    if ($('#folio').val() == "") {
      alert("Campo de folio vacío.");
      return false;
    }
    else {
      if ($('#carga').val() == -1) {
        alert('Favor de escoger la carga');
        return false;
      }
      else {
        if ($('#proceso').val() == -1 || !$('#proceso').length) {
          alert('Valor de proceso inválido');
          return false;
        }
        else return true;
      }
    }
  });
  $("#folio").focus().keyup(function() {
    $('#procesos').html('');
    $('#valida').html('');
    $.ajax({
      url: "<?php echo base_url() ?>index.php/ajax/operarioCargas",
      data: { folio: $('#folio').val() },
      dataType: 'text',
      type: 'POST',
      success: function(result) {
        $("#cargas").html(result);
        $( '#carga' ).change(function() {
          $('#valida').html('');
          $.ajax({
            url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos',
            data: { folio: $('#folio').val(), carga: $('#carga').val() },
            dataType: 'text',
            type: 'POST',
            success: function(result){
              $('#procesos').html(result);
              $( '#proceso' ).change(function() {
                $.ajax({
                  url: '<?php echo base_url() ?>index.php/ajax/operarioValida',
                  data: { folio: $('#folio').val(), carga: $('#carga').val(), proceso: $('#proceso').val() },
                  type: "POST",
                  dataType: "text",
                  success: function(result) {
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
  $('#carga').change(function(){
    $.ajax({
      url: '<?php echo base_url() ?>index.php/ajax/operarioProcesos',
      data: { folio: $('#folio').val(), carga: $('#carga').val() },
      dataType: "text",
      type: "POST",
      success: function(result) {
        $('#procesos').html(result);
      }
    });
  });
  $('#proceso').change(function() {
    $.ajax({
      url: '<?php echo base_url() ?>index.php/ajax/operarioValida',
      data: { folio: $('#folio').val(), carga: $('#carga').val(), proceso: $('#proceso').val() },
      dataType: 'text',
      type: "POST",
      success: function(result) {
        $('#valida').html(result);
      }
    });
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Cerrar proceso</h3>
      <form action="alta" method="post" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <?php echo form_input($input_folio); ?>
          </div>
        </div>
        <div id="cargas" name="cargas" class="form-group row">
          <div class="col-12">
            <div class="alert alert-info" role="alert">
              Escriba el número de folio.
            </div>
          </div>
        </div>
        <div id="procesos" name="procesos" class="form-group row">
        </div>
        <div id="valida" name="valida" class="form-group row">
        </div>
      </form>
    </div>
  </div>
</div>
