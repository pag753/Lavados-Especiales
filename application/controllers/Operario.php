<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operario extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario=$_SESSION['id'];
		if($idusuario==4 || $idusuario==5)
		{
		}
		else
			redirect('/');
	}

	public function index($datos=null)
	{
		if($datos==null)
		{
			$datos['texto1']="Bienvenido(a) usuario";
			$datos['texto2']=$_SESSION['username'];
		}
		else
		{
			$datos['texto1']="Los datos";
			$datos['texto2']="Se han registrado con éxito";
		}
		$datos['folio']=$this->input->post()['folio'];
		$this->load->view('head');
		$this->load->view('operario/menu');
		$this->load->view('operario/index',$datos);
		$this->load->view('foot');

		/*
		$this->load->view('encabezado_principal');
		$this->load->view('operarioBase');
		$this->load->view('operarioPrincipal',$data);
		$this->load->view('footer');*/
	}

	public function alta($id=null)
	{
		if($this->input->post())
		{
			$this->load->model('corteAutorizadoDatos');
			$data=$this->input->post();
			$data['faltantes']=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros4($this->input->post()['folio'],$this->input->post()['carga']);
			$data['trabajadas']=0;
			$data['defectos']=0;
			$this->load->model('produccionProcesoSeco');
			$query=$this->produccionProcesoSeco->seleccionDefinida2($_SESSION['usuario_id'],$this->input->post()['folio'],$this->input->post()['carga']);
			foreach ($query as $key => $value)
			{
				$data['trabajadas']+=$value['piezas'];
				$data['defectos']+=$value['defectos'];
			}
			$data['query']=$query;
			$this->load->view('head');
			$this->load->view('operario/menu');
			$this->load->view('operariops/altaConfirmacion',$data);
			$this->load->view('foot');

			/*
			$this->load->view('encabezado_principal');
			$this->load->view('operarioBase');
			$this->load->view('operarioAltaConfirmacion',$data);
			$this->load->view('footer');*/
		}
		else
		{
			$this->load->view('head');
			$this->load->view('operario/menu');
			$this->loas->view('operario/alta');
			$this->load->view('foot');
			/*
			$this->load->view('encabezado_principal');
			$this->load->view('operarioBase');
			$this->load->view('operarioAlta');
			$this->load->view('footer');*/
		}
	}

	public function registro()
	{
		if($this->input->post())
		{
			$this->load->model('corteAutorizadoDatos');
			$this->corteAutorizadoDatos->actualiza2($this->input->post()['proceso'],$this->input->post()['folio'],$this->input->post()['carga'],$this->input->post()['piezas_trabajadas'],$this->input->post()['defectos'],date("Y/m/d"),$_SESSION['usuario_id']);
			if(isset($this->input->post()['siguente']))
			{
				$this->corteAutorizadoDatos->actualiza($this->input->post()['siguente'],$this->input->post()['folio'],$this->input->post()['carga'],$this->input->post()['piezas_trabajadas'],$this->input->post()['orden']+1);
			}
		  redirect("/operario/index/2");
		}
		else
		{
			redirect("/");
		}
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
