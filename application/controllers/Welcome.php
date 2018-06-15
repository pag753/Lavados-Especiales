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
                $titulo['titulo'] = 'Bienvenido a lavados especiales';
                $this->load->view('comunes/head', $titulo);
                $this->load->view('welcome/menu');
                $this->load->view('welcome/index', $info);
                $this->load->view('comunes/foot');
            }
        }
        else
            $this->sesion();
    }

    function sesion()
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
        }
    }
}
