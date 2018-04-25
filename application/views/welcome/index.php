<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$data=array(
  'type' => 'text',
  'class' => 'form-control',
  'placeholder' => 'Usuario',
  'name' => 'nombre',
  'id' => 'nombre',
  'required' => 'true'
);
$data1=array(
  'type' => 'password',
  'class' => 'form-control',
  'placeholder' => 'Contraseña',
  'name' => 'pass',
  'id' => 'pass',
  'required' => 'true'
);
$data2=array(
  'class' => 'btn btn-primary',
  'value' => 'Aceptar',
  'name' => 'boton',
  'id' => 'boton',
);
?>
<style media="screen">
.main-section{
  margin: 0 auto;
  margin-top:100px;
  background-color: #fff;
  border-radius: 5px;
  padding: 0px;
}
.user-img{
  margin-top:-50px;
}
.user-img img{
  height: 100px;
  width: 100px;
}
.user-name{
  margin:10px 0px;
}
.user-name h1{
  font-size:30px;
  color:#676363;
}
.user-name button{
  position: absolute;
  top:-50px;
  right:20px;
  font-size:30px;
}
.form-input button{
  width: 100%;
  margin-bottom: 20px;
}
.link-part{
  border-radius:0px 0px 5px 5px;
  background-color: #ECF0F1;
  padding:15px;
  border-top:1px solid #c2c2c2;
}
.open-modal{
  margin-top:100px !important;
}
</style>
<div class="jumbotron">
  <h1><?php echo $texto1; ?></h1>
  <h2><?php echo $texto2; ?></h2>
</div>
<div id="modal1" class="modal fade text-center">
  <div class="modal-dialog">
    <div class="col-lg-8 col-sm-8 col-12 main-section">
      <div class="modal-content">
        <div class="col-lg-12 col-sm-12 col-12 user-img">
          <img src="<?php echo base_url() ?>img/main.png">
        </div>
        <div class="col-lg-12 col-sm-12 col-12 user-name">
          <h1>Entrada de usuario</h1>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="col-lg-12 col-sm-12 col-12 form-input">
          <?php
          $attributes = array('class' => 'popup-form');
          echo form_open('',$attributes);
          ?>
          <div class="form-group">
            <?php echo form_submit($data);?>
          </div>
          <div class="form-group">
            <?php echo form_submit($data1); ?>
          </div>
          <?php echo form_submit($data2); ?>
          <?php echo form_close();?>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
</div>
