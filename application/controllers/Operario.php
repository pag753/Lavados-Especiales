<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operario extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario=$_SESSION['id'];
		if($idusuario!=4 && $idusuario!=5) redirect('/');
	}

	public function index($datos=null)
	{
		if($datos==null)
			$data = array(
				'texto1' => "Bienvenido(a) usuario",
				'texto2' => $_SESSION['username']
			);
		else
			$data = array(
				'texto1' => "Los datos",
			 	'texto2' => "Se han registrado con éxito"
			);
		$titulo['titulo']='Bienvenido a lavados especiales';
		$this->load->view('head',$titulo);
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
			$data['faltantes']=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros4(
				$this->input->post()['folio'],
				$this->input->post()['carga']
			);
			$data['trabajadas']=0;
			$data['defectos']=0;
			$this->load->model('produccionProcesoSeco');
			$query=$this->produccionProcesoSeco->seleccionDefinida3(
				$_SESSION['usuario_id'],
				$this->input->post()['folio'],
				$this->input->post()['carga'],
				$this->input->post()['proceso']
			);
			foreach ($query as $key => $value)
			{
				$data['trabajadas']+=$value['piezas'];
				$data['defectos']+=$value['defectos'];
			}
			$data['query']=$query;
			$titulo['titulo']='Cerrar proceso';
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operario/altaConfirmacion',$data);
			$this->load->view('foot');
		}
		else
		{
			$titulo['titulo']='Cerrar proceso';
			$this->load->view('head',$titulo);
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
			//Actualizando datos de proceso actual
			$this->corteAutorizadoDatos->actualiza2(
				$this->input->post()['proceso'],
				$this->input->post()['folio'],
				$this->input->post()['carga'],
				$this->input->post()['piezas_trabajadas'],
				$this->input->post()['defectos'],
				date("Y/m/d"),
				$_SESSION['usuario_id']
			);
			//Actualizando datos de proceso siguiente
			if(isset($this->input->post()['siguiente']))
				$this->corteAutorizadoDatos->actualiza(
					$this->input->post()['siguiente'],
					$this->input->post()['folio'],
					$this->input->post()['carga'],
					$this->input->post()['piezas_trabajadas'],
					$this->input->post()['orden']+1
				);
			redirect("/operario/index/2");
		}
		else redirect("/");
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
			$titulo['titulo']='Cambiar contraseña';
			$this->load->view('head',$titulo);
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
			$this->Usuarios->updateD(
				$_SESSION['usuario_id'],
				$this->input->post()['nombre_completo'],
				$this->input->post()['direccion'],
				$this->input->post()['telefono']
			);
			redirect('/operario/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/operario/datos';
			$this->load->model('Usuarios');
			$data['data']=$this->Usuarios->getById($_SESSION['usuario_id']);
			$titulo['titulo']='Cambiar datos personales';
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
		}
	}

	public function insertar($id=null)
	{
		if($id==null)
		{
			if($this->input->post())
			{
				$data=$this->input->post();
				$this->load->model('produccionProcesoSeco');
				$query=$this->produccionProcesoSeco->seleccionDefinida(
					$_SESSION['usuario_id'],
					$this->input->post()['folio'],
					$this->input->post()['carga'],
					$this->input->post()['proceso']
				);
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
						'piezas' => $query[0]['piezas'],
						'defectos' => $query[0]['defectos'],
						'nuevo' => 0,
						'idprod' => $query[0]['id']
					);
			}
			else $data=null;
			$data['url']=base_url()."index.php/operario/insertar";
			$titulo['titulo']='Insertar producción';
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
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
			redirect("/operario/index/2");
		}
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
