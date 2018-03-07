<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if(!isset($_SESSION['username']))
		{
			if($this->input->post())
			{
				$this->load->model('usuarios');
				$query=$this->usuarios->login();
				if(!empty($query))
				{
					$data= array(
					'username'  => $query[0]['nombre'],
	      			'id' => $query[0]["tipo_usuario_id"],
					'usuario_id' => $query[0]["id"],
	      			'logged_in' => TRUE);
					$this->session->set_userdata($data);
					$this->sesion();
				}
				else
				{
					$info = array('texto1' => 'Error en usuario o contraseña',
												'texto2' => 'Intente de nuevo');
					$this->carga($info);
				}
			}
			else
			{
				$info = array('texto1' => 'Bienvenido a',
			 								'texto2' => 'Lavados especiales');
				$this->carga($info);
			}
		}
		else
		{
			$this->sesion();
		}
	}

	function carga($info)
	{
		$this->load->view('welcome/index',$info);
	}


	function sesion()
	{
		$id=$_SESSION['id'];
		switch ($id)
		{
			case 1://administrador
			redirect('/administracion');
				break;
			case 2://gestion
			redirect('/gestion');
				break;
			case 3://producción
			redirect('/produccion');
				break;
			case 4://encargado de proceso seco
			redirect('/operario');
			 	break;
			case 5://root
			redirect('/root');
				break;
			case 6://operario de proceso seco
			redirect('/operariops');
				break;
		}
	}

	public function cambiarPass()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			$id=$_SESSION['id'];
			switch ($id)
			{
				case 1://administrador
				redirect('/administracion/index/-1');
					break;
				case 2://gestion
				redirect('/gestion/index/-1');
					break;
				case 3://producción
				redirect('/produccion/index/-1');
					break;
				case 4://encargado de proceso seco
				redirect('/operario/index/-1');
				 	break;
				case 5://root
				redirect('/root/index/-1');
					break;
				case 6://operario de proceso seco
				redirect('/operariops/index/-1');
					break;
			}
		}
		else
		{
			redirect('/');
		}
	}
}
