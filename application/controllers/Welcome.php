<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    if (! isset($_SESSION['username']))
    {
      if ($this->input->post())
      {
        $this->load->model('usuarios');
        $query = $this->usuarios->login();
        if (! empty($query))
        {
          $data = array(
            'username' => $query[0]['nombre'],
            'id' => $query[0]["tipo_usuario_id"],
            'usuario_id' => $query[0]["id"],
            'logged_in' => TRUE
          );
          $this->session->set_userdata($data);
          $this->sesion();
        }
        else
        {
          $info = array(
            'texto1' => 'Error en usuario o contraseña',
            'texto2' => 'Intente de nuevo'
          );
          $titulo['titulo'] = 'Error de entrada de usuario';
          $this->load->view('comunes/head', $titulo);
          $this->load->view('welcome/menu');
          $this->load->view('welcome/index', $info);
          $this->load->view('comunes/foot');
        }
      }
      else
      {
        $info = array(
          'texto1' => 'Bienvenido',
          'texto2' => 'Lavados especiales'
        );
        $titulo = null;
        $titulo['titulo'] = 'Bienvenido a lavados especiales';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('welcome/menu');
        $this->load->view('welcome/index', $info);
        $this->load->view('comunes/foot');
      }
    }
    else $this->sesion();
  }

  private function sesion()
  {
    $id = $_SESSION['id'];
    switch ($id)
    {
      case 1: // administrador
      redirect('/administracion');
      break;
      case 2: // gestion
      redirect('/gestion');
      break;
      case 3: // producción
      redirect('/produccion');
      break;
      case 4: // encargado de proceso seco
      case 6: // operario de proceso seco
      redirect('/operario');
      break;
      case 5: // root
      redirect('/root');
      break;
      case 7: // lavanderia
      redirect('/lavanderia');
      break;
      case 8: // lavanderia
      redirect('/entallado');
      break;
    }
  }

  /*
  ** Método para cambiar la contraseña
  */
  public function cambiarPass()
  {
    if ($this->input->post() && isset($this->input->post()['pass1']))
    {
      $this->load->model('Usuarios');
      $this->Usuarios->updateP($_SESSION['usuario_id'], md5($this->input->post()['pass1']));
      $this->redirigir();
    }
    else
    {
      $titulo = null;
      $titulo['titulo'] = 'Cambiar contraseña';
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('comunes/cambiarPass');
      $this->load->view('comunes/foot');
    }
  }

  /*
  ** Método para cambiar los datos personales
  */
  public function cambiarDatos()
  {
    if ($this->input->post() && isset($this->input->post()['nombre_completo']) && isset($this->input->post()['direccion']) && isset($this->input->post()['telefono']))
    {
      $this->load->model('Usuarios');
      $this->Usuarios->updateD($_SESSION['usuario_id'], $this->input->post()['nombre_completo'], $this->input->post()['direccion'], $this->input->post()['telefono']);
      $this->redirigir();
    }
    else
    {
      $this->load->model('Usuarios');
      $data = array(
        'data' => $this->Usuarios->getById($_SESSION['usuario_id'])
      );
      $titulo = null;
      $titulo['titulo'] = 'Cambiar datos personales';
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('comunes/cambiarDatos', $data);
      $this->load->view('comunes/foot');
    }
  }

  /*
  ** Método para cerrar la sesión
  */
  public function cerrarSesion()
  {
    $this->session->sess_destroy();
    redirect('/');
  }

  private function redirigir()
  {
    switch ($_SESSION['id'])
    {
      case 1:
      redirect('administracion/index/-1');
      break;
      case 2:
      redirect('gestion/index/-1');
      break;
      case 3:
      redirect('produccion/index/-1');
      break;
      case 4: case 6:
      redirect('operario/index/-1');
      break;
      case 5:
      redirect('root/index/-1');
      break;
      case 7:
      redirect('lavanderia/index/-1');
      break;
      default:
      redirect('/');
      break;
    }
  }
}
