<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
* Clase en la que se tiene acceso por medio del método AJAX por JQUERY.
*/
class Ajax extends CI_Controller
{
  /*
  * Constructor de la clase
  */
  public function __construct()
  {
    parent::__construct();
    if (! isset($_SESSION['id'])) $this->output->set_status_header('404');
  }

  // ROOT
  public function rootReporte($folio = null)
  {
    if ($folio == null) echo "<div class='alert alert-info' role='alert'>Favor de insertar el número de folio.</div>";
    else
    {
      if ($this->existeCorte($folio)) echo "<input type='submit' value='aceptar'/>";
      else echo "<div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div>";
    }
  }

  public function rootCargas($folio = null)
  {
    if ($folio != null)
    {
      if ($this->existeCorte($folio))
      {
        $this->load->model('corteAutorizadoDatos');
        $query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
        if (count($query) == 0) echo "<div class='alert alert-info' role='alert'>Corte aún no autorizado.</div>";
        else
        {
          echo "<label>Carga: </label><select name='carga' id='carga'><option value=-1>Seleccione la carga</option>";
          foreach ($query as $key => $value) echo "<option value=" . ($key + 1) . ">" . strtoupper($value['lavado']) . "</option>";
          echo "</select><br />";
        }
      }
      else echo "<div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div>";
    }
  }

  public function rootValida($cadena = null)
  {
    $carga = explode("_", $cadena)[1];
    if ($carga == - 1) echo "<div class='alert alert-warning' role='alert'>Seleccione un opción válida.</div>";
    else echo "<input type='submit' name='orden' id='orden' value='Aceptar'/>";
  }

  public function agregarRenglon($numero)
  {
    $this->load->model('Marca');
    $marcas = $this->Marca->get();
    echo "<tr id='renglon$numero' name='renglon$numero'><td>Marca</td><td><select id='marca[$numero]' name='marca[$numero]'>";
    foreach ($marcas as $value) echo "<option value='" . $value['id'] . "'>" . $value['nombre'] . "</option>";
    echo "</td><td><button type='button' name='eliminar$numero' id='eliminar$numero'>Eliminar</button></td></tr>";
  }

  // GESTIÓN
  /*
  * Método que proporciona las marcas.
  */
  public function gestionMarcas()
  {
    if (!in_array($_SESSION['id'],array(1,2)) || !$this->input->post()) $this->output->set_status_header('404');
    $cliente = $this->input->post()["cliente"];
    if ($cliente == - 1) echo "<div class='alert alert-warning' role='alert'>Escoja primero el cliente.</div>";
    else
    {
      $this->load->model('marca');
      $query = $this->marca->getByCliente($cliente);
      echo "<select class='form-control' name='marca' id='marca'>";
      if ($query == null) echo "<option value='0'>Ninguna</option>";
      else foreach ($query as $value) echo "<option value='" . $value['marcaId'] . "'>" . $value['marcaNombre'] . "</option>";
      echo "</select>";
    }
  }

  public function salidaInterna()
  {
    if (!in_array($_SESSION['id'],array(1,2,8)) || !$this->input->post() || ! isset($this->input->post()["folio"])) $this->output->set_status_header('404');
    else
    {
      $folio = $this->input->post()["folio"];
      if ($folio == null)
      {
        $info = array(
          'respuesta' => utf8_encode("<div class='alert alert-info' role='alert'>Ingresa el número de folio.</div>"),
          'info' => ''
        );
        $info = json_encode($info);
        echo $info;
      }
      else
      {
        $this->load->model('corte');
        $query2 = $this->corte->getByFolio($folio);
        if (count($query2) == 0)
        {
          $info = array(
            'respuesta' => utf8_encode("<div class='alert alert-warning' role='alert'>El corte no está en la base de datos.</div>"),
            'info' => ''
          );
          $info = json_encode($info);
          echo $info;
        }
        else
        {
          $this->load->model('corteAutorizado');
          $query = $this->corteAutorizado->getByFolio($folio);
          if (count($query) == 0)
          {
            $info = array(
              'respuesta' => utf8_encode("<div class='alert alert-warning' role='alert'>El corte no se ha autorizado.</div>"),
              'info' => ''
            );
            $info = json_encode($info);
            echo $info;
          }
          else
          {
            $this->load->model('salidaInterna1');
            $query = $this->salidaInterna1->getByFolio($folio);
            if (count($query) != 0)
            {
              $info = array(
                'respuesta' => utf8_encode("<div class='alert alert-warning' role='alert'>El corte ya tiene salida interna.</div>"),
                'info' => ''
              );
              $info = json_encode($info);
              echo $info;
            }
            else
            {
              $cadena = "<div class='form-group row'><label for='Piezas' class='col-3 col-form-label'>Piezas</label><div class='col-9'><input type='number' name='piezas' id='piezas' readonly='true' class='form-control' value='" . $query2[0]['piezas'] . "'></input></div></div><div class='form-group row'><label for='Muestras' class='col-3 col-form-label'>Muestras</label><div class='col-9'><input type='number' required='true' name='muestras' id='muestras' placeholder='Inserte muestras' class='form-control'></input></div></div><div class='form-group row'><div class='col-12'><div class='table-responsive'><table name='tabla' id='tabla' class='table'><thead><tr><th># Carga</th><th>Lavado</th><th>Color de hilo</th><th>Tipo</th><th>Piezas</th></tr></thead><tbody>";
              $this->load->model('corteAutorizadoDatos');
              $autorizado = $this->corteAutorizado->getCargas($folio);
              foreach ($autorizado as $key => $value)
              {
                $cadena .= "<tr><td>" . $value['id_carga'] . "<td>" . strtoupper($value['lavado']) . "</td><td>" . $value['color_hilo'] . "</td><td>" . $value['tipo'] . "</td><td><input type='number' name='piezas_parcial[" . $value['id'] . "]' id='piezas_parcial" . ($key + 1) . "' class='form-control' placeholder='Inserte # de piezas' required='true' class='form-control'/></td>";
                $query10 = $this->corteAutorizadoDatos->getProcesosSinCeros($value['id']);
                //$cadena .= "<td><select required name='primero[" . ($value['id']) . "]' class='form-control'>";
                //foreach ($query10 as $key => $value) $cadena .= "<option value='" . $value['id'] . "'>" . $value['proceso'] . "</option>";
                //$cadena .= "</select></td></tr>";
                $cadena .= "</tr>";
              }
              $cadena .= "</tbody></table></div><div class='col-6'><input type='submit' class='btn btn-primary' value='Aceptar'/></div><input type='hidden' name='fechabd' id='fechabd' value='" . $query2[0]['fecha_entrada'] . "'/><input type='hidden' name='cargas' id='cargas' value='" . count($autorizado) . "'/></div></div>";
              $infoCorte = $this->infoCorte($folio);
              $info = array(
                'respuesta' => utf8_encode($cadena),
                'info' => $infoCorte
              );
              $info = json_encode($info);
              echo $info;
            }
          }
        }
      }
    }
  }

  public function salidaAlmacen()
  {
    if (!in_array($_SESSION['id'],array(1,2)) || !$this->input->post() || !isset($this->input->post()["folio"]))
    $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    // Verificar si el corte es nulo
    if ($folio == "")
    {
      $info = array(
        'respuesta' => "<div class='alert alert-info' role='alert'>Ingresa el número de folio.</div>",
        'info' => ''
      );
      $info = json_encode($info);
      echo $info;
    }
    else
    {
      // Verificar si el corte existe en la base de datos
      if (! $this->existeCorte($folio))
      {
        $info = array(
          'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio " . $folio . " no existe en la base de datos.</div>",
          'info' => ''
        );
        $info = json_encode($info);
        echo $info;
      }
      else
      {
        // Verificar si el corte está autorizado
        $this->load->model('corteAutorizado');
        $query = $this->corteAutorizado->getByFolio($folio);
        if (count($query) == 0)
        {
          $info = array(
            'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio " . $folio . " no está autorizado.</div>",
            'info' => ''
          );
          $info = json_encode($info);
          echo $info;
        }
        else
        {
          // Verificar si el corte tiene salida interna
          $this->load->model('salidaInterna1');
          $query = $this->salidaInterna1->getByFolio($folio);
          if (count($query) == 0)
          {
            $info = array(
              'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio " . $folio . " no tiene salida interna.</div>",
              'info' => ''
            );
            $info = json_encode($info);
            echo $info;
          }
          else
          {
            $this->load->model(array('corteAutorizadoDatos','entregaAlmacen'));
            $query = $this->corteAutorizadoDatos->getByFolioEspecifico2($folio);
            //foreach ($query as $key => $value) if (count($this->entregaAlmacen->existe($value['id_corte_autorizado'])) > 0) unset($query[$key]);
            $datos = $this->infoCorte($folio);
            $info = array(
              'respuesta' => $query,
              'info' => $datos
            );
            $info = json_encode($info);
            echo $info;
          }
        }
      }
    }
  }

  public function salidaExterna()
  {
    if (!in_array($_SESSION['id'],array(1,2)) || ! $this->input->post())
    $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    // Verificar si el corte es nulo
    if ($folio == "")
    {
      $info = array(
        'respuesta' => "<div class='alert alert-info' role='alert'>Ingresa el número de folio.</div>",
        'info' => ''
      );
      $info = json_encode($info);
      echo $info;
    }
    else
    {
      // Verificar si el corte existe en la base de datos
      if (!$this->existeCorte($folio))
      {
        $info = array(
          'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio " . $folio . " no existe en la base de datos.</div>",
          'info' => ''
        );
        $info = json_encode($info);
        echo $info;
      }
      else
      {
        // Verificar si el corte está autorizado
        $this->load->model('corteAutorizado');
        $query = $this->corteAutorizado->getByFolio($folio);
        if (count($query) == 0)
        {
          $info = array(
            'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio " . $folio . " no está autorizado.</div>",
            'info' => ''
          );
          $info = json_encode($info);
          echo $info;
        }
        else
        {
          // Verificar si el corte tiene salida interna
          $this->load->model('salidaInterna1');
          $query = $this->salidaInterna1->getByFolio($folio);
          if (count($query) == 0)
          {
            $info = array(
              'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio " . $folio . " no tiene salida interna.</div>",
              'info' => ''
            );
            $info = json_encode($info);
            echo $info;
          }
          else
          {
            $this->load->model('corteAutorizadoDatos');
            $query = $this->corteAutorizadoDatos->getByFolioEspecifico3($folio);
            //foreach ($query as $key => $value) if (count($this->entregaExterna->existe($folio,$value['lavadoid'])) > 0) unset($query[$key]);
            $infoCorte = $this->infoCorte($folio);
            $info = array(
              'respuesta' => $query,
              'info' => $infoCorte
            );
            $info = json_encode($info);
            echo $info;
          }
        }
      }
    }
  }

  // PRODUCCIÓN
  public function autorizacionCorte($folio = null)
  {
    if (!in_array($_SESSION['id'],array(1,2,3)) || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    if ($folio == null)
    {
      $info = array(
        'respuesta' => utf8_encode("<div class='alert alert-info' role='alert'>Escriba el número del corte.</div>"),
        'info' => ''
      );
      $info = json_encode($info);
      echo $info;
    }
    else
    {
      $this->load->model('corte');
      $resultado = $this->corte->getByFolio($folio);
      if (count($resultado) == 0)
      {
        $info = array(
          'respuesta' => "<div class='alert alert-info' role='alert'>El corte no se encuentra en la base de datos.</div>",
          'info' => ''
        );
        $info = json_encode($info);
        echo $info;
      }
      else
      {
        $this->load->model('corteAutorizado');
        $resultado2 = $this->corteAutorizado->getByFolio($folio);
        if (count($resultado2) != 0)
        {
          $info = array(
            'respuesta' => "<div class='alert alert-warning' role='alert'>El corte ya fue autorizado.</div>",
            'info' => ''
          );
          $info = json_encode($info);
          echo $info;
        }
        else
        {
          $corte = $this->infoCorte($folio);
          unset($corte['ojales']);
          $info = array(
            'respuesta' => "<input type='submit' class='btn btn-primary' value='Aceptar'/>",
            'info' => $corte
          );
          $info = json_encode($info);
          echo $info;
        }
      }
    }
  }

  public function primerProceso($folio = null)
  {
    if (!in_array($_SESSION['id'],array(1,3)) || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    if ($folio == null)
    {
      $info = array(
        'respuesta' => "<div class='alert alert-info' role='alert'>Escriba el número del corte.</div>",
        'info' => ''
      );
      $info = json_encode($info);
      echo $info;
    }
    else
    {
      $this->load->model('corte');
      $resultado = $this->corte->getByFolio($folio);
      if (count($resultado) == 0)
      {
        $info = array(
          'respuesta' => "<div class='alert alert-info' role='alert'>El corte no se encuentra en la base de datos.</div>",
          'info' => ''
        );
        $info = json_encode($info);
        echo $info;
      }
      else
      {
        $this->load->model('corteAutorizado');
        $resultado2 = $this->corteAutorizado->getByFolio($folio);
        if (count($resultado2) == 0)
        {
          $info = array(
            'respuesta' => "<div class='alert alert-warning' role='alert'>El corte no ha sido autorizado.</div>",
            'info' => ''
          );
          $info = json_encode($info);
          echo $info;
        }
        else
        {
          $this->load->model('salidaInterna1');
          $resultado2 = $this->salidaInterna1->getByFolio($folio);
          if (count($resultado2) == 0)
          {
            $info = array(
              'respuesta' => "<div class='alert alert-warning' role='alert'>El corte no tiene salida interna.</div>",
              'info' => ''
            );
            $info = json_encode($info);
            echo $info;
          }
          else
          {
            $this->load->model('corteAutorizadoDatos');
            $resultado2 = $this->corteAutorizadoDatos->procesosAbiertos($folio);
            if (count($resultado2) != 0)
            {
              $info = array(
                'respuesta' => "<div class='alert alert-warning' role='alert'>El corte ya tiene procesos abiertos.</div>",
                'info' => ''
              );
              $info = json_encode($info);
              echo $info;
            }
            else
            {
              $this->load->model('corteAutorizado');
              $cargas = $this->corteAutorizado->getCargasPiezas($folio);
              foreach ($cargas as $key => $value) $cargas[$key]['procesos'] = $this->corteAutorizadoDatos->procesosActivos($value['id']);
              $info = array(
                'respuesta' => $cargas,
                'info' => $this->infoCorte($folio),
              );
              $info = json_encode($info);
              echo $info;
            }
          }
        }
      }
    }
  }

  // OPERARIO Y OPERARIO VALIDA
  public function operarioCargas()
  {
    if(!in_array($_SESSION['id'],array(6,4)) || !$this->input->post()) $this->output->set_status_header('404');
    //if (($_SESSION['id'] != 4 && $_SESSION['id'] != 6) || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    if ($folio == null) echo "<div class='col-12'><div class='alert alert-info' role='alert'>Escriba el número de folio.</div></div>";
    else
    {
      if (! $this->existeCorte($folio)) echo "<div class='col-12'><div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div></div>";
      else
      {
        $this->load->model('corteAutorizadoDatos');
        $query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
        if (count($query) == 0) echo "<div class='col-12'><div class='alert alert-info' role='alert'>Corte aún no autorizado.</div></div>";
        else
        {
          echo "<label for='carga' class='col-3 col-form-label'>Carga</label><div class='col-9'><select name='carga' id='carga' class='form-control'><option value=-1>Seleccione la carga</option>";
          foreach ($query as $value) echo "<option value=" . ($value['idlavado']) . ">" . strtoupper($value['lavado']) . "</option>";
          echo "</select></div>";
        }
      }
    }
  }

  public function operarioProcesos()
  {
    if (!in_array($_SESSION['id'],array(4,6,1,7)) || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    //$carga = $this->input->post()["carga"];
    $this->load->model(array('corteAutorizadoDatos','corte'));
    $query = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros6($folio);
    $cadena = (count($query) == 0)?  "<div class='alert alert-info' role='alert'>No hay procesos para el folio " . $folio . ".</div>" : $query;
    $corte = $this->corte->getByFolioGeneral($this->input->post()["folio"]);
    echo json_encode(array('datos' => $cadena, 'corte' => $corte));
  }

  public function operarioValida()
  {
    if (!in_array($_SESSION['id'],array(4,6,7)) || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    $carga = $this->input->post()["carga"];
    $proceso = $this->input->post()["proceso"];
    if ($proceso == - 1) echo "<div class='col-12'><div class='alert alert-warning' role='alert'>Seleccione un opción válida.</div></div>";
    else
    {
      $this->load->model('corteAutorizadoDatos');
      $query = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros2($folio, $carga, $proceso);
      if ($query[0]['status'] == 1) echo "<div class='col-12'><input type='submit' class='btn btn-primary' value='Aceptar'/></div><input type='hidden' name='piezas' id='piezas' value='" . $query[0]['piezas'] . "'/><input type='hidden' name='nombreCarga' id='nombreCarga' value='" . $query[0]['lavado'] . "'/><input type='hidden' name='nombreProceso' id='nombreProceso' value='" . $query[0]['proceso'] . "'/><input type='hidden' name='idlavado' id='idlavado' value='" . $query[0]['idlavado'] . "'/><input type='hidden' name='orden' id='orden' value='" . $query[0]['orden'] . "'/>";
      else echo "<div class='col-12'><div class='alert alert-info' role='alert'>Éste proceso no está disponible.</div></div>";
    }
  }

  // ADMINISTRACIÓN
  public function costosAdministracion()
  {
    if ($_SESSION['id'] != 1 || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()["folio"];
    if ($folio != null)
    {
      if ($this->existeCorte($folio))
      {
        $this->load->model('corteAutorizadoDatos');
        $this->load->model('corteAutorizadoDatos');
        $query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
        if (count($query) == 0) echo "<div class='col-12'><div class='alert alert-info' role='alert'>Corte aún no autorizado.</div></div>";
        else
        {
          echo "<label for='carga' class='col-3 col-form-label'>Carga</label><div class='col-9'><select name='carga' id='carga' class='form-control'>";
          foreach ($query as $key => $value) echo "<option value=" . ($key + 1) . ">" . strtoupper($value['lavado']) . "</option>";
          echo "</select></div><div class='col-12'><input type='submit' class='btn btn-primary' value='Aceptar'/></div>";
        }
      }
      else echo "<div class='col-12'><div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div></div>";
    }
  }

  public function existeUsuario()
  {
    if ($_SESSION['id'] != 1 || ! $this->input->post() || ! isset($this->input->post()["nombre"])) $this->output->set_status_header('404');
    $nombre = $this->input->post()["nombre"];
    $this->load->model("Usuarios");
    return $this->Usuarios->exists($nombre);
  }

  public function detalleCorte($folio = null)
  {
    if (! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()['folio'];
    if ($this->existeCorte($folio)) echo json_encode($this->infoCorte($folio));
    else echo json_encode(array(
      'folio' => ''
    ));
  }

  // Método para recuperar datos del corte
  private function infoCorte($folio)
  {
    $extensiones = array(
      "jpg",
      "jpeg",
      "png"
    );
    $ban = false;
    foreach ($extensiones as $extension)
    {
      $url = base_url() . "img/fotos/" . $folio . "." . $extension;
      $headers = get_headers($url);
      if (stripos($headers[0], "200 OK"))
      {
        $ban = true;
        $imagen = "<img src='" . base_url() . "img/fotos/" . $folio . "." . $extension . "' class='img-fluid' alt='Responsive image'>";
        break;
      }
    }
    if (!$ban) $imagen = "No hay imágen";
    // Información del corte
    $this->load->model("corte");
    $corte = $this->corte->getByFolioGeneral($folio);
    $corte = (count($corte) > 0)? $corte[0] : array();
    $corte['imagen'] = $imagen;
    return $corte;
  }

  // Método que devuelve true si existe el corte en la base de datos, de lo contrario regresa false
  private function existeCorte($folio)
  {
    $this->load->model('corte');
    $query = $this->corte->getByFolio($folio);
    return (count($query) != 0);
  }

  public function cargasAdministracion()
  {
    if ($_SESSION['id'] != 1 || ! $this->input->post()) $this->output->set_status_header('404');
    $folio = $this->input->post()['folio'];
    $this->load->model('corteAutorizadoDatos');
    $query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
    echo json_encode($query);
  }

  public function getProcesosReproceso()
  {
    if (!isset($this->input->post()['folio'])) redirect("/");
    $this->load->model("Reproceso");
    $query = $this->Reproceso->getByFolioOperarios($this->input->post()['folio']);
    echo json_encode(array('datos' => $query, 'corte' => $this->infoCorte($this->input->post()['folio'])));
  }
}
