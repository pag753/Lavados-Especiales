<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/javascript">
$('#document').ready(function() {
  $('#porFolios').hide();
  $('#optionsRadios1').click(function() {
    $('#porFechas').show(800);
    $('#porFolios').hide(800);
  });
  $('#optionsRadios2').click(function() {
    $('#porFechas').hide(800);
    $('#porFolios').show(800);
  });
});
</script>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <form action="nuevaNomina" method="post">
        <fieldset class="form-group">
          <h2>Generar nómina</h2>
          <div class="form-check">
            <label class="form-check-label"> <input type="radio"
              class="form-check-input" name="optionsRadios"
              id="optionsRadios1" value="option1" checked> Generar por
              fechas.
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label"> <input type="radio"
              class="form-check-input" name="optionsRadios"
              id="optionsRadios2" value="option2"> Generar por folios de
              corte.
            </label>
          </div>
        </fieldset>
        <div class="form-group" id="porFechas">
          <table class="table">
            <tbody>
              <tr>
                <td>Fecha inicial</td>
                <td><input class="form-control" type="date"
                  value="<?php echo date('Y-m-d'); ?>"
                  name="fechaInicial" id="fechaInicial"></td>
              </tr>
              <tr>
                <td>Fecha final</td>
                <td><input class="form-control" type="date"
                  value="<?php echo date('Y-m-d'); ?>" name="fechaFinal"
                  id="fechaFinal"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="form-group" id="porFolios">
          <table class="table">
            <tbody>
              <tr>
                <td>Folios</td>
                <td><textarea
                    placeholder="Escribe los folios separados por comas. Ej.: 1,3,6,9,7,41,100"
                    name="folios" id="folios" class="form-control"></textarea></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mx-auto">
          <input type="submit" class="btn btn-primary" value="Generar">
        </div>
      </form>
    </div>
  </div>
</diV>
