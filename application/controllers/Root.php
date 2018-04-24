<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Root extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($_SESSION['id']!=5)
			redirect('/');
	}

	public function index()
	{
		$data['texto1']="Bienvenido(a) usuario";
		$data['texto2']=$_SESSION['username'];
		$titulo['titulo']='Bienvenido a lavados especiales';
		$this->load->view('head',$titulo);
		$this->load->view('root/menu');
		$this->load->view('root/index',$data);
		$this->load->view('foot');
	}

	public function cambiarPass()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/root/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/root/cambiarPass';
			$titulo['titulo']='Cambiar contraseña';
			$this->load->view('head',$titulo);
			$this->load->view('root/menu');
			$this->load->view('cambiarPass',$data);
			$this->load->view('foot');
		}
	}

	public function cambiarDatos()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateD($_SESSION['usuario_id'],$this->input->post()['nombre_completo'],$this->input->post()['direccion'],$this->input->post()['telefono']);
			redirect('/root/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/root/cambiarDatos';
			$this->load->model('Usuarios');
			$data['data']=$this->Usuarios->getById($_SESSION['usuario_id']);
			$titulo['titulo']='Cambiar datos personales';
			$this->load->view('head',$titulo);
			$this->load->view('root/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
		}
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
