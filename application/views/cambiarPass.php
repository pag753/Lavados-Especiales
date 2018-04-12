<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
var ban=false;
$(document).ready(function(){
  $('input').keyup(function(){
    if($('#pass2').val()==$('#pass1').val()){
      ban=true;
      $('#mensaje').html("Las contraseñas son iguales");
    }
    else{
      ban=false;
      $('#mensaje').html("Las contraseñas no son iguales");
    }
    if(ban)
      $('#mensaje').css("color","green");
    else
    $('#mensaje').css("color","red");
  });
  $( "form" ).submit(function(){
    return ban;
  });
});
</script>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <form action="<?php echo $link ?>" method="post" enctype="multipart/form-data">
        <h1>Cambiar contraseña</h1>
        <div class="form-group row">
          <label for="pass1" class="col-3 col-form-label">Contraseña nueva</label>
          <div class="col-9">
            <input type="password" name="pass1" id="pass1" placeholder="Escribe la contraseña" required class="form-control">
          </div>
        </div>
        <div class="form-group row">
          <label for="pass2" class="col-3 col-form-label">Repetir Contraseña</label>
          <div class="col-9">
            <input type="password" name="pass2" id="pass2" placeholder="Vuelve a escribir la contraseña" required class="form-control">
          </div>
        </div>
        <div class="col-12">
          <div class="form-group row">
            <h3 name="mensaje" id="mensaje"></h3>
          </div>
        </div>
        <div class="offset-sm-2 col-sm-10">
          <input type="submit" class="btn btn-primary" value="Aceptar"/>
        </div>
      </form>
    </div>
  </div>
</div>
