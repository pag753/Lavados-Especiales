<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entallado extends CI_Controller
{

  /*
  *  Método constructor de la clase
  */
  public function __construct()
  {
    parent::__construct();
    $idusuario = $_SESSION['id'];
    if (!in_array($idusuario,array(8))) redirect('/');
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
    $this->load->view('entallado/menu');
    $this->load->view('comunes/index', $data);
    $this->load->view('comunes/foot');
  }
}
