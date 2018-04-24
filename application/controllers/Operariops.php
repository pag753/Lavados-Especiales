<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operariops extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario=$_SESSION['id'];
		if($idusuario!=6 && $idusuario!=5) redirect('/');
	}

	public function index($datos=null)
	{
		if($datos==null)
			$data = array(
				'texto1' => "Bienvenido(a)",
				'texto2' => $_SESSION['username']
			);
		else
			$data = array(
				'texto1' =>"Los datos" ,
				'texto2' =>"Se han registrado con éxito"
			);
		$titulo['titulo']='Bienvenido a lavados especiales';
		$this->load->view('head',$titulo);
		$this->load->view('operariops/menu');
		$this->load->view('operariops/index',$data);
		$this->load->view('foot');
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function insertar($id=null)
	{
		if($id==null)
		{
			if($this->input->post())
			{
				$data=$this->input->post();
				$this->load->model('produccionProcesoSeco');
				$query=$this->produccionProcesoSeco->seleccionDefinida($_SESSION['usuario_id'],$this->input->post()['folio'],$this->input->post()['carga'],$this->input->post()['proceso']);
				$data['usuarioid']=$_SESSION['usuario_id'];
				if(count($query)==0)
					$data = array(
						'piezas' => 0,
						'defectos' => 0,
						'nuevo' => 1,
						'idprod' => 0
					);
				else
					$data = array(
						'piezas' => $query[0]['piezas'] ,
						'defectos' => $query[0]['defectos'] ,
						'nuevo' => 0 ,
						'idprod' => $query[0]['id']
					);
			}
			else
				$data=null;
			$data['url']=base_url()."index.php/operariops/insertar";
			$titulo['titulo']='Insertar producción';
			$this->load->view('head',$titulo);
			$this->load->view('operariops/menu');
			$this->load->view('operarios/insertar',$data);
			$this->load->view('foot');
		}
		else
		{
			if($this->input->post())
			{
				$this->load->model('produccionProcesoSeco');
				if($this->input->post()['nuevo']==1)
					$this->produccionProcesoSeco->insertar($this->input->post());
				else
					$this->produccionProcesoSeco->editar($this->input->post());
				$n=1;
			}
			redirect("/operariops/index/2");
		}
	}

	public function cambiarPass()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/operariops/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/Operariops/cambiarPass';
			$titulo['titulo']='Cambiar contraseña';
			$this->load->view('head',$titulo);
			$this->load->view('operariops/menu');
			$this->load->view('cambiarPass',$data);
			$this->load->view('foot');
		}
	}

	public function datos()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateD($_SESSION['usuario_id'],$this->input->post()['nombre_completo'],$this->input->post()['direccion'],$this->input->post()['telefono']);
			redirect('/operariops/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/Operariops/datos';
			$this->load->model('Usuarios');
			$data['data']=$this->Usuarios->getById($_SESSION['usuario_id']);
			$titulo['titulo']='Cambiar datos personales';
			$this->load->view('head',$titulo);
			$this->load->view('operariops/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
		}
	}
}
