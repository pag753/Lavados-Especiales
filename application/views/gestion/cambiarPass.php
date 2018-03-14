<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <script>
      var ban=false;
      $(document).ready(function(){
        $('input').keyup(function(){
          if($('#pass2').val()==$('#pass1').val()){
            ban=true;
            $('#mensaje').html("La contraseña son iguales");
          }
          else{
            ban=false;
            $('#mensaje').html("Las contraseñas no son iguales");
          }
          if(ban)
            $('#mensaje').css("color","blue");
            else
              $('#mensaje').css("color","red");
        });
        $( "form" ).submit(function(){
            return ban;
        });
      });
    </script>
    <?php
      $link=base_url().'index.php/welcome/cambiarPass';
    ?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <form action="<?php echo $link ?>" method="post" enctype="multipart/form-data">
                <center>
                  <h3>Cambiar contraseña</h3>
                  <table class="table table-striped">
                    <caption>Llene los datos</caption>
                    <thead>
                      <tr>
                        <th>Descripción</th>
                        <th>Valor</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Contraseña nueva</td>
                        <td><input type="password" name="pass1" id="pass1" required="true"></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Repetir contraseña</td>
                        <td><input type="password" name="pass2" id="pass2" required="true"></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                  <h3 name="mensaje" id="mensaje"></h3>
                  <input type="submit" name="Aceptar" value="Aceptar">
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
