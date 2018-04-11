<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administracion extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario=$_SESSION['id'];
		if($idusuario==1 || $idusuario==5)
		{
		}
		else
			redirect('/');
	}
	public function index($datos=null)
	{
		if($datos==null)
		{
			$data['texto1']="Bienvenido(a) usuario de administración";
			$data['texto2']=$_SESSION['username'];
		}
		else
		{
			if($datos=-1)
			{
				$data['texto1']="Los datos";
				$data['texto2']="Se han registrado con éxito";
			}
			else
			{
				$data['texto1']="El corte con folio ".$datos;
				$data['texto2']="Se ha registrado con éxito";
			}

		}
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/index',$data);
		$this->load->view('foot');
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function costos($folio=null)
	{
		if($folio==null)
		{
			$textos['texto1']="Costos del corte";
			$textos['texto2']="Ingrese el numero de folio";
			$this->cargaCostoValidacion($textos);
			if($this->input->post())
			{
				$folio=$this->input->post()['folio'];
				redirect('/administracion/costos/'.$folio."_".$this->input->post()['carga']);
			}
		}
		else
		{
			$a=explode("_", $folio);
			$folio=$a[0];
			$cargaid=$a[1];
			if($this->input->post())
			{
				$this->load->model('corteAutorizadoDatos');
				foreach ($this->input->post()['costo'] as $key => $value)
				{
					$query=$this->corteAutorizadoDatos->actualizaCosto($this->input->post()['folio'],$this->input->post()['carga'],$key,$value,$this->input->post()['idlavado']);
				}
				redirect('/administracion/index/'.$this->input->post()['folio']);
			}
			else
			{
				$datos['carga']=$cargaid;
				$this->load->model('corte');
				$datos['texto1']="Asignación de costos";
				$datos['texto2']="Inserte la información";
				$query=$this->corte->getByFolioGeneral($folio);
				$datos['folio']=$folio;
				$datos['corte']=$query[0]['corte'];
				$datos['marca']=$query[0]['marca'];
				$datos['maquilero']=$query[0]['maquilero'];
				$datos['cliente']=$query[0]['cliente'];
				$datos['tipo']=$query[0]['tipo'];
				$datos['piezas']=$query[0]['piezas'];
				$datos['fecha']=$query[0]['fecha'];
				$this->load->model('corteAutorizadoDatos');
				$query=$this->corteAutorizadoDatos->joinLavadoProcesosCarga($folio,$cargaid);
				$datos['lavado']=$query[0]['lavado'];
				$datos['idlavado']=$query[0]['idlavado'];
				foreach ($query as $key => $value)
				{
					$datos['procesos'][$value['idproceso']]=$value['proceso'];
					$datos['costos'][$value['idproceso']]=$value['costo'];
				}
				$this->cargaCosto($datos);
			}
		}
	}

	private function cargaCosto($datos)
	{
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/cargaCosto',$datos);
		$this->load->view('foot');
	}


	private function cargaCostoValidacion($textos)
	{
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/cargaCostoValidacion',$textos);
		$this->load->view('foot');
	}

	//CATÁLOGOS
	public function catalogosClientes()
	{
		$this->load->model("Cliente");
		$data['data']=$this->Cliente->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosCliente',$data);
		$this->load->view('foot');
	}

	public function catalogosLavados()
	{
		$this->load->model("Lavado");
		$data['data']=$this->Lavado->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosLavado',$data);
		$this->load->view('foot');
	}

	public function catalogosMaquileros()
	{
		$this->load->model("Maquilero");
		$data['data']=$this->Maquilero->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosMaquilero',$data);
		$this->load->view('foot');
	}

	public function catalogosMarcas()
	{
		$this->load->model("Marca");
		$data['data']=$this->Marca->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosMarca',$data);
		$this->load->view('foot');
	}

	public function catalogosProcesos()
	{
		$this->load->model("ProcesoSeco");
		$data['data']=$this->ProcesoSeco->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosProcesos',$data);
		$this->load->view('foot');
	}

	public function catalogosUsuarios()
	{
		$this->load->model("Usuarios");
		$this->load->model("TipoUsuario");
		$data['data']=$this->Usuarios->get();
		$data['TipoUsuario']=$this->TipoUsuario->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosUsuarios',$data);
		$this->load->view('foot');
	}

	public function catalogosTipos()
	{
		$this->load->model("Tipo_pantalon");
		$data['data']=$this->Tipo_pantalon->get();
		$this->load->view('head');
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosTipoPantalon',$data);
		$this->load->view('foot');
	}

	public function nuevoCliente()
	{
		if($this->input->post())
		{
			$this->load->model("Cliente");
			$data['nombre']=$this->input->post()['nombre'];
			$data['direccion']=$this->input->post()['direccion'];
			$data['telefono']=$this->input->post()['telefono'];
			$n=$this->Cliente->insert($data);
			redirect("/administracion/catalogosClientes");
		}
		else redirect("/");
	}

	public function editarCliente()
	{
		if($this->input->post())
		{
				$this->load->model("Cliente");
				$this->Cliente->update(
					$this->input->post()['nombreE'],
					$this->input->post()['direccionE'],
					$this->input->post()['telefonoE'],
					$this->input->post()['id']
			);
			redirect("/administracion/catalogosClientes");
		}
		else redirect("/");
	}

	public function nuevoLavado()
	{
		if($this->input->post())
		{
			$this->load->model("Lavado");
			$data['nombre']=$this->input->post()['nombre'];
			$this->Lavado->insert($data);
			redirect("/administracion/catalogosLavados");
		}
		else redirect("/");
	}

	public function editarLavado()
	{
		if($this->input->post())
		{
			$this->load->model("Lavado");
			$this->Lavado->update(
				$this->input->post()['nombreE'],
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosLavados");
		}
		else redirect("/");
	}

	public function nuevoMaquilero()
	{
		if($this->input->post())
		{
			$this->load->model("Maquilero");
			$data['nombre']=$this->input->post()['nombre'];
			$data['direccion']=$this->input->post()['direccion'];
			$data['telefono']=$this->input->post()['telefono'];
			$this->Maquilero->insert($data);
			redirect("/administracion/catalogosMaquileros");
		}
		else redirect("/");
	}

	public function editarMaquilero()
	{
		if($this->input->post())
		{
			$this->load->model("Maquilero");
			$this->Maquilero->update(
				$this->input->post()['nombreE'],
				$this->input->post()['direccionE'],
				$this->input->post()['telefonoE'],
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosMaquileros");
		}
		else redirect("/");
	}

	public function nuevoMarca()
	{
		if($this->input->post())
		{
			$this->load->model("Marca");
			$data['nombre']=$this->input->post()['nombre'];
			$this->Marca->insert($data);
			redirect("/administracion/catalogosMarcas");
		}
		else redirect("/");
	}

	public function editarMarca()
	{
		if($this->input->post())
		{
			$this->load->model("Marca");
			$this->Marca->update(
				$this->input->post()['nombreE'],
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosMarcas");
		}
		else redirect("/");
	}

	public function nuevoProceso()
	{
		if($this->input->post())
		{
			$this->load->model("ProcesoSeco");
			$data['nombre']=$this->input->post()['nombre'];
			$data['costo']=$this->input->post()['costo'];
			$data['abreviatura']=$this->input->post()['abreviatura'];
			$this->ProcesoSeco->insert($data);
			redirect("/administracion/catalogosProcesos");
		}
		else redirect("/");
	}

	public function editarProceso()
	{
		if($this->input->post())
		{
			$this->load->model("ProcesoSeco");
			$this->ProcesoSeco->update(
				$this->input->post()['nombreE'],
				$this->input->post()['costoE'],
				$this->input->post()['abreviaturaE'],
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosProcesos");
		}
		else redirect("/");
	}

	public function nuevoTipo()
	{
		if($this->input->post())
		{
			$this->load->model("Tipo_pantalon");
			$data['nombre']=$this->input->post()['nombre'];
			$this->Tipo_pantalon->insert($data);
			redirect("/administracion/catalogosTipos");
		}
		else redirect("/");
	}

	public function editarTipo()
	{
		if($this->input->post())
		{
			$this->load->model("Tipo_pantalon");
			$this->Tipo_pantalon->update(
				$this->input->post()['nombreE'],
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosTipos");
		}
		else redirect("/");
	}

	public function nuevoUsuario()
	{
		if($this->input->post())
		{
			$this->load->model("Usuarios");
			$data['nombre']=$this->input->post()['nombre'];
			$data['pass']=$this->input->post()['pass'];
			$data['tipo_usuario_id']=$this->input->post()['tipo_usuario_id'];
			$data['nombre_completo']=$this->input->post()['nombre_completo'];
			$data['direccion']=$this->input->post()['direccion'];
			$data['telefono']=$this->input->post()['telefono'];
			$this->Usuarios->insert($data);
			redirect("/administracion/catalogosUsuarios");
		}
		else redirect("/");
	}

	public function editarUsuario()
	{
		if($this->input->post())
		{
			$this->load->model("Usuarios");
			$this->Usuarios->update(
				$this->input->post()['nombreE'],
				$this->input->post()['passE'],
				$this->input->post()['tipo_usuario_idE'],
				$this->input->post()['nombre_completoE'],
				$this->input->post()['direccionE'],
				$this->input->post()['telefonoE'],
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosUsuarios");
		}
		else redirect("/");
	}

	public function cambiarPass()
	{
		if($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/administracion/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/administracion/cambiarPass';
			$this->load->view('head');
			$this->load->view('administracion/menu');
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
			redirect('/administracion/index/-1');
		}
		else
		{
			$data['link']=base_url().'index.php/administracion/datos';
			$this->load->model('Usuarios');
			$data['data']=$this->Usuarios->getById($_SESSION['usuario_id']);
			$this->load->view('head');
			$this->load->view('administracion/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
		}
	}
}
