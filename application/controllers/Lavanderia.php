<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lavanderia extends CI_Controller
{

  /*
  *  Método constructor de la clase
  */
  public function __construct()
  {
    parent::__construct();
    $idusuario = $_SESSION['id'];
    if (!in_array($idusuario,array(1,7))) redirect('/');
  }

  /*
  *  Método de bienvenida en la sesión Administrador
  */
  public function index($datos = null)
  {
    if ($this->input->get())
    {
      switch ($this->input->get()['q'])
      {
        case 'error':
        $data = array(
          'texto1' => "Sucedió un error",
          'texto2' => "Favor de reportarlo"
        );
        break;

        case 'reproceso':
        $data = array(
          'texto1' => "El reproceso",
          'texto2' => "Se ha registrado con éxito"
        );
        break;

        case 'folio':
        $data = array(
          'texto1' => "El corte con folio " . $this->input->get()['folio'],
          'texto2' => "Se ha registrado con éxito"
        );
        break;
        case 'noCorte':
        $data = array(
          'texto1' => "El corte",
          'texto2' => "No contiene registros de producción"
        );
        break;
        default:
        redirect("/");
        break;
      }
    }
    else
    {
      if ($datos == null)
      {
        $data = array(
          'texto1' => 'Bienvenido(a)',
          'texto2' => $_SESSION['username']
        );
      }
      elseif ($datos == - 1)
      {
        $data = array(
          'texto1' => 'Los datos',
          'texto2' => 'Se han registrado con éxito'
        );
      }
      else
      {
        $data = array(
          'texto1' => "El corte con folio " . $datos,
          'texto2' => "Se ha registrado con éxito"
        );
      }
    }
    $titulo['titulo'] = 'Bienvenido a lavados especiales';
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('comunes/index', $data);
    $this->load->view('comunes/foot');
  }

  public function reportar($folio = null)
  {
    $titulo['titulo'] = 'Reportar piezas de lavandería';
    $data['folio'] = $folio;
    if ($this->input->post())
    {
      //print_r($this->input->post());
      $this->load->model('corteAutorizadoDatos');
      $id = $this->input->post()['id'];
      $trabajadas = $this->input->post()['piezas'];
      $defectos = $this->input->post()['defectos'];
      $fecha = date('Y-m-d');
      $usuario = $_SESSION['usuario_id'];
      $this->corteAutorizadoDatos->actualiza2($id, $trabajadas, $defectos, $fecha, $usuario);
      if (isset($this->input->post()['siguiente']))
      {
        $id = $this->input->post()['siguiente'];
        $piezas = $trabajadas;
        $orden = $this->input->post()['orden'] + 1;
        $this->corteAutorizadoDatos->actualiza($id, $piezas, $orden);
      }
      redirect('lavanderia/reportar/'.$this->input->post()['folio']);
    }
    elseif ($this->input->get())
    {
      $this->load->model('corteAutorizadoDatos');
      $query = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros2($this->input->get()['id']);
      $data = $this->input->get();
      $data['piezas'] = $query[0]['piezas'];
      $data['nombreCarga'] = $query[0]['lavado'];
      $data['nombreProceso'] = $query[0]['proceso'];
      $data['idlavado'] = $query[0]['idlavado'];
      $data['orden'] = $query[0]['orden'];
      $data['faltantes'] = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros4($this->input->get()['f'], $this->input->get()['c']);
      //$data['query'] = $query;
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('lavanderia/reportarConfirmacion', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('lavanderia/reportar',$data);
      $this->load->view('comunes/foot');
    }
  }
}
