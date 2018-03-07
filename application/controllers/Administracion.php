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
		$this->load->view('administracion/index',$data);/*
		$this->load->view('encabezado_principal');
		$this->load->view('administracionBase');
		$this->load->view('administracionPrincipal',$data);
		$this->load->view('footer');*/
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
		$this->load->view('administracion/cargaCosto',$datos);/*
		$this->load->view('encabezado_principal');
		$this->load->view('administracionBase');
		$this->load->view('administracionCostos',$datos);
		$this->load->view('footer');*/
	}


	private function cargaCostoValidacion($textos)
	{
		$this->load->view('administracion/cargaCostoValidacion');/*
		$this->load->view('encabezado_principal');
		$this->load->view('administracionBase');
		$this->load->view('administracionCostosValidacion',$textos);
		$this->load->view('footer');*/
	}

	public function catalogos($id=null)
	{
		//$this->load->view('encabezado_principal');
		//$this->load->view('administracionBase');
		if($this->input->post())
		{
			$data['post']=$this->input->post();
			$id=$this->input->post()['nombre'];
			switch ($this->input->post()['id'])
			{
				case 1:
					$this->load->model("Cliente");
					$data['data']=$this->Cliente->getById($id);
					$this->load->model("ClienteHasMarca");
					$data['marca']=$this->ClienteHasMarca->get($id);
					$this->load->model('Marca');
					$data['marcas']=$this->Marca->get();
					$this->load->view('administracion/catalogosCliente',$data);
					//$this->load->view('administracionCatalogosCliente',$data);
					break;

				case 2:
					$this->load->model("Lavado");
					$data['data']=$this->Lavado->getById($id);
					$this->load->view('administracion/catalogosLavado',$data);
					//$this->load->view('administracionCatalogosLavado',$data);
					break;

				case 3:
					$this->load->model("Maquilero");
					$data['data']=$this->Maquilero->getById($id);
					$this->load->view('administracion/catalogosMaquilero',$data);
					//$this->load->view('administracionCatalogosMaquilero',$data);
					break;

				case 4:
					$this->load->model("Marca");
					$data['data']=$this->Marca->getById($id);
					$this->load->view('administracion/catalogosMarca',$data);
					//$this->load->view('administracionCatalogosMarca',$data);
					break;

					case 5:
					$this->load->model("ProcesoSeco");
					$data['data']=$this->ProcesoSeco->getById($id);
					$this->load->view('administracion/catalogosProcesos',$data);
					//$this->load->view('administracionCatalogosProcesos',$data);
						break;

					case 6:
					$this->load->model("Usuarios");
					$data['data']=$this->Usuarios->getById($id);
					$this->load->model("TipoUsuario");
					$data['tiposUsuario']=$this->TipoUsuario->get();
					$this->load->view('administracion/catalogosUsuarios',$data);
					//$this->load->view('administracionCatalogosUsuarios',$data);
						break;

					case 7:
					$this->load->model("Tipo_pantalon");
					$data['data']=$this->Tipo_pantalon->getById($id);
					$this->load->view('administracion/catalogosTipoPantalon',$data);
					//$this->load->view('administracionCatalogosTipoPantalon',$data);
						break;
				}
				//$this->load->view('footer');

		}
		else
		{
			if($id==null)
				redirect('/');
			else
			{
				$data['id']=$id;
				switch ($id)
				{
					case 1:
					$this->load->model("Cliente");
					$data['data']=$this->Cliente->get();
						break;
					case 2:
					$this->load->model("Lavado");
					$data['data']=$this->Lavado->get();
						break;

					case 3:
					$this->load->model("Maquilero");
					$data['data']=$this->Maquilero->get();
						break;

					case 4:
					$this->load->model("Marca");
					$data['data']=$this->Marca->get();
						break;

					case 5:
					$this->load->model("ProcesoSeco");
					$data['data']=$this->ProcesoSeco->get();
						break;

					case 6:
					$this->load->model("Usuarios");
					$data['data']=$this->Usuarios->get();
						break;

					case 7:
					$this->load->model("Tipo_pantalon");
					$data['data']=$this->Tipo_pantalon->get();
						break;
				}
				$this->load->view('administracion/catalogos',$data);
				//$this->load->view('administracionCatalogos',$data);
				//$this->load->view('footer');
			}
		}
	}

	public function CatalogosGuardar($catalogo)
	{
		if($this->input->post())
		{
			$opcion=$this->input->post()['opcion'];
			switch ($catalogo)
			{
				case 1:
					$this->load->model("Cliente");
					if ($opcion=='Editar')
					{
						$this->Cliente->update($this->input->post()['nombre'],$this->input->post()['direccion'],$this->input->post()['telefono'],$this->input->post()['id']);
						$data['cliente_id']=$this->input->post()['id'];
					}
					else
					{
						$data['nombre']=$this->input->post()['nombre'];
						$data['direccion']=$this->input->post()['direccion'];
						$data['telefono']=$this->input->post()['telefono'];
						$n=$this->Cliente->insert($data);
						$data=null;
						$data['cliente_id']=$n;
					}
					$this->load->model("ClienteHasMarca");
					$this->ClienteHasMarca->delete($this->input->post()['id']);

					foreach ($this->input->post()['marca'] as $key => $value)
					{
						$data['marca_id']=$value;
						$this->ClienteHasMarca->insert($data);
					}
						break;
				case 2:
					$this->load->model("Lavado");
					if ($opcion=='Editar')
						$this->Lavado->update($this->input->post()['nombre'],$this->input->post()['id']);
					else
					{
						$data['nombre']=$this->input->post()['nombre'];
						$this->Lavado->insert($data);
					}
						break;

				case 3:
					$this->load->model("Maquilero");
					if ($opcion=='Editar')
						$this->Maquilero->update($this->input->post()['nombre'],$this->input->post()['direccion'],$this->input->post()['telefono'],$this->input->post()['id']);
					else
					{
						$data['nombre']=$this->input->post()['nombre'];
						$data['direccion']=$this->input->post()['direccion'];
						$data['telefono']=$this->input->post()['telefono'];
						$this->Maquilero->insert($data);
					}
						break;
				case 4:
					$this->load->model("Marca");
					if ($opcion=='Editar')
						$this->Marca->update($this->input->post()['nombre'],$this->input->post()['id']);
					else
					{
						$data['nombre']=$this->input->post()['nombre'];
						$this->Marca->insert($data);
					}
					break;
				case 5:
					$this->load->model("ProcesoSeco");
					if ($opcion=='Editar')
						$this->ProcesoSeco->update($this->input->post()['nombre'],$this->input->post()['costo'],$this->input->post()['abreviatura'],$this->input->post()['id']);
					else
					{
						$data['nombre']=$this->input->post()['nombre'];
						$data['costo']=$this->input->post()['costo'];
						$data['abreviatura']=$this->input->post()['abreviatura'];
						$this->ProcesoSeco->insert($data);
					}
						break;

				case 6:
					$this->load->model("Usuarios");
					if ($opcion=='Editar')
						$this->Usuarios->update($this->input->post()['nombre'],$this->input->post()['pass'],$this->input->post()['tipo_usuario_id'],$this->input->post()['nombre_completo'],$this->input->post()['direccion'],$this->input->post()['telefono'],$this->input->post()['id']);
					else
					{
						$data['nombre']=$this->input->post()['nombre'];
						$data['pass']=md5($this->input->post()['pass']);
						$data['tipo_usuario_id']=$this->input->post()['tipo_usuario_id'];
						$data['nombre_completo']=$this->input->post()['nombre_completo'];
						$data['direccion']=$this->input->post()['direccion'];
						$data['telefono']=$this->input->post()['telefono'];
						$this->Usuarios->insert($data);
					}
						break;

					case 7:
						$this->load->model("Tipo_pantalon");
						if ($opcion=='Editar')
							$this->Tipo_pantalon->update($this->input->post()['nombre'],$this->input->post()['id']);
						else
						{
							$data['nombre']=$this->input->post()['nombre'];
							$this->Tipo_pantalon->insert($data);
						}
						break;
				}
				redirect('/administracion/index/-1');
		}
		else
		{
			redirect('/');
		}
	}
}
