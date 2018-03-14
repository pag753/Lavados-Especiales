<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <?php
      $input_folio = array('name'    => 'folio',
                           'id'      => 'folio',
                           'readonly'=> 'true',
                           'type'    => 'text',
                           'class'   => 'bootstrap',
                           'value'   => $corte,);

      $input_fecha = array('name'    => 'fecha',
                           'id'      => 'fecha',
                           'type'    => 'text',
                           'class'   => 'tcal',
                           'value'   => set_value('fecha',date("d/m/Y")),
                           'readonly'=> 'true',);

      $input_corte = array('name'       => 'corte',
                           'id'         => 'corte',
                           'type'       => 'text',
                           'class'      => 'bootstrap',
                           'placeholder'=> 'Inserte corte',
                           'required'   => 'true');

      $input_piezas = array('name'      => 'piezas',
                           'id'         => 'piezas',
                           'type'       => 'number',
                           'class'      => 'bootstrap',
                           'placeholder'=> 'Inserte # de piezas',
                           'required'   => 'true');

      $input_imagen = array('name'      =>  'mi_imagen',
                           'id'         => 'mi_imagen',
                           'type'       => 'file',
                           'class'      => 'bootstrap',);

      foreach ($maquileros as $key => $value)
        $opciones_maquilero[$value['id']]=$value['nombre'];

        $select_maquilero=array('name'   => 'maquilero',
                                'id'     => 'maquilero',
                                'class'  => 'bootstrap',);

        $opciones_cliente[-1]="Seleccione el cliente";

        foreach ($clientes as $key => $value)
          $opciones_cliente[$value['id']]=$value['nombre'];

          $select_cliente=array('name'    => 'cliente',
                                  'id'    => 'cliente',
                                  'class' => 'bootstrap',);

          foreach ($tipos as $key => $value)
            $opciones_tipo[$value['id']]=$value['nombre'];

            $select_tipo=array('name'  => 'tipo',
                               'id'    => 'tipo',
                               'class' => 'bootstrap',);
    ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#cliente').change(function(){
          $.ajax({url: "<?php echo base_url() ?>index.php/ajax/gestionMarcas/"+$('#cliente').val(), success: function(result){
            $('#marcas').html(result);
          }});
        });
        $( "form" ).submit(function( event ){
          var val=$("#cliente").val();

          if(parseInt(val)==-1){
            alert("Por favor escoja un cliente");
            return false;
          }
          else
            return true;
        });
      });
    </script>

    <div class="table">
      <div class="header-text">
        <form class="form-inline" role='form' action="<?php echo base_url(); ?>index.php/Gestion/alta" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <h3 class="black">Alta de Corte</h3>
            <table class="table table-condensed">
              <caption>Ingrese los datos del nuevo corte</caption>
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
                  <td>Corte</td>
                  <td><?php echo form_input($input_corte); ?></td>
                </tr>
                <tr>
                  <td>Piezas</td>
                  <td><?php echo form_input($input_piezas); ?></td>
                </tr>
                <tr>
                  <td>Cliente</td>
                  <td><?php echo form_dropdown($select_cliente,$opciones_cliente,set_value('cliente',@$datos_corte[0]->cliente)); ?></td>
                </tr>
                <tr>
                  <td>Marca</td>
                  <td><div id='marcas' value='marcas'>Escoja primero el cliente</div></td>
                </tr>
                <tr>
                  <td>Maquilero</td>
                  <td><?php echo form_dropdown($select_maquilero,$opciones_maquilero,set_value('maquilero',@$datos_corte[0]->maquilero)); ?></td>
                </tr>
                <tr>
                  <td>Tipo</td>
                  <td><?php echo form_dropdown($select_tipo,$opciones_tipo,set_value('tipo',@$datos_corte[0]->tipo)); ?></td>
                </tr>
              </tbody>
            </table>
            <label>Imágen:</label>
            <?php echo form_input($input_imagen); ?>
            <input type="submit" class="bootstrap" value="Aceptar"/>
          </div>
        </form>
      </div>
    </div>
  </header>
