<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
      $(document).ready(function(){
        $('#folio').keyup(function(){
          $.ajax({url: "<?php echo base_url() ?>index.php/ajax/autorizacionCorte/"+$('#folio').val(), success: function(result){
            $("#respuesta").html(result);
          }});
        });
        $( "form" ).on( "click", "button", function(){
          if(this.name=="boton"){
            var numero=$( "#numero" );
            $.ajax({url: "<?php echo base_url() ?>index.php/ajax/agregarRenglonProduccion/"+numero.val(), success: function(result){
              $( '#tabla tbody' ).append(result);
              numero.val(parseInt(numero.val())+1);
            }});
          }
          else{
            if(this.id.substring(0,8)=="eliminar"){
              var renglon=this.id.substring(8);
              $( "#renglon"+renglon ).remove();
              var numero=$( "#numero" );
              numero.val(parseInt(numero.val())-1);
            }
          }
        });
      });
    </script>
    <?php
      $input_folio = array('name'       => 'folio',
                           'id'         => 'folio',
                           'type'       => 'text',
                           'class'      => 'bootstrap',
                           'placeholder'=> 'Ingresa folio de corte',
                           'value'      => set_value('folio',@$datos_corte->folio),
                           'required'   => 'true');

      $input_fecha = array('name'    => 'fecha',
                           'id'      => 'fecha',
                           'type'    => 'text',
                           'class'   => 'tcal',
                           'value'   => set_value('fecha',date("d/m/Y"),@$datos_corte->fecha),
                           'readonly'=> 'true',
                        'required'   => 'true');
    ?>
    <div class="container">el folio y fecha de autorización
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <form class="form-inline" action="<?php echo base_url(); ?>index.php/produccion/autorizar" method="post">
              <input type="hidden" name="numero" id="numero" value="0">
              <h3 class="black"><?php echo $texto1; ?></h3>
              <table class="table table-striped" name="tabla" id="tabla">
                <caption><?php echo $texto2; ?></caption>
                <thead>
                  <tr>
                    <th><center>Descripción</center></th>
                    <th><center>Valor</center></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Folio</td>
                    <td><?php echo form_input($input_folio); ?></td>
                  </tr>
                  <tr>
                    <td>Fecha</td>
                    <td><?php echo form_input($input_fecha); ?></td>
                  </tr>
                  <tr>
                    <th><center>Lavado</center></th>
                    <th><center>Proceso seco</center></th>
                    <th><center>Eliminar</center></th>
                  </tr>
                </tbody>
              </table>
              <button type="button" name="boton" id="boton">Agregar Lavado</button>
              <br />
              <br>
              <div name='respuesta' id='respuesta'>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
