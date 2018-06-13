<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script>
var ban=false;
$(document).ready(function() {
  $("#pass1").focus();
  $('input').keyup(function() {
    if ($('#pass2').val()==$( '#pass1' ).val()) {
      if ($( '#pass2' ).val() =='') $('#mensaje').html("<div class='alert alert-danger' role='alert'><strong>¡Error!</strong> Las contraseñas están vacías.</div>");
      else {
        $('#mensaje').html("<div class='alert alert-success' role='alert'><strong>¡Perfecto!</strong> Las contraseñas son iguales.</div>");
        ban=true;
      }
    }
    else {
      $('#mensaje').html("<div class='alert alert-danger' role='alert'><strong>¡Error!</strong> Las contraseñas no son iguales.</div>");
      ban=false;
    }
  });
  $("form").submit(function() {
    if (!ban) alert("¡Las contraseñas no son iguales! Favor de verificar.")
    return ban;
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="<?php echo $link ?>" method="post"
        enctype="multipart/form-data">
        <h1>Cambiar contraseña</h1>
        <div class="form-group row">
          <div class="input-group mb-2 col-12">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-lock"></i>
              </div>
            </div>
            <input type="password" name="pass1" id="pass1"
              placeholder="Escribe la contraseña" required
              class="form-control">
          </div>
        </div>
        <div class="form-group row">
          <div class="input-group mb-2 col-12">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-lock"></i>
              </div>
            </div>
            <input type="password" name="pass2" id="pass2"
              placeholder="Vuelve a escribir la contraseña" required
              class="form-control">
          </div>
        </div>
        <div id="mensaje"></div>
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-success" value="Aceptar">
            <i class="far fa-save"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
