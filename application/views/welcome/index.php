<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="row">
            <div class="col-md-12 text-center">
              <h3 class="light blue"><?php echo $texto1; ?></h3>
              <h1 class="blue typed"><?php echo $texto2; ?></h1>
              <span class="typed-cursor">|</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content modal-popup">
        <h3 class="white">Iniciar Sesión</h3>
        <?php
          $attributes = array('class' => 'popup-form');
          echo form_open('',$attributes);
        ?>
        <?php
          $data=array('type'        => 'text',
        				 			'class' 			=> 'form-control form-white',
        							'placeholder' => 'Usuario',
        							'name'    		=> 'nombre',
        							'id'       		=> 'nombre',
        							'required'		=> 'true');
        ?>
        <?php echo form_submit($data);?>
        <?php $data=array('type' 				=>  'password',
                          'class' 			=>  'form-control form-white',
                          'placeholder' =>  'Contraseña',
        									 'name'    		=>  'pass',
        									 'id'    			=>  'pass',
        									 'required'		=> 	'true');
        ?>
        <?php echo form_submit($data); ?>
        <?php $data=array('class' =>  'btn btn-submit',
        								  'value' =>  'Aceptar',
        								  'name'  =>  'boton',
        								  'id'    =>  'boton',); ?>
        <?php echo form_submit($data); ?>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
  <!-- Holder for mobile navigation -->
  <div class="mobile-nav">
    <ul>
    </ul>
    <a href="#" class="close-link"><i class="arrow_up"></i></a>
  </div>
