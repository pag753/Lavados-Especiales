<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#alerta').hide();
    $("#boton").click(function (){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/ajax/detalleCorte/") ?>",
            data: { "folio": $("#folio").val() },
            success: function(res) {
                if (res.folio == ''){
                    $('#alerta').html("El corte con folio "+$('#folio').val()+" no existe en la base de datos.");
                    $('#alerta').show();
                    alert("El folio con corte "+$('#folio').val()+" no existe en la base de datos.");
                }
                else
                    $("#formulario").submit();
            },
            dataType: "json"
        });
    });
});
</script>
<div class="container">
    <div class="table">
        <div class="row">
            <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
                <h3 class="white">Modificar corte</h3>
                <form action="<?php echo base_url('index.php/administracion/modificar'); ?>" method="get" enctype="multipart/form-data" name="formulario" id="formulario">
                    <div class="form-group row">
                        <label for="folio" class="col-3 col-form-label">Folio</label>
                        <div class="col-9">
                            <input type="number" name="folio" id="folio" class="form-control">
                        </div>
                    </div>
                    <center>
                        <button type="button" name="boton" id="boton" class="btn btn-primary">Aceptar</button>
                    </center>
                </form>
                <div class="alert alert-danger" role="alert" name="alerta" id="alerta">
                </div>
            </div>
        </div>
    </div>
</div>
