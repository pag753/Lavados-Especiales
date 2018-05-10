<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administracion extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$idusuario=$_SESSION['id'];
		if ($idusuario!=1 && $idusuario!=5)
			redirect('/');
	}

	public function index($datos=null)
	{
		if ($datos == null)
			$data = array(
				'texto1' => 'Bienvenido(a)',
				'texto2' => $_SESSION['username']
			);
		elseif ($datos == -1)
			$data = array(
				'texto1' => 'Los datos',
				'texto2' => 'Se han registrado con éxito'
			);
		else
			$data = array(
				'texto1' => "El corte con folio ".$datos,
				'texto2' => "Se ha registrado con éxito"
			);
		$titulo['titulo'] = 'Bienvenido a lavados especiales';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/index',$data);
		$this->load->view('foot');
	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function costos()
	{
		if ($this->input->post())
		{
			$this->load->model('corteAutorizadoDatos');
			foreach ($this->input->post()['costo'] as $key => $value)
				$query=$this->corteAutorizadoDatos->actualizaCosto(
					$this->input->post()['folio'],
					$this->input->post()['carga'],
					$key,
					$value,
					$this->input->post()['idlavado']
				);
			redirect('/administracion/index/'.$this->input->post()['folio']);
		}
		else
		{
			if ($this->input->get())
			{
				$folio = $this->input->get()['folio'];
				$cargaid = $this->input->get()['carga'];
				$datos['carga'] = $cargaid;
				$this->load->model('corte');
				$datos['texto1'] = "Asignación de costos";
				$datos['texto2'] = "Inserte la información";
				$query = $this->corte->getByFolioGeneral($folio);
				$datos['folio'] = $folio;
				$datos['corte'] = $query[0]['corte'];
				$datos['marca'] = $query[0]['marca'];
				$datos['maquilero'] = $query[0]['maquilero'];
				$datos['cliente'] = $query[0]['cliente'];
				$datos['tipo'] = $query[0]['tipo'];
				$datos['piezas'] = $query[0]['piezas'];
				$datos['fecha'] = $query[0]['fecha'];
				$datos['ojales'] = $query[0]['ojales'];
				$this->load->model('corteAutorizadoDatos');
				$query = $this->corteAutorizadoDatos->joinLavadoProcesosCarga($folio,$cargaid);
				$datos['lavado'] = $query[0]['lavado'];
				$datos['idlavado'] = $query[0]['idlavado'];				
				foreach ($query as $key => $value)
				{
					$datos['procesos'][$value['idproceso']] = $value['proceso'];
					$datos['costos'][$value['idproceso']] = $value['costo'];
				}
				//Buscar la imágen
				$extensiones = array("jpg","jpeg","png");
				$ban=false;
				foreach ($extensiones as $key2 => $extension)
				{
					$file = "/var/www/html/lavanderia/img/fotos/".$folio.".".$extension;
					if (is_file($file))
					{
						$ban=true;
						$imagen="<img src='".base_url()."img/fotos/".$folio.".".$extension."' class='img-fluid' alt='Responsive image'>";
						break;
					}
				}
				if (!$ban)
					$imagen = "No hay imagen";
				$datos['imagen'] = $imagen;
				$this->load->view('head');
				$this->load->view('administracion/menu');
				$this->load->view('administracion/cargaCosto',$datos);
				$this->load->view('foot');
			}
			else
			{
				$titulo['titulo'] = 'Cambiar costos';
				$textos['texto1'] = "Costos del corte";
				$this->load->view('head',$titulo);
				$this->load->view('administracion/menu');
				$this->load->view('administracion/cargaCostoValidacion',$textos);
				$this->load->view('foot');
			}
		}
	}

	//CATÁLOGOS
	public function catalogosClientes()
	{
		$this->load->model("Cliente");
		$data['data'] = $this->Cliente->get();
		$titulo['titulo'] = 'Catálogo de clientes';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosCliente',$data);
		$this->load->view('foot');
	}

	public function catalogosLavados()
	{
		$this->load->model("Lavado");
		$data['data'] = $this->Lavado->get();
		$titulo['titulo'] = 'Catálogo de lavados';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosLavado',$data);
		$this->load->view('foot');
	}

	public function catalogosMaquileros()
	{
		$this->load->model("Maquilero");
		$data['data'] = $this->Maquilero->get();
		$titulo['titulo'] = 'Catálogo de maquileros';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosMaquilero',$data);
		$this->load->view('foot');
	}

	public function catalogosMarcas()
	{
		$this->load->model("Marca");
		$this->load->model("Cliente");
		$data = array(
			'data' => $this->Marca->getJoin(),
			'clientes' => $this->Cliente->get(),
		);
		$titulo['titulo'] = 'Catálogo de marcas';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosMarca',$data);
		$this->load->view('foot');
	}

	public function catalogosProcesos()
	{
		$this->load->model("ProcesoSeco");
		$data['data'] = $this->ProcesoSeco->get();
		$titulo['titulo'] = 'Catálogo de procesos';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosProcesos',$data);
		$this->load->view('foot');
	}

	public function catalogosUsuarios()
	{
		$this->load->model("Usuarios");
		$this->load->model("TipoUsuario");
		$data = array(
			'data' => $this->Usuarios->get(),
			'TipoUsuario' => $this->TipoUsuario->get(),
		);
		$titulo['titulo'] = 'Catálogo de usuarios';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosUsuarios',$data);
		$this->load->view('foot');
	}

	public function catalogosTipos()
	{
		$this->load->model("Tipo_pantalon");
		$data['data'] = $this->Tipo_pantalon->get();
		$titulo['titulo'] = 'Catálogo de tipos de pantalón';
		$this->load->view('head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosTipoPantalon',$data);
		$this->load->view('foot');
	}

	public function nuevoCliente()
	{
		if ($this->input->post())
		{
			$this->load->model("Cliente");
			$data = array(
				'nombre' => trim($this->input->post()['nombre']),
				'direccion' => trim($this->input->post()['direccion']),
				'telefono' => trim($this->input->post()['telefono']),
			);
			$this->Cliente->insert($data);
			redirect("/administracion/catalogosClientes");
		}
		else
			redirect("/");
	}

	public function editarCliente()
	{
		if ($this->input->post())
		{
			$this->load->model("Cliente");
			$this->Cliente->update(
				trim($this->input->post()['nombreE']),
				trim($this->input->post()['direccionE']),
				trim($this->input->post()['telefonoE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosClientes");
		}
		else
			redirect("/");
	}

	public function nuevoLavado()
	{
		if ($this->input->post())
		{
			$this->load->model("Lavado");
			$data['nombre'] = trim($this->input->post()['nombre']);
			$this->Lavado->insert($data);
			redirect("/administracion/catalogosLavados");
		}
		else
			redirect("/");
	}

	public function editarLavado()
	{
		if ($this->input->post())
		{
			$this->load->model("Lavado");
			$this->Lavado->update(
				trim($this->input->post()['nombreE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosLavados");
		}
		else
			redirect("/");
	}

	public function nuevoMaquilero()
	{
		if ($this->input->post())
		{
			$this->load->model("Maquilero");
			$data = array(
				'nombre' => trim($this->input->post()['nombre']),
				'direccion' => trim($this->input->post()['direccion']),
				'telefono' => trim($this->input->post()['telefono']),
			);
			$this->Maquilero->insert($data);
			redirect("/administracion/catalogosMaquileros");
		}
		else
			redirect("/");
	}

	public function editarMaquilero()
	{
		if ($this->input->post())
		{
			$this->load->model("Maquilero");
			$this->Maquilero->update(
				trim($this->input->post()['nombreE']),
				trim($this->input->post()['direccionE']),
				trim($this->input->post()['telefonoE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosMaquileros");
		}
		else
			redirect("/");
	}

	public function nuevoMarca()
	{
		if ($this->input->post())
		{
			$this->load->model("Marca");
			$data = array(
				'nombre' => trim($this->input->post()['nombre']),
				'cliente_id' => trim($this->input->post()['cliente']),
			);
			$this->Marca->insert($data);
			redirect("/administracion/catalogosMarcas");
		}
		else
			redirect("/");
	}

	public function editarMarca()
	{
		if ($this->input->post())
		{
			$this->load->model("Marca");
			$this->Marca->update(
				trim($this->input->post()['nombreE']),
				trim($this->input->post()['clienteE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosMarcas");
		}
		else
			redirect("/");
	}

	public function nuevoProceso()
	{
		if ($this->input->post())
		{
			$this->load->model("ProcesoSeco");
			$data = array(
				'nombre' => trim($this->input->post()['nombre']),
				'costo' => trim($this->input->post()['costo']),
				'abreviatura' => trim($this->input->post()['abreviatura']),
			);
			$this->ProcesoSeco->insert($data);
			redirect("/administracion/catalogosProcesos");
		}
		else
			redirect("/");
	}

	public function editarProceso()
	{
		if ($this->input->post())
		{
			$this->load->model("ProcesoSeco");
			$this->ProcesoSeco->update(
				trim($this->input->post()['nombreE']),
				trim($this->input->post()['costoE']),
				trim($this->input->post()['abreviaturaE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosProcesos");
		}
		else
			redirect("/");
	}

	public function nuevoTipo()
	{
		if ($this->input->post())
		{
			$this->load->model("Tipo_pantalon");
			$data['nombre'] = trim($this->input->post()['nombre']);
			$this->Tipo_pantalon->insert($data);
			redirect("/administracion/catalogosTipos");
		}
		else
			redirect("/");
	}

	public function editarTipo()
	{
		if ($this->input->post())
		{
			$this->load->model("Tipo_pantalon");
			$this->Tipo_pantalon->update(
				trim($this->input->post()['nombreE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosTipos");
		}
		else
			redirect("/");
	}

	public function nuevoUsuario()
	{
		if ($this->input->post())
		{
			$this->load->model("Usuarios");
			$data = array(
				'pass' => md5($this->input->post()['pass']),
				'tipo_usuario_id' => $this->input->post()['tipo_usuario_id'],
				'nombre' => $this->input->post()['nombre'],				
				'nombre_completo' => trim($this->input->post()['nombre_completo']),
				'direccion' => trim($this->input->post()['direccion']),
				'telefono' => trim($this->input->post()['telefono']),
				'activo' => trim($this->input->post()['activo']),
			);
			$this->Usuarios->insert($data);
			redirect("/administracion/catalogosUsuarios");
		}
		else
			redirect("/");
	}

	public function editarUsuario()
	{
		if ($this->input->post())
		{
			$this->load->model("Usuarios");
			$this->Usuarios->update(
				trim($this->input->post()['nombreE']),
				trim($this->input->post()['passE']),
				trim($this->input->post()['tipo_usuario_idE']),
				trim($this->input->post()['nombre_completoE']),
				trim($this->input->post()['direccionE']),
				trim($this->input->post()['telefonoE']),
				trim($this->input->post()['activoE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosUsuarios");
		}
		else
			redirect("/");
	}

	public function cambiarPass()
	{
		if ($this->input->post())
		{
			$this->load->model('Usuarios');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/administracion/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/administracion/cambiarPass';
			$titulo['titulo'] = 'Cambiar contraseña';
			$this->load->view('head',$titulo);
			$this->load->view('administracion/menu');
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
				$_SESSION['usuario_id'],
				$this->input->post()['nombre_completo'],
				$this->input->post()['direccion'],
				$this->input->post()['telefono']
			);
			redirect('/administracion/index/-1');
		}
		else
		{
			$this->load->model('Usuarios');
			$data = array(
				'link' => base_url().'index.php/administracion/datos',
				'data' => $this->Usuarios->getById($_SESSION['usuario_id']),
			);
			$titulo['titulo'] = 'Cambiar datos personales';
			$this->load->view('head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
		}
	}

	public function descuentos()
	{
		if ($this->input->get())
		{
			$id=$this->input->get()['id'];
			if ($id=='')
				redirect("/");
			else
			{
				$this->load->model("Descuentos");
				$this->load->model("Usuarios");
				$data['descuentos'] = $this->Descuentos->getByIdUsuario($id);
				$data['usuario'] = $this->Usuarios->getById($id);;
				if (count($data['usuario'])==0)
					redirect("/");
				else
				{
					$titulo="Descuentos del operario ".$data['usuario'][0]['nombre'];
					$this->load->view('head',$titulo);
					$this->load->view('administracion/menu');
					$this->load->view('administracion/descuentosEspecifico',$data);
					$this->load->view('foot');
				}
			}
		}
		else
		{
			$this->load->model("Usuarios");
			$data['data'] = $this->Usuarios->getOperarios();
			$titulo="Descuentos";
			$this->load->view('head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/descuentos',$data);
			$this->load->view('foot');
		}
	}

	public function editarDescuento()
	{
		if (!$this->input->post())
			redirect("/");
		else
		{
			$this->load->model("Descuentos");
			$this->Descuentos->update(
				trim($this->input->post()['razonE']),
				trim($this->input->post()['fechaE']),
				trim($this->input->post()['cantidadE']),
				trim($this->input->post()['idE'])
			);
			redirect('/administracion/descuentos/?id='.$this->input->post()['idUsuario']);
		}
	}

	public function nuevoDescuento()
	{
		if (!$this->input->post())
			redirect("/");
		else
		{
			$this->load->model("Descuentos");
			$data = array(
				'fecha' => $this->input->post()['fecha'],
				'razon' => trim( $this->input->post()['razon']),
				'usuario_id' => $this->input->post()['id'],
				'cantidad' => $this->input->post()['cantidad'] 
			);
			$this->Descuentos->insert($data);
			redirect('/administracion/descuentos/?id='.$data['usuario_id']);
		}
	}

	public function eliminarDescuento()
	{
		if (!$this->input->post()) 
			redirect("/");
		else
		{
			$id=$this->input->post()['id'];
			$this->load->model("Descuentos");
			$this->Descuentos->delete($id);
		}
	}

	public function ojal()
	{
		$this->load->model("ojal");
		if (!$this->input->post()) 
		{
			$data['costo'] = $this->ojal->get()[0]['costo'];
			$titulo['titulo'] = "Costo de ojal";
			$this->load->view('head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/ojal',$data);
			$this->load->view('foot');
		} 
		else 
		{
			$this->ojal->update($this->input->post()['costo']);
			redirect("administracion\index\-1");
		}		
	}
}