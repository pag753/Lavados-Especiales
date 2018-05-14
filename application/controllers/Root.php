<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Root extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($_SESSION['id'] != 5)
			redirect('/');
	}

	public function index()
	{
		$data = array('texto1' => "Bienvenido(a) usuario", 'texto2' => $_SESSION['username']);
		$titulo['titulo'] = 'Bienvenido a lavados especiales';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('root/menu');
		$this->load->view('root/index',$data);
		$this->load->view('comunes/foot');
	}

	public function cambiarPass()
	{
		if ($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/root/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/root/cambiarPass';
			$titulo['titulo'] = 'Cambiar contraseña';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('root/menu');
			$this->load->view('comunes/cambiarPass',$data);
			$this->load->view('comunes/foot');
		}
	}

	public function cambiarDatos()
	{
		if ($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateD(
				$_SESSION['usuario_id'],
				$this->input->post()['nombre_completo'],
				$this->input->post()['direccion'],
				$this->input->post()['telefono']
			);
			redirect('/root/index/-1');
		}
		else
		{
			$this->load->model('Usuarios');
			$data = array(
				'link' => base_url().'index.php/root/cambiarDatos',
				'data' => $this->Usuarios->getById($_SESSION['usuario_id'])
			);
			$titulo['titulo'] = 'Cambiar datos personales';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('root/menu');
			$this->load->view('comunes/cambiarDatos',$data);
			$this->load->view('comunes/foot');
		}
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
