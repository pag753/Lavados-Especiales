<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="container">
      <div class="table">
        <div class="header-text">
          <div class="modal-dialog">
            <div class="modal-content modal-popup">
              <h3 class="white">Catálogos</h3>
              <h4 class="white">
                <?php
                  switch ($id)
                  {
                    case 1:
                    echo 'Cliente';
                    break;

                    case 2:
                    echo "Lavado";
                    break;

                    case 3:
                    echo "Maquilero";
                    break;

                    case 4:
                    echo "Marca";
                    break;

                    case 5:
                    echo "Proceso Seco";
                    break;

                    case 6:
                    echo "Usuarios";
                    break;

                    case 7:
                    echo "Tipos de pantalón";
                    break;
                  }
                ?>
              </h4>
              <form action="<?php echo base_url(); ?>index.php/administracion/catalogos" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo $id?>"/>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th><center>Nombre</center></th>
                      <th><center>Editar</center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select name="nombre">
                          <?php foreach ($data as $key => $value): ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo strtoupper($value['nombre']); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td>
                        <input type="submit" value="Editar" id="Editar" name="Boton"/>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <center>
                  <input type="submit" value="Nuevo" id="Nuevo" name="Boton"/>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
