<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <script>
      $(document).ready(function(){
        $('#folio').keyup(function(){
          $.ajax({url: "<?php echo base_url() ?>index.php/ajax/salidaInterna/"+$('#folio').val(), success: function(result){
            $("#complemento").html(result);
          }});
        });
        $( "form" ).submit(function( event ){
          var suma=0;
          for (var i = 0; i < $('#cargas').val(); i++)
          suma+=parseInt($('#piezas_parcial'+i).val());
          suma+=parseInt($('#muestras').val());
          if(suma!=$('#piezas').val()){
            alert("La suma de las piezas y las muestras no son iguales que el total");
            return false;
          }
          else{
            var fechabd=$('#fechabd').val();
            var fecha=$('#fecha').val();
            var fbd=new Date(fechabd.split("-")[0],fechabd.split("-")[1],fechabd.split("-")[2]);
            var f=new Date(fecha.split("/")[2],fecha.split("/")[1],fecha.split("/")[0]);
            if(f>=fbd)
              return true;
            else{
              alert("La fecha que ingresó no puede ser anterior a la de su autorización");
              return false;
            }
          }
        });
      });
    </script>
    <?php
      $input_folio = array('name'    => 'folio',
                           'id'      => 'folio',
                           'type'    => 'text',
                           'class'   => 'bootstrap',);
    ?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <h3 class="black">Salida interna</h3>
            <form action="<?php echo base_url(); ?>index.php/Gestion/salidaInterna/" method="post" enctype="multipart/form-data">
              <table class="table table-striped" name="tabla" id="tabla">
                <caption>Ingrese los datos</caption>
                <thead>
                  <tr>
                    <th><center>Descripción</center></th>
                    <th><center>Valor</center></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Folio</td>
                    <td><input type="text" name="folio" id="folio" placeholder="Ingrese folio" required="true" /></td>
                  </tr>
                  <tr>
                    <td>Fecha</td>
                    <td><input type='date' name='fecha' id='fecha' readonly='true' class='tcal' value='<?php echo $fecha ?>'/></td>
                  </tr>
                </tbody>
              </table>
              <br />
              <div id="complemento" name="complemento">
              </div>
            </form>
            <!-- Holder for mobile navigation -->
          </div>
        </div>
      </div>
    </div>
  </header>
