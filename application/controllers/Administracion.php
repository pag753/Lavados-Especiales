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
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/index',$data);
		$this->load->view('comunes/foot');
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
				if (!isset($this->input->get()['folio']) || !$this->input->get()['carga'])
					redirect("/");
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
				$this->load->view('comunes/head');
				$this->load->view('administracion/menu');
				$this->load->view('administracion/cargaCosto',$datos);
				$this->load->view('comunes/foot');
			}
			else
			{
				$titulo['titulo'] = 'Cambiar costos';
				$textos['texto1'] = "Costos del corte";
				$this->load->view('comunes/head',$titulo);
				$this->load->view('administracion/menu');
				$this->load->view('administracion/cargaCostoValidacion',$textos);
				$this->load->view('comunes/foot');
			}
		}
	}

	//CATÁLOGOS
	public function catalogosClientes()
	{
		$this->load->model("Cliente");
		$data['data'] = $this->Cliente->get();
		$titulo['titulo'] = 'Catálogo de clientes';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosCliente',$data);
		$this->load->view('comunes/foot');
	}

	public function catalogosLavados()
	{
		$this->load->model("Lavado");
		$data['data'] = $this->Lavado->get();
		$titulo['titulo'] = 'Catálogo de lavados';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosLavado',$data);
		$this->load->view('comunes/foot');
	}

	public function catalogosMaquileros()
	{
		$this->load->model("Maquilero");
		$data['data'] = $this->Maquilero->get();
		$titulo['titulo'] = 'Catálogo de maquileros';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosMaquilero',$data);
		$this->load->view('comunes/foot');
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
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosMarca',$data);
		$this->load->view('comunes/foot');
	}

	public function catalogosProcesos()
	{
		$this->load->model("ProcesoSeco");
		$data['data'] = $this->ProcesoSeco->get();
		$titulo['titulo'] = 'Catálogo de procesos';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosProcesos',$data);
		$this->load->view('comunes/foot');
	}

	public function catalogosUsuarios()
	{
		$this->load->model(array("Usuarios","TipoUsuario","Puestos"));
		$data = array(
			'data' => $this->Usuarios->get(),
			'TipoUsuario' => $this->TipoUsuario->get(),
			'puestos' => $this->Puestos->get(),
		);
		$titulo['titulo'] = 'Catálogo de usuarios';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosUsuarios',$data);
		$this->load->view('comunes/foot');
	}

	public function catalogosTipos()
	{
		$this->load->model("Tipo_pantalon");
		$data['data'] = $this->Tipo_pantalon->get();
		$titulo['titulo'] = 'Catálogo de tipos de pantalón';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosTipoPantalon',$data);
		$this->load->view('comunes/foot');
	}

	public function catalogosPuestos()
	{
		$this->load->model("Puestos");
		$data['data'] = $this->Puestos->get();
		$titulo['titulo'] = 'Catálogo de puestos';
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('administracion/catalogosPuestos',$data);
		$this->load->view('comunes/foot');
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
				'puesto_id' => trim($this->input->post()['puesto_id']),
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
				trim($this->input->post()['puesto_idE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosUsuarios");
		}
		else
		redirect("/");
	}

	public function nuevoPuesto()
	{
		if ($this->input->post())
		{
			$this->load->model("Puestos");
			$data = array(
				'nombre' => $this->input->post()['nombre'],
			);
			$this->Puestos->insert($data);
			redirect("/administracion/catalogosPuestos");
		}
		else
		redirect("/");
	}

	public function editarPuesto()
	{
		if ($this->input->post())
		{
			$this->load->model("Puestos");
			$this->Puestos->update(
				trim($this->input->post()['nombreE']),
				$this->input->post()['id']
			);
			redirect("/administracion/catalogosPuestos");
		}
		else
		redirect("/");
	}

	public function cambiarPass()
	{
		if ($this->input->post())
		{
			$this->load->model('Puestos');
			$this->Usuarios->updateP($_SESSION['usuario_id'],md5($this->input->post()['pass1']));
			redirect('/administracion/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/administracion/cambiarPass';
			$titulo['titulo'] = 'Cambiar contraseña';
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
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
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('comunes/cambiarDatos',$data);
			$this->load->view('comunes/foot');
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
				$data['usuario'] = $this->Usuarios->getById($id);
				if (count($data['usuario'])==0)
					redirect("/");
				else
				{
					$titulo="Descuentos del operario ".$data['usuario'][0]['nombre'];
					$this->load->view('comunes/head',$titulo);
					$this->load->view('administracion/menu');
					$this->load->view('administracion/descuentosEspecifico',$data);
					$this->load->view('comunes/foot');
				}
			}
		}
		else
		{
			$this->load->model("Usuarios");
			$data['data'] = $this->Usuarios->getOperarios();
			$titulo="Descuentos";
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/descuentos',$data);
			$this->load->view('comunes/foot');
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
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/ojal',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			$this->ojal->update($this->input->post()['costo']);
			redirect("administracion\index\-1");
		}
	}

	public function ver()
	{
		$titulo['titulo'] = "Ver detalles de corte";
		$this->load->view('comunes/head',$titulo);
		$this->load->view('administracion/menu');
		$this->load->view('comunes/ver');
		$this->load->view('comunes/foot');
	}

	//Funciones para modificar corte
	public function modificar()
	{
		if ($this->input->get())
		{
			//Cargar modelos
			$this->load->model(array('Cliente','Lavado','Maquilero','Marca','ProcesoSeco','Tipo_pantalon','Corte','corteAutorizado','Usuarios','CorteAutorizadoDatos','SalidaInterna1','SalidaInterna1Datos','ProduccionProcesoSeco'));
			$folio = $this->input->get()['folio'];
			if($folio == '')
				redirect('/');
			//DETALLES DEL CORTE
			$corte = $this->Corte->getByFolio($folio);
			if(count($corte) == 0)
				redirect('/');
			$corte = $corte[0];
			$extensiones = array("jpg","jpeg","png");
			$ban=false;
			foreach ($extensiones as $key2 => $extension)
			{
				$url = base_url()."img/fotos/".$folio.".".$extension;
				$headers = get_headers($url);
				if (stripos($headers[0],"200 OK"))
				{
					$ban=true;
					$imagen="<img src='".base_url()."img/fotos/".$folio.".".$extension."' class='img-fluid' alt='Responsive image'>";
					break;
				}
			}
			if (!$ban)
				$imagen="No hay imágen";
			$corte['imagen'] = $imagen;
			$data['generales'] = $corte;
			$data['clientes'] = $this->Cliente->get();
			$data['lavados'] = $this->Lavado->get();
			$data['maquileros'] = $this->Maquilero->get();
			$data['marcas'] = $this->Marca->get();
			$data['procesosecos'] = $this->ProcesoSeco->get();
			$data['jsonProcesos'] = json_encode($data['procesosecos']);
			$data['tipo'] = $this->Tipo_pantalon->get();
			$data['usuarios'] =$this->Usuarios->get();
			//Cargar datos de autorización de corte
			$autorizado = $this->corteAutorizado->getByFolio($folio);
			if (count($autorizado) == 0)
				$data['autorizado'] = 0;
			else
				$data['autorizado'] = $autorizado[0];
			//Cargar autorización datos de corte
			$autorizadoDatos = $this->CorteAutorizadoDatos->getByFolio($folio);
			if (count($autorizadoDatos) == 0)
				$data['autorizadoDatos'] = 0;
			else
				$data['autorizadoDatos'] = $autorizadoDatos;
			//Cargar Salida Interna
			$salidaInterna = $this->SalidaInterna1->getByFolio($folio);
			if (count($salidaInterna) == 0)
				$data['salidaInterna'] = 0;
			else
				$data['salidaInterna'] = $salidaInterna[0];
			//Cargar Salida Interna Datos
			$salidaInternaDatos = $this->SalidaInterna1Datos->getByFolioEspecifico($folio);
			if (count($salidaInternaDatos) == 0)
				$data['salidaInternaDatos'] = 0;
			else
				$data['salidaInternaDatos'] = $salidaInternaDatos;
			//Cargar datos de producción de proceso seco
			$produccionProcesoSeco = $this->ProduccionProcesoSeco->seleccionReporte($folio);
			if (count($produccionProcesoSeco) == 0)
				$data['produccionProcesoSeco'] = 0;
			else
				$data['produccionProcesoSeco'] = $produccionProcesoSeco;
			//Cargar los lavados del corte con sus cargas
			$lavadosCorte = $this->CorteAutorizadoDatos->getLavadosByFolio($folio);
			if (count($lavadosCorte) == 0)
				$data['lavadosCorte'] = 0;
			else
				$data['lavadosCorte'] = $lavadosCorte;
			//CARGAR VISTAS
			$titulo['titulo'] = "Modificar Corte con folio ".$this->input->get()['folio'];
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/modificarCorte',$data);
			$this->load->view('comunes/foot');
		}
		else
		{
			//Cargar vistas
			$titulo['titulo'] = "Modificar Corte";
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/modificar');
			$this->load->view('comunes/foot');
		}
	}

	public function modificarGenerales()
	{
		if (!$this->input->post())
		redirect('/');
		$this->load->Model('Corte');
		$this->Corte->update($this->input->post());
		return json_encode(array('respuesta' => true, ));
	}

	public function modificarImagen()
	{
		if (!$this->input->post())
		redirect('/');
		else {
			$folio = $this->input->post()['folioCambiarImagen'];
			//Eliminar la imágen actualizaCosto
			$extensiones = array("jpg","jpeg","png");
			foreach ($extensiones as $key2 => $extension)
			{
				//$ruta = __DIR__."../../img/fotos/".$folio.".".$extension;
				$ruta = "img/fotos/".$folio.".".$extension;
				if (is_file($ruta))
				{
					unlink($ruta);
					break;
				}
			}
			//Subir la imagen nueva
			$mi_imagen = 'mi_imagen';
			$config = array(
				'upload_path' => "img/fotos",
				'file_name' => $folio,
				'allowed_types' => "gif|jpg|jpeg|png",
				'max_size' => "500000",
				'max_width' => "20000",
				'max_height' => "20000"
			);
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload($mi_imagen))
			$data['uploadError'] = $this->upload->display_errors();
			$data['uploadSuccess'] = $this->upload->data();
			//Retornar
			redirect('/administracion/modificar?folio='.$this->input->post()['folio']);
		}
	}

	public function editarAutorizacion()
	{
		if (!$this->input->post())
		redirect('/');
		$this->load->model('CorteAutorizado');
		$this->CorteAutorizado->update($this->input->post());
		echo json_encode(array('respuesta' => true, ));
	}

	public function eliminarAutorizacion()
	{
		if (!$this->input->post())
		redirect('/');
		$folio = $this->input->post()['folio'];
		$this->load->model(array('ProduccionProcesoSeco','SalidaInterna1Datos','SalidaInterna1','CorteAutorizadoDatos','CorteAutorizado'));
		//Eliminar datos de producción de proceso Seco
		$this->ProduccionProcesoSeco->deleteByFolio($folio);
		//Eliminar datos de salida interna
		$this->SalidaInterna1Datos->deleteByFolio($folio);
		$this->SalidaInterna1->deleteByFolio($folio);
		//Eliminar datos de corte autorizado
		$this->CorteAutorizadoDatos->deleteByFolio($folio);
		$this->CorteAutorizado->deleteByFolio($folio);
		//Enviar respuesta
		echo json_encode(array('respuesta' => true, ));
	}

	public function editarAutorizacionDatos()
	{
		if (!$this->input->post())
		redirect("/");
		$this->load->model("CorteAutorizadoDatos");
		$this->CorteAutorizadoDatos->update($this->input->post());
		echo json_encode(array('respuesta' => true, ));
	}

	public function eliminarAutorizacionDatos()
	{
		if (!$this->input->post())
		redirect("/");
		$this->load->model("CorteAutorizadoDatos");
		$this->CorteAutorizadoDatos->deleteByID($this->input->post()['id']);
		echo json_encode(array('respuesta' => true, ));
	}

	public function editarSalidaInterna()
	{
		if (!$this->input->post())
		redirect("/");
		$this->load->model("SalidaInterna1");
		$this->SalidaInterna1->update($this->input->post());
		echo json_encode(array('respuesta' => true, ));
	}

	public function eliminarSalidaInterna()
	{
		if (!$this->input->post())
		redirect('/');
		$folio = $this->input->post()['folio'];
		$this->load->model(array('ProduccionProcesoSeco','SalidaInterna1Datos','SalidaInterna1'));
		//Eliminar datos de producción de proceso Seco
		$this->ProduccionProcesoSeco->deleteByFolio($folio);
		//Eliminar datos de salida interna
		$this->SalidaInterna1Datos->deleteByFolio($folio);
		$this->SalidaInterna1->deleteByFolio($folio);
		//Enviar respuesta
		echo json_encode(array('respuesta' => true, ));
	}

	public function editarSalidaInternaDatos()
	{
		if (!$this->input->post())
			redirect('/');
		$this->load->model(array("SalidaInterna1Datos"));
		//Actualizar salida_interna1_datos(piezas) con : id_carga, corte_folio
		$data = array(
			'piezas' => $this->input->post()['piezas'],
			'id_carga' => $this->input->post()['id_carga'],
			'corte_folio' => $this->input->post()['folio'],
		);
		$this->SalidaInterna1Datos->updateAdministracion($data);
		//Regresar
		echo json_encode(array('respuesta' => true, ));
	}

	public function editarLavadoCorte()
	{
		if (!$this->input->post())
			redirect('/');
			$this->load->model(array("CorteAutorizadoDatos","SalidaInterna1Datos","ProduccionProcesoSeco"));
			//Actualizar corte_autorizado_datos(lavado_id) con: corte_folio, id_carga
			$data  = array(
				'lavado_id' => $this->input->post()['lavado_id'],
				'corte_folio' => $this->input->post()['folio'],
				'id_carga' => $this->input->post()['id_carga'],
			);
			$this->CorteAutorizadoDatos->updateAdministracion($data);
			//Actualizar produccion_proceso_seco(lavado_id) con: carga, corte_folio
			$data = array(
				'lavado_id' => $this->input->post()['lavado_id'],
				'carga' => $this->input->post()['id_carga'],
				'corte_folio' => $this->input->post()['folio'],
			);
			$this->ProduccionProcesoSeco->updateAdministracion($data);
			//Regresar
			echo json_encode(array('respuesta' => true, ));
	}

	public function eliminarLavadoCorte()
	{
		if (!$this->input->post())
			redirect('/');
		$this->load->model(array("CorteAutorizadoDatos","SalidaInterna1Datos","ProduccionProcesoSeco","CorteAutorizado"));
		//Eliminar corte_autorizado_datos con: corte_folio, id_carga
		$data  = array(
			'corte_folio' => $this->input->post()['folio'],
			'id_carga' => $this->input->post()['id_carga'],
		);
		$this->CorteAutorizadoDatos->deleteAdministracion($data);
		//Eliminar salida_interna1_datos con : id_carga, corte_folio
		$data = array(
			'id_carga' => $this->input->post()['id_carga'],
			'corte_folio' => $this->input->post()['folio'],
		);
		$this->SalidaInterna1Datos->deleteAdministracion($data);
		//Eliminar produccion_proceso_seco con: carga, corte_folio
		$data = array(
			'carga' => $this->input->post()['id_carga'],
			'corte_folio' => $this->input->post()['folio'],
		);
		$this->ProduccionProcesoSeco->deleteAdministracion($data);
		//Disminuir en 1 las cargas
		$this->CorteAutorizado->disminuyeCargasEn1($this->input->post()['folio']);
		//Regresar
		echo json_encode(array('respuesta' => true, ));
	}

	public function editarProduccion() {
		if (!$this->input->post())
			redirect('/');
		$this->load->model("ProduccionProcesoSeco");
		$this->ProduccionProcesoSeco->updateById($this->input->post());
		//Regresar
		echo json_encode(array('respuesta' => true, ));
	}

	public function eliminarProduccion() {
		if (!$this->input->post())
			redirect('/');
		$this->load->model("ProduccionProcesoSeco");
		$this->ProduccionProcesoSeco->deleteById($this->input->post()['id']);
		//Regresar
		echo json_encode(array('respuesta' => true, ));
	}

	public function agregarLavado()
	{
		if (!$this->input->post())
			redirect("/");
		$this->load->model(array('CorteAutorizado','CorteAutorizadoDatos','ProcesoSeco'));
		//Aumentar la cargas
		$this->CorteAutorizado->aumentaCargasEn1($this->input->post()['corteFolioNuevoLavado']);
		//Agregar en corte_autorizado_datos
		//Encontrar el id de la carga mas alta
		$carga = $this->CorteAutorizadoDatos->getCargaMaxima($this->input->post()['corteFolioNuevoLavado']);
		$idCarga = (count($carga)!=0) ? $carga[0]['maxima']+1 : 1 ;
		//recuperar procesos
		$procesos = $this->input->post()['procesoNuevo'];
		//si hay salida interna
		if (isset($this->input->post()['piezasLavadoNuevo']))
		{
			$this->load->model('SalidaInterna1Datos');
			//insertar en corte_autorizado_datos
			foreach ($procesos as $key => $value)
			{
				//Costo del proceso seco
				$costo=$this->ProcesoSeco->getById($value)[0]['costo'];
				//Llenar arreglo
				$data['corte_folio'] = $this->input->post()['corteFolioNuevoLavado'];
				$data['id_carga'] = $idCarga;
				$data['lavado_id'] = $this->input->post()['lavadoProcesoNuevo'];
				$data['proceso_seco_id'] = $value;
				$data['costo'] = $costo;
				$data['defectos'] = 0;
				$data['orden'] = 0;
				$data['fecha_registro'] = date('Y-m-d');
				$data['usuario_id'] = $_SESSION['usuario_id'];
				if ($value == $this->input->post()['abrirConProceso'])
				{
					$data['piezas_trabajadas'] = $this->input->post()['piezasLavadoNuevo'];
					$data['status'] = 1;
				}
				else
				{
					$data['piezas_trabajadas'] = 0;
					$data['status'] = 0;
				}
				$this->CorteAutorizadoDatos->agregar($data);
			}
			//Insrtar en salida_interna1_datos
			$this->load->model('SalidaInterna1Datos');
			$data = array(
				'id_carga' => $idCarga,
				'piezas' => $this->input->post()['piezasLavadoNuevo'],
				'corte_folio' => $this->input->post()['corteFolioNuevoLavado'],
			);
			$this->SalidaInterna1Datos->agregar($data);
		}
		//si no hay salida interna
		else
		{
			foreach ($procesos as $key => $value)
			{
				$costo=$this->ProcesoSeco->getById($value)[0]['costo'];
				//Llenar arreglo
				$data['corte_folio'] = $this->input->post()['corteFolioNuevoLavado'];
				$data['id_carga'] = $idCarga;
				$data['lavado_id'] = $this->input->post()['lavadoProcesoNuevo'];
				$data['proceso_seco_id'] = $value;
				$data['costo'] = $costo;
				$data['defectos'] = 0;
				$data['orden'] = 0;
				$data['fecha_registro'] = date('Y-m-d');
				$data['usuario_id'] = $_SESSION['usuario_id'];
				$data['piezas_trabajadas'] = 0;
				$data['status'] = 0;
			}
		}
		redirect('/administracion/modificar?folio='.$this->input->post()['folio']);
	}

	public function ahorros()
	{
		if ($this->input->get())
		{
			$id=$this->input->get()['id'];
			if ($id=='')
				redirect("/");
			$this->load->model("Ahorros");
			$this->load->model("Usuarios");
			$data['ahorros'] = $this->Ahorros->getByIdUsuario($id);
			$data['usuario'] = $this->Usuarios->getById($id);
			if (count($data['usuario'])==0)
				redirect("/");
			else
			{
				$titulo="Ahorros del operario ".$data['usuario'][0]['nombre'];
				$this->load->view('comunes/head',$titulo);
				$this->load->view('administracion/menu');
				$this->load->view('administracion/ahorrosEspecifico',$data);
				$this->load->view('comunes/foot');
			}
		}
		else
		{
			$this->load->model("Usuarios");
			$data['data'] = $this->Usuarios->getOperarios();
			$titulo="Ahorros";
			$this->load->view('comunes/head',$titulo);
			$this->load->view('administracion/menu');
			$this->load->view('administracion/ahorros',$data);
			$this->load->view('comunes/foot');
		}
	}

	public function nuevoAhorro() {
		if (!$this->input->post())
			redirect('/');
		$this->load->model('Ahorros');
		$data = array(
				'fecha' => $this->input->post()['fecha'],
				'cantidad' => $this->input->post()['cantidad'],
				'usuario_id' => $this->input->post()['id'],
				'aportacion' => $this->input->post()['aportacion'],
		);
		$this->Ahorros->insert($data);
		redirect("administracion/ahorros?id=".$this->input->post()['id']);
	}

	public function editarAhorro() {
		if (!$this->input->post())
			redirect('/');
		$this->load->model('Ahorros');
		$this->Ahorros->update(
			$this->input->post()['aportacionE'],
			$this->input->post()['fechaE'],
			$this->input->post()['cantidadE'],
			$this->input->post()['idE']
		);
		redirect("administracion/ahorros?id=".$this->input->post()['idUsuario']);
	}

	public function eliminarAhorro()
	{
		if (!$this->input->post())
			redirect("/");
		$this->load->model("Ahorros");
		$this->Ahorros->delete($this->input->post()['id']);
		echo json_encode(array('respuesta' => true ));
	}
}
