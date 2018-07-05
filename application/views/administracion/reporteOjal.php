<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <form action="reporteOjal" method="get">
        <fieldset class="form-group">
          <h2>Generar reporte de cortes con ojal.</h2>
        </fieldset>
        <div class="form-group" id="porFechas">
          <table class="table">
            <tbody>
              <tr>
                <td>Fecha inicial</td>
                <td><input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" name="fechaInicial" id="fechaInicial"></td>
              </tr>
              <tr>
                <td>Fecha final</td>
                <td><input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" name="fechaFinal" id="fechaFinal"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mx-auto">
          <input type="submit" class="btn btn-primary" value="Generar" title="Generar reporte">
        </div>
      </form>
    </div>
  </div>
</diV>
