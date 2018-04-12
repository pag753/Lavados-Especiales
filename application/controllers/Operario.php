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
			$data['texto1']="Bienvenido(a) usuario";
			$data['texto2']=$_SESSION['username'];
		}
		else
		{
			$data['texto1']="Los datos";
			$data['texto2']="Se han registrado con éxito";
		}
		$this->load->view('head');
		$this->load->view('operario/menu');
		$this->load->view('operario/index',$data);
		$this->load->view('foot');
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
			$this->load->view('operario/altaConfirmacion',$data);
			$this->load->view('foot');
		}
		else
		{
			$this->load->view('head');
			$this->load->view('operario/menu');
			$this->load->view('operario/alta');
			$this->load->view('foot');
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

	public function cambiarPass()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/operario/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/operario/cambiarPass';
			$this->load->view('head');
			$this->load->view('operario/menu');
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
			redirect('/operario/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/operario/datos';
			$this->load->model('Usuarios');
			$data['data']=$this->Usuarios->getById($_SESSION['usuario_id']);
			$this->load->view('head');
			$this->load->view('operario/menu');
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
