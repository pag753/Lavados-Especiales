<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operario extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario = $_SESSION['id'];
		if ($idusuario != 4 && $idusuario != 5) redirect('/');
	}

	public function index($datos = null)
	{
		if ($this->input->get())
		{
			if (!isset($this->input->get()['q'])) redirect('/');
			switch ($this->input->get()['q'])
			{
				case 'error':
				$data = array(
					'texto1' => "Se produjo un error",
					'texto2' => "Favor de notificar al administrador",
				);
				break;

				case 'reproceso':
				$data = array(
					'texto1' => "La producción de reproceso",
					'texto2' => "Se ha registrado con éxito",
				);
				break;

				case 'cerrarReproceso':
				$data = array(
					'texto1' => "El reproceso",
					'texto2' => "Se ha cerrado con éxito",
				);
				break;

				default:
				redirect("/");
				break;
			}
		}
		else
		{
			if ($datos == null)
				$data = array(
					'texto1' => "Bienvenido(a) usuario",
					'texto2' => $_SESSION['username']
				);
			else
				$data = array(
					'texto1' => "Los datos",
					'texto2' => "Se han registrado con éxito"
				);
		}
		$titulo['titulo'] = 'Bienvenido a lavados especiales';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('operario/menu');
		$this->load->view('operario/index',$data);
		$this->load->view('comunes/foot');
	}

	public function alta($id = null)
	{
		if ($this->input->post())
		{
			$this->load->model('corteAutorizadoDatos');
			$data = $this->input->post();
			$data['faltantes'] = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros4(
				$this->input->post()['folio'],
				$this->input->post()['carga']
			);
			$data['trabajadas'] = 0;
			$data['defectos'] = 0;
			$this->load->model('produccionProcesoSeco');
			$query = $this->produccionProcesoSeco->seleccionDefinida3(
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
			$data['query'] = $query;
			$titulo['titulo'] = 'Cerrar proceso';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operario/altaConfirmacion',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			$titulo['titulo'] = 'Cerrar proceso';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operario/alta');
			$this->load->view('comunes/foot');
		}
	}

	public function registro()
	{
		if ($this->input->post())
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
			if (isset($this->input->post()['siguiente']))
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
		if ($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/operario/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/operario/cambiarPass';
			$titulo['titulo'] = 'Cambiar contraseña';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('comunes/cambiarPass',$data);
			$this->load->view('comunes/foot');
		}
	}

	public function datos()
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
			redirect('/operario/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/operario/datos';
			$this->load->model('Usuarios');
			$data['data'] = $this->Usuarios->getById($_SESSION['usuario_id']);
			$titulo['titulo'] = 'Cambiar datos personales';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('comunes/cambiarDatos',$data);
			$this->load->view('comunes/foot');
		}
	}

	public function insertar($id = null)
	{
		if ($id == null)
		{
			if ($this->input->post())
			{
				$data = $this->input->post();
				$this->load->model('produccionProcesoSeco');
				$query = $this->produccionProcesoSeco->seleccionDefinida(
					$_SESSION['usuario_id'],
					$this->input->post()['folio'],
					$this->input->post()['carga'],
					$this->input->post()['proceso']
				);
				$data['usuarioid'] = $_SESSION['usuario_id'];
				if (count($query) == 0)
				{
					$data['piezas'] = 0;
					$data['defectos'] = 0;
					$data['nuevo'] = 1;
					$data['idprod'] = 0;
				}
				else
				{
					$data['piezas'] = $query[0]['piezas'];
					$data['defectos'] = $query[0]['defectos'];
					$data['nuevo'] = 0;
					$data['idprod'] = $query[0]['id'];
				}
			}
			else $data = null;
			$data['url'] = base_url()."index.php/operario/insertar";
			$titulo['titulo'] = 'Insertar producción';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operarios/insertar',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			if ($this->input->post())
			{
				$this->load->model('produccionProcesoSeco');
				if ($this->input->post()['nuevo'] == 1)
					$this->produccionProcesoSeco->insertar($this->input->post());
				else
					$this->produccionProcesoSeco->editar($this->input->post());
				$n = 1;
			}
			redirect("/operario/index/2");
		}
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function ver()
	{
		if (!$this->input->post())
		{
			$data['data']="operario";
			$titulo['titulo']="Ver Producción";
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operarios/verProduccion',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			$this->load->model("ProduccionProcesoSeco");
			$reporte = $this->ProduccionProcesoSeco->verProduccion(
	            $_SESSION['usuario_id'],
	            $this->input->post()['fechaInicio'],
	            $this->input->post()['fechaFinal']
	        );
			$this->load->model("Descuentos");
	        $descuentos=$this->Descuentos->consulta1(
	            $_SESSION['usuario_id'],
	            $this->input->post()['fechaInicio'],
	            $this->input->post()['fechaFinal']
	        );
			$this->load->library('Comunes');
			$comunes = new Comunes();
			$comunes->verProduccion(
				$reporte,
				$this->input->post()['fechaInicio'],
				$this->input->post()['fechaFinal'],
				$descuentos
			);
		}
	}

	public function verAhorro()
	{
		$this->load->model("Ahorros");
		$data['ahorros'] = $this->Ahorros->getByIdUsuario($_SESSION['usuario_id']);
		$titulo['titulo']="Ver caja de ahorro";
		$this->load->view('comunes/head',$titulo);
		$this->load->view('operario/menu');
		$this->load->view('operarios/verAhorro',$data);
		$this->load->view('comunes/foot');
	}

	public function insertarReproceso()
	{
		if ($this->input->get())
		{
			//Validación de datos
			if (!isset($this->input->get()['id']) || !isset($this->input->get()['lavado']) || !isset($this->input->get()['proceso']) || !is_numeric($this->input->get()['id'])) redirect("operario/index?q=error");
			//Ver si ya hay produccion de este usuario en la base de datos
			$data  = array(
				'reproceso_id' => $this->input->get()['id'],
				'usuario_id' => $_SESSION['usuario_id'],
			);
			$this->load->model("ProduccionReproceso");
			$query = $this->ProduccionReproceso->getWhere($data);
			if (count($query) == 0)
			{
				$data = array(
					'tipo' => 0,
					'piezas' => 0,
					'defectos' => 0,
				);
			}
			else
			{
				$data = array(
					'tipo' => 1,
					'piezas' => $query[0]['piezas'],
					'defectos' => $query[0]['defectos'],
				);
			}
			$data['id'] = $this->input->get()['id'];
			$data['lavado'] = $this->input->get()['lavado'];
			$data['proceso'] = $this->input->get()['proceso'];
			$titulo['titulo']="Insertar producción de reproceso";
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operarios/insertarProduccionReproceso',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			if ($this->input->post())
			{
				if (!isset($this->input->post()['tipo']) || !isset($this->input->post()['id']) || !isset($this->input->post()['piezas']) ||!isset($this->input->post()['defectos']) || !is_numeric($this->input->post()['tipo']) || !is_numeric($this->input->post()['id']) || !is_numeric($this->input->post()['piezas']) || !is_numeric($this->input->post()['defectos'])) redirect("operario/index?q=error");
				$this->load->model("ProduccionReproceso");
				$data  = array(
					'usuario_id' => $_SESSION['usuario_id'],
					'piezas' => $this->input->post()['piezas'],
					'fecha' => date('Y-m-d'),
					'defectos' => $this->input->post()['defectos'],
					'reproceso_id' => $this->input->post()['id'],
				);
				switch ($this->input->post()['tipo'])
				{
					case 0:
					//Nuevo registro
					$this->ProduccionReproceso->insertar($data);
					redirect("operario/index?q=reproceso");
					break;

					case 1:
					//registro ya existente
					$this->ProduccionReproceso->updateByOperario($data);
					redirect("operario/index?q=reproceso");
					break;

					default:
					redirect("operario/index?q=error");
					break;
				}
			}
			else
			{
				$titulo['titulo']="Reproceso";
				$this->load->view('comunes/head',$titulo);
				$this->load->view('operario/menu');
				$this->load->view('operarios/produccionReproceso');
				$this->load->view('comunes/foot');
			}
		}
	}

	public function cerrarReproceso()
	{
		if ($this->input->get())
		{
			//validación
			if (!isset($this->input->get()['id']) || !isset($this->input->get()['lavado']) || !isset($this->input->get()['proceso'])) redirect("operario/index?q=error");
			$this->load->model(array('ProduccionReproceso','Reproceso'));
			//Recuerar datos
			$data['reprocesos'] = $this->ProduccionReproceso->getByIdEspecifico($this->input->get()['id']);
			$data['reproceso'] = $this->Reproceso->getById($this->input->get()['id'])[0];
			if (count($data['reproceso']) == 0) redirect("operario/index?q=error");
			if ($data['reproceso']['status'] == 2) redirect("operario/index?q=error");
			$data['lavado'] = $this->input->get()['lavado'];
			$data['proceso'] = $this->input->get()['proceso'];
			$titulo['titulo']="Cerrar reproceso";
			$this->load->view('comunes/head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operario/cerrarReprocesoEspecifico',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			if($this->input->post())
			{
				//Validación
				if (!isset($this->input->post()['id']) || !isset($this->input->post()['piezas_trabajadas']) || !isset($this->input->post()['defectos']) || !is_numeric($this->input->post()['id']) || !is_numeric($this->input->post()['piezas_trabajadas']) || !is_numeric($this->input->post()['defectos'])) redirect("operario/index?q=error");
				print_r($this->input->post());
				$data = array(
					'piezas_trabajadas' => $this->input->post()['piezas_trabajadas'],
					'defectos' => $this->input->post()['defectos'],
					'status' => 2,
					'usuario_id' => $_SESSION['usuario_id'],
					'id' => $this->input->post()['id'],
				);
				$this->load->model('Reproceso');
				$this->Reproceso->update($data);
				redirect("operario/index?q=cerrarReproceso");
			}
			else
			{
				$titulo['titulo']="Cerrar reproceso";
				$this->load->view('comunes/head',$titulo);
				$this->load->view('operario/menu');
				$this->load->view('operario/cerrarReproceso');
				$this->load->view('comunes/foot');
			}
		}
	}
}
