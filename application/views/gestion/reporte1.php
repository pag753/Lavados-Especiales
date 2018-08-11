<?php defined('BASEPATH') or exit('No direct script access allowed');
$extensiones = array(
  "jpg",
  "jpeg",
  "png"
);
?>
<div class="container-fluid">
  <div class="row">
    <form action="reporte1" method="post" target="_blank">
      <h3>Reporte de cortes en almacén de entrada.</h3>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Imágen</th>
              <th>Folio del corte</th>
              <th>Fecha de entrada</th>
              <th>Corte</th>
              <th>Marca</th>
              <th>Maquilero</th>
              <th>Cliente</th>
              <th>Tipo de pantalón</th>
              <th>Número de piezas</th>
              <th>Número de ojales</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $key => $value): ?>
              <?php
              $ban = false;
              foreach ($extensiones as $key2 => $extension)
              {
                $url = base_url() . "img/fotos/" . $value['folio'] . "." . $extension;
                $headers = get_headers($url);
                if (stripos($headers[0], "200 OK"))
                {
                  $ban = true;
                  $imagen = "<img src='" . base_url() . "img/fotos/" . $value['folio'] . "." . $extension . "' class='img-fluid' alt='Responsive image'>";
                  break;
                }
              }
              if (! $ban) $imagen = "No hay imágen";
              ?>
              <tr>
                <td>
                  <?php echo $imagen ?>
                </td>
                <td><?php echo $value['folio'] ?></td>
                <td>
                  <?php echo $value['fecha'] ?>
                  <input type="hidden" name="fecha[<?php echo $value['folio'] ?>]" value="<?php echo $value['fecha'] ?>">
                </td>
                <td>
                  <?php echo $value['corte'] ?>
                  <input type="hidden" name="corte[<?php echo $value['folio'] ?>]" value="<?php echo $value['corte'] ?>">
                </td>
                <td>
                  <?php echo $value['marca'] ?>
                  <input type="hidden" name="marca[<?php echo $value['folio'] ?>]" value="<?php echo $value['marca'] ?>">
                </td>
                <td>
                  <?php echo $value['maquilero'] ?>
                  <input type="hidden" name="maquilero[<?php echo $value['folio'] ?>]" value="<?php echo $value['maquilero'] ?>">
                </td>
                <td>
                  <?php echo $value['cliente'] ?>
                  <input type="hidden" name="cliente[<?php echo $value['folio'] ?>]" value="<?php echo $value['cliente'] ?>">
                </td>
                <td>
                  <?php echo $value['tipo'] ?>
                  <input type="hidden" name="tipo[<?php echo $value['folio'] ?>]" value="<?php echo $value['tipo'] ?>">
                </td>
                <td>
                  <?php echo $value['piezas'] ?>
                  <input type="hidden" name="piezas[<?php echo $value['folio'] ?>]" value="<?php echo $value['piezas'] ?>">
                </td>
                <td>
                  <?php echo $value['ojales'] ?>
                  <input type="hidden" name="ojales[<?php echo $value['folio'] ?>]" value="<?php echo $value['ojales'] ?>">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mx-auto">
        <button type="submit" class="btn btn-primary" title="Generar archivo PDF"><i class="far fa-file-pdf"></i></button>
      </div>
    </form>
  </div>
</div>
