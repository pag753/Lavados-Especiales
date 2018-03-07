<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operariops extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario=$_SESSION['id'];
		if($idusuario==6 || $idusuario==5)
		{
		}
		else
			redirect('/');
	}

	public function index($datos=null)
	{
		if($datos==null)
		{
			$data['texto1']="Bienvenido(a) usuario de proceso seco";
			$data['texto2']=$_SESSION['username'];
		}
		else
		{
			$data['texto1']="Los datos";
			$data['texto2']="Se han registrado con éxito";
		}
		$this->load->view('operariops/index',$data);/*
		$this->load->view('encabezado_principal');
		$this->load->view('operariopsBase');
		$this->load->view('operarioPrincipal',$data);
		$this->load->view('footer');*/
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
				{
					$data['piezas']=0;
					$data['defectos']=0;
					$data['nuevo']=1;
					$data['idprod']=0;
				}
				else
				{
					$data['piezas']=$query[0]['piezas'];
					$data['defectos']=$query[0]['defectos'];
					$data['nuevo']=0;
					$data['idprod']=$query[0]['id'];
				}
			}
			else
				$data=null;

			$this->load->view('operariops/insertar',$data);
			/*
			$this->load->view('encabezado_principal');
			$this->load->view('operariopsBase');
			$this->load->view('operariopsInsertar',$data);
			$this->load->view('footer');*/
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
}
