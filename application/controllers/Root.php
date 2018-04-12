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

		$this->load->view('head');
		$this->load->view('root/menu');
		$this->load->view('root/index');
		$this->load->view('foot');
		/*
		$this->load->view('encabezado_principal');
		$this->load->view('rootBase');
		$this->load->view('rootPrincipal',$data);
		$this->load->view('footer');*/
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
