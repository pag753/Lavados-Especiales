<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
      <h3>Costo de ojal $</h3>
      <form action="ojal" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="costo" class="col-12 col-form-label">Costo actual</label>
          <div class="col-12">
            <input type="number" step="any" name="costo" id="costo" class="form-control" required placeholder="Escribe el costo de ojal" value="<?php echo $costo; ?>">
          </div>
        </div>
        <div >
          <center>
            <input type="submit" class="btn btn-primary" value="Cambiar"/>
          </center>
        </div>
      </form>
    </div>
  </div>
</div>
