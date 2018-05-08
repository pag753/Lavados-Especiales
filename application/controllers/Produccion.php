<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produccion extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario = $_SESSION['id'];
		if ($idusuario != 3 && $idusuario != 5)
			redirect('/');
	}

	public function index($datos = null)
	{
		if ($datos == null)
			$data = array(
				'texto1' => "Bienvenido(a)",
				'texto2' => $_SESSION['username']
			);
		elseif ($datos<0)
			$data = array(
				'texto1' => "Los datos", 
				'texto2' => "Se han actualizado con éxito"
			);
		else
			$data = array(
				'texto1' => "El corte con folio ".$datos,
				'texto2' => "Se ha autorizado con éxito"
			);
		$titulo['titulo'] = 'Bienvenido a lavados especiales';
		$this->load->view('head',$titulo);
		$this->load->view('produccion/menu');
		$this->load->view('produccion/index',$data);
		$this->load->view('foot');
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function autorizar()
	{
		if ($this->input->post())
		{
			$datos['datos_corte'] = $this->input->post();
			$this->load->model('corte');
			$resultado = $this->corte->getByFolio($datos['datos_corte']['folio']);
			$this->load->model('corteAutorizado');
			$resultado2 = $this->corteAutorizado->getByFolio($datos['datos_corte']['folio']);
			$data['corte_folio'] = $datos['datos_corte']['folio'];
			$data['fecha_autorizado'] = substr($datos['datos_corte']['fecha'],0,10);
			if ($this->input->post()['numero'] != 0)
			{
				$data['cargas'] = count($this->input->post()['lavado']);
				$this->load->model('corteAutorizadoDatos');
				$this->corteAutorizado->agregar($data);
				$data = null;
				$data['corte_folio'] = $datos['datos_corte']['folio'];
				$contador = 1;
				foreach ($this->input->post()['lavado'] as $key => $value)
				{
					$this->load->model('procesoSeco');
					$ps = $this->procesoSeco->get();
					foreach ($ps as $key2 => $value)
						$precios[$value['id']]=$value['costo'];
					$data['id_carga'] = $contador;
					$data['lavado_id'] = $this->input->post()['lavado'][$key];
					foreach ($this->input->post()['proceso_seco'][$key] as $num => $valor)
					{
						$data['proceso_seco_id'] = $valor;
						$data['costo'] = $precios[$valor];
						if ($data['costo'] == 0)
							$data['status'] = 2;
						else
							$data['status'] = 0;
						$n = $this->corteAutorizadoDatos->agregar($data);
					}
					$contador++;
				}
				redirect('/produccion/index/'.$datos['datos_corte']['folio']);
			}
			else
				$this->cargarAutorizacion($this->input->post(),'Autorización de Corte','No agregó ningún lavado');
		}
		else
			$this->cargarAutorizacion('','Autorización de Corte','Ingrese los datos');
	}

	private function cargarAutorizacion($entrada = null,$texto1,$texto2)
	{
		$this->load->model('lavado');
		$this->load->model('procesoSeco');
		$datos = array(
			'lavados' => $this->lavado->get(),
			'procesos' => $this->procesoSeco->get(),
			'datos_corte' => $entrada,
			'texto1' => $texto1,
			'texto2' => $texto2
		);
		$titulo['titulo'] = 'Autorizar corte';
		$this->load->view('head',$titulo);
		$this->load->view('produccion/menu');
		$this->load->view('produccion/cargarAutorizacion',$datos);
		$this->load->view('foot');
	}

	public function cambiarPass()
	{
		if ($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/produccion/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/produccion/cambiarPass';
			$titulo['titulo'] = 'Cambiar contraseña';
			$this->load->view('head',$titulo);
			$this->load->view('produccion/menu');
			$this->load->view('cambiarPass',$data);
			$this->load->view('foot');
		}
	}

	public function datos()
	{
		if ($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateD(
				$_SESSION['usuario_id'],$this->input->post()['nombre_completo'],
				$this->input->post()['direccion'],$this->input->post()['telefono']
			);
			redirect('/produccion/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/produccion/datos';
			$this->load->model('Usuarios');
			$data['data'] = $this->Usuarios->getById($_SESSION['usuario_id']);
			$titulo['titulo'] = 'Cambiar datos personales';
			$this->load->view('head',$titulo);
			$this->load->view('produccion/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
		}
	}
}