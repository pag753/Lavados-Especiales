<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (! $this->input->get()): ?>
  <?php
  $input_folio = array(
    'type' => 'number',
    'name' => 'folio',
    'id' => 'folio',
    'class' => 'form-control',
    'value' => set_value('folio', @$f),
    'placeholder' => 'Inserte número de folio'
  );
  ?>
  <script type="text/javascript">
  function procesos(folio) {
    $.ajax({
      error: function(request, status, error){folio
        window.location.replace("<?php echo base_url() ?>");
        //console.log(request.responseText);
      },
      type: "POST",
      url: "<?php echo base_url() ?>/index.php/ajax/operarioProcesos",
      data: { folio: folio },
      success: function(res) {
        if (res.datos[0] == '<') $('#mensaje').html(res.datos)
        else {
          $("#marca").html(res.corte[0].marca);
          $("#cliente").html(res.corte[0].cliente);
          $("#piezas").html(res.corte[0].piezas);
          //console.log(res);
          var cadena = "<p class='text-primary'>Seleccione el proceso en el que desea agregar la producción correspondiente del corte con folio " + $("#folio").val() + "</p><div class='table-responsive'><table class='table table-striped'><thead><tr><th># Carga</th><th>Tipo</th><th>Color de hilo</th><th>Lavado</th><th>Proceso seco</th><th>Insertar producción</th></tr></thead><tbody>";
          $.each(res.datos, function( index, value ) {
            cadena += "<tr><td>" + value.id_carga + "</td><td>" + value.tipo + "</td><td>" + value.color_hilo + "</td><td>" + value.lavado + "</td><td>" + value.proceso + "</td><td><a title='Insertar producción en este proceso' href='insertar?id=" + value.id + "&f=" + $("#folio").val() + "&c=" + value.id_carga + "&p=" + value.idproceso + "&nc=" + value.lavado + "&np=" + value.proceso + "&il=" + value.idlavado + "&m=" + res.corte[0].marca + "&cl=" + res.corte[0].cliente + "&pie=" + res.corte[0].piezas + "&color_hilo=" + value.color_hilo + "&tipo=" + value.tipo + "'><i class='far fa-edit'></i></a></td></tr>"
          });
          cadena += "</tbody></table></div>";
          $('#mensaje').html(cadena);
        }
      },
      dataType: "json"
    });
  }
  $(document).ready(function() {
    <?php if (isset($f)): ?>
      procesos(<?php echo $f; ?>);
    <?php else: ?>
      procesos($("#folio").val());
    <?php endif; ?>
    $("#folio").focus().keyup(function() {
      $("#marca").html('');
      $("#cliente").html('');
      $("#piezas").html('');
      procesos($("#folio").val());
    });
  });
  </script>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <h3>Alta de producción</h3>
        <table class="table table-striped" style="background-color: rgba(255,255,255,.8)">
          <tr>
            <th>Folio</th>
            <td><?php echo form_input($input_folio); ?></td>
          </tr>
          <tr>
            <th>Marca</th>
            <td id="marca"></td>
          </tr>
          <tr>
            <th>Cliente</th>
            <td id="cliente"></td>
          </tr>
          <tr>
            <th># Piezas del corte</th>
            <td id="piezas"></td>
          </tr>
        </table>
        <div id="mensaje" style="background-color: rgba(255,255,255,.8)">
        </div>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
        <h3>Alta de producción</h3>
        <form action="<?php echo $url . "/" . $this->input->get()['f']; ?>" method="post" enctype="multipart/form-data">
          <table class="table table-striped">
            <tr>
              <th>Folio</th>
              <td><?php echo $this->input->get()['f'] ?></td>
            </tr>
            <tr>
              <th>Marca</th>
              <td><?php echo $this->input->get()['m'] ?></td>
            </tr>
            <tr>
              <th>Cliente</th>
              <td><?php echo $this->input->get()['cl'] ?></td>
            </tr>
            <tr>
              <th># piezas del corte</th>
              <td><?php echo $this->input->get()['pie'] ?></td>
            </tr>
            <tr>
              <th>Color de hilo</th>
              <td><?php echo $this->input->get()['color_hilo'] ?></td>
            </tr>
            <tr>
              <th>Tipo</th>
              <td><?php echo $this->input->get()['tipo'] ?></td>
            </tr>
            <tr>
              <th># carga</th>
              <td><?php echo $this->input->get()['c'] ?></td>
            </tr>
            <tr>
              <th>Lavado</th>
              <td><?php echo strtoupper($nc) ?></td>
            </tr>
            <tr>
              <th>Proceso</th>
              <td><?php echo strtoupper($np) ?></td>
            </tr>
            <tr>
              <th>Piezas de producción</th>
              <th><input type="number" name="piezas" id="piezas" required class="form-control" placeholder="Inserte el valor" autofocus value="<?php echo $piezas ?>" /></th>
            </tr>
            <tr>
              <th>Defectos</th>
              <td><input type="number" name="defectos" id="defectos" required class="form-control" placeholder="Inserte el valor" value="<?php echo $defectos ?>" /></td>
            </tr>
          </table>
          <input type="hidden" name="id" value="<?php echo $this->input->get()['id'] ?>">
          <input type="hidden" name="nuevo" id="nuevo" value="<?php echo $nuevo ?>" />
          <input type="submit" value="Aceptar" class="btn btn-primary" />
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>
