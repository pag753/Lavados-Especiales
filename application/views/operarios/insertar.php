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
  $(document).ready(function() {
    $("#folio").focus().keyup(function() {
      $.ajax({
        error: function(request, status, error){folio
          window.location.replace("<?php echo base_url() ?>");
        },
        type: "POST",
        url: "<?php echo base_url() ?>/index.php/ajax/operarioProcesos",
        data: { folio: $("#folio").val() },
        success: function(res) {
          if (res.datos[0] == '<') $('#mensaje').html(res.datos)
          else {
            console.log(res.datos);
            var cadena = "<h5>Seleccione el proceso en el que desea agregar la producción correspondiente del corte con folio " + $("#folio").val() + "</h5><div class='table-responsive'><table class='table table-striped'><thead><tr><th>Carga o lavado</th><th>Proceso seco</th><th>Insertar producción</th></tr></thead><tbody>";
            $.each(res.datos, function( index, value ) {
              cadena += "<tr><td>" + value.lavado + "</td><td>" + value.proceso + "</td><td><a title='Insertar producción en este proceso' href='insertar?f=" + $("#folio").val() + "&c=" + value.idcarga + "&p=" + value.idproceso + "&nc=" + value.lavado + "&np=" + value.proceso + "&il=" + value.idlavado + "'><i class='far fa-edit'></i></a></td></tr>"
            });
            cadena += "</tbody></table></div>";
            $('#mensaje').html(cadena);
          }
        },
        dataType: "json"
      });
    });
  });
  </script>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
        <h3>Alta de producción</h3>
        <div class="form-group row">
          <label for="folio" class="col-3 col-form-label">Folio</label>
          <div class="col-9">
            <?php echo form_input($input_folio); ?>
          </div>
        </div>
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
        <form action="<?php echo $url; ?>/1" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="folio" class="col-3 col-form-label">Folio</label>
            <div class="col-9">
              <input type="text" name="folio" readonly class="form-control" value="<?php echo $f ?>" />
            </div>
          </div>
          <div class="form-group row">
            <label for="nombreCarga" class="col-3 col-form-label">Carga</label>
            <div class="col-9">
              <input type="text" name="nombreCarga" readonly class="form-control" value="<?php echo strtoupper($nc) ?>" />
            </div>
          </div>
          <div class="form-group row">
            <label for="nombreProceso" class="col-3 col-form-label">Proceso:</label>
            <div class="col-9">
              <input type="text" name="nombreProceso" readonly class="form-control" value="<?php echo strtoupper($np) ?>" />
            </div>
          </div>
          <div class="form-group row">
            <label for="piezas" class="col-3 col-form-label">Piezas de producción:</label>
            <div class="col-9">
              <input type="number" name="piezas" id="piezas" required class="form-control" placeholder="Inserte el valor" autofocus value="<?php echo $piezas ?>" />
            </div>
          </div>
          <div class="form-group row">
            <label for="defectos" class="col-3 col-form-label">Defectos:</label>
            <div class="col-9">
              <input type="number" name="defectos" id="defectos" required class="form-control" placeholder="Inserte el valor" value="<?php echo $defectos ?>" />
            </div>
          </div>
          <input type="hidden" name="carga" id="carga" value="<?php echo $c ?>" />
          <input type="hidden" name="proceso" id="proceso" value="<?php echo $p ?>" />
          <input type="hidden" name="nuevo" id="nuevo" value="<?php echo $nuevo ?>" />
          <input type="hidden" name="idprod" id="idprod" value="<?php echo $idprod ?>" /> <input type="hidden" name="usuarioid" id="usuarioid" value="<?php echo $usuarioid ?>" />
          <input type="hidden" name="idlavado" id="idlavado" value="<?php echo $il ?>" />
          <input type="submit" value="Aceptar" class="btn btn-primary" />
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>
