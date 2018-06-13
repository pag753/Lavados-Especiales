<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Administracion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $idusuario = $_SESSION['id'];
        if ($idusuario != 1 && $idusuario != 5)
            redirect('/');
    }

    public function index($datos = null)
    {
        if ($this->input->get())
        {
            switch ($this->input->get()['q'])
            {
                case 'error':
                    $data = array(
                        'texto1' => "Sucedió un error",
                        'texto2' => "Favor de reportarlo"
                    );
                    break;
                
                case 'reproceso':
                    $data = array(
                        'texto1' => "El reproceso",
                        'texto2' => "Se ha registrado con éxito"
                    );
                    break;
                
                case 'folio':
                    $data = array(
                        'texto1' => "El corte con folio " . $this->input->get()['folio'],
                        'texto2' => "Se ha registrado con éxito"
                    );
                    break;
                case 'noCorte':
                    $data = array(
                        'texto1' => "El corte",
                        'texto2' => "No contiene registros de producción"
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
            {
                $data = array(
                    'texto1' => 'Bienvenido(a)',
                    'texto2' => $_SESSION['username']
                );
            }
            elseif ($datos == - 1)
            {
                $data = array(
                    'texto1' => 'Los datos',
                    'texto2' => 'Se han registrado con éxito'
                );
            }
            else
            {
                $data = array(
                    'texto1' => "El corte con folio " . $datos,
                    'texto2' => "Se ha registrado con éxito"
                );
            }
        }
        $titulo['titulo'] = 'Bienvenido a lavados especiales';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/index', $data);
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
                $query = $this->corteAutorizadoDatos->actualizaCosto($this->input->post()['folio'], $this->input->post()['carga'], $key, $value, $this->input->post()['idlavado']);
            redirect('/administracion/index?q=folio&' . $this->input->post()['folio']);
        }
        else
        {
            if ($this->input->get())
            {
                if (! isset($this->input->get()['folio']) || ! isset($this->input->get()['carga']))
                    redirect('/administracion/index?q=error');
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
                $query = $this->corteAutorizadoDatos->joinLavadoProcesosCarga($folio, $cargaid);
                $datos['lavado'] = $query[0]['lavado'];
                $datos['idlavado'] = $query[0]['idlavado'];
                foreach ($query as $key => $value)
                {
                    $datos['procesos'][$value['idproceso']] = $value['proceso'];
                    $datos['costos'][$value['idproceso']] = $value['costo'];
                }
                // Buscar la imágen
                $extensiones = array(
                    "jpg",
                    "jpeg",
                    "png"
                );
                $ban = false;
                foreach ($extensiones as $key2 => $extension)
                {
                    // $file = "/var/www/html/lavanderia/img/fotos/".$folio.".".$extension;
                    $file = __DIR__ . "/../../img/fotos/" . $folio . "." . $extension;
                    if (is_file($file))
                    {
                        $ban = true;
                        $imagen = "<img src='" . base_url() . "img/fotos/" . $folio . "." . $extension . "' class='img-fluid' alt='Responsive image'>";
                        break;
                    }
                }
                if (! $ban)
                    $imagen = "No hay imagen";
                $datos['imagen'] = $imagen;
                $this->load->view('comunes/head');
                $this->load->view('administracion/menu');
                $this->load->view('administracion/cargaCosto', $datos);
                $this->load->view('comunes/foot');
            }
            else
            {
                $titulo['titulo'] = 'Cambiar costos';
                $textos['texto1'] = "Costos del corte";
                $this->load->view('comunes/head', $titulo);
                $this->load->view('administracion/menu');
                $this->load->view('administracion/cargaCostoValidacion', $textos);
                $this->load->view('comunes/foot');
            }
        }
    }

    // CATÁLOGOS
    public function catalogosClientes()
    {
        $this->load->model("Cliente");
        $data['data'] = $this->Cliente->get();
        $titulo['titulo'] = 'Catálogo de clientes';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosCliente', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosLavados()
    {
        $this->load->model("Lavado");
        $data['data'] = $this->Lavado->get();
        $titulo['titulo'] = 'Catálogo de lavados';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosLavado', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosMaquileros()
    {
        $this->load->model("Maquilero");
        $data['data'] = $this->Maquilero->get();
        $titulo['titulo'] = 'Catálogo de maquileros';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosMaquilero', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosMarcas()
    {
        $this->load->model("Marca");
        $this->load->model("Cliente");
        $data = array(
            'data' => $this->Marca->getJoin(),
            'clientes' => $this->Cliente->get()
        );
        $titulo['titulo'] = 'Catálogo de marcas';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosMarca', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosProcesos()
    {
        $this->load->model("ProcesoSeco");
        $data['data'] = $this->ProcesoSeco->get();
        $titulo['titulo'] = 'Catálogo de procesos';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosProcesos', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosUsuarios()
    {
        $this->load->model(array(
            "Usuarios",
            "TipoUsuario",
            "Puestos"
        ));
        $data = array(
            'data' => $this->Usuarios->get(),
            'TipoUsuario' => $this->TipoUsuario->get(),
            'puestos' => $this->Puestos->get()
        );
        $titulo['titulo'] = 'Catálogo de usuarios';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosUsuarios', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosTipos()
    {
        $this->load->model("Tipo_pantalon");
        $data['data'] = $this->Tipo_pantalon->get();
        $titulo['titulo'] = 'Catálogo de tipos de pantalón';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosTipoPantalon', $data);
        $this->load->view('comunes/foot');
    }

    public function catalogosPuestos()
    {
        $this->load->model("Puestos");
        $data['data'] = $this->Puestos->get();
        $titulo['titulo'] = 'Catálogo de puestos';
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/catalogosPuestos', $data);
        $this->load->view('comunes/foot');
    }

    public function nuevoCliente()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']) && isset($this->input->post()['direccion']) && isset($this->input->post()['telefono']))
        {
            $this->load->model("Cliente");
            $data = array(
                'nombre' => trim($this->input->post()['nombre']),
                'direccion' => trim($this->input->post()['direccion']),
                'telefono' => trim($this->input->post()['telefono'])
            );
            $this->Cliente->insert($data);
            redirect("/administracion/catalogosClientes");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarCliente()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['direccionE']) && isset($this->input->post()['telefonoE']) && isset($this->input->post()['id']))
        {
            $this->load->model("Cliente");
            $this->Cliente->update(trim($this->input->post()['nombreE']), trim($this->input->post()['direccionE']), trim($this->input->post()['telefonoE']), $this->input->post()['id']);
            redirect("/administracion/catalogosClientes");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoLavado()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']))
        {
            $this->load->model("Lavado");
            $data['nombre'] = trim($this->input->post()['nombre']);
            $this->Lavado->insert($data);
            redirect("/administracion/catalogosLavados");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarLavado()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']))
        {
            $this->load->model("Lavado");
            $this->Lavado->update(trim($this->input->post()['nombreE']), $this->input->post()['id']);
            redirect("/administracion/catalogosLavados");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoMaquilero()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']) && isset($this->input->post()['direccion']) && isset($this->input->post()['telefono']))
        {
            $this->load->model("Maquilero");
            $data = array(
                'nombre' => trim($this->input->post()['nombre']),
                'direccion' => trim($this->input->post()['direccion']),
                'telefono' => trim($this->input->post()['telefono'])
            );
            $this->Maquilero->insert($data);
            redirect("/administracion/catalogosMaquileros");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarMaquilero()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['direccionE']) && isset($this->input->post()['telefonoE']) && isset($this->input->post()['id']))
        {
            $this->load->model("Maquilero");
            $this->Maquilero->update(trim($this->input->post()['nombreE']), trim($this->input->post()['direccionE']), trim($this->input->post()['telefonoE']), $this->input->post()['id']);
            redirect("/administracion/catalogosMaquileros");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoMarca()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']) && isset($this->input->post()['cliente']))
        {
            $this->load->model("Marca");
            $data = array(
                'nombre' => trim($this->input->post()['nombre']),
                'cliente_id' => trim($this->input->post()['cliente'])
            );
            $this->Marca->insert($data);
            redirect("/administracion/catalogosMarcas");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarMarca()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['clienteE']) && isset($this->input->post()['id']))
        {
            $this->load->model("Marca");
            $this->Marca->update(trim($this->input->post()['nombreE']), trim($this->input->post()['clienteE']), $this->input->post()['id']);
            redirect("/administracion/catalogosMarcas");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoProceso()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']) && isset($this->input->post()['costo']) && isset($this->input->post()['abreviatura']))
        {
            $this->load->model("ProcesoSeco");
            $data = array(
                'nombre' => trim($this->input->post()['nombre']),
                'costo' => trim($this->input->post()['costo']),
                'abreviatura' => trim($this->input->post()['abreviatura'])
            );
            $this->ProcesoSeco->insert($data);
            redirect("/administracion/catalogosProcesos");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarProceso()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['costoE']) && isset($this->input->post()['abreviaturaE']) && isset($this->input->post()['id']))
        {
            $this->load->model("ProcesoSeco");
            $this->ProcesoSeco->update(trim($this->input->post()['nombreE']), trim($this->input->post()['costoE']), trim($this->input->post()['abreviaturaE']), $this->input->post()['id']);
            redirect("/administracion/catalogosProcesos");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoTipo()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']))
        {
            $this->load->model("Tipo_pantalon");
            $data['nombre'] = trim($this->input->post()['nombre']);
            $this->Tipo_pantalon->insert($data);
            redirect("/administracion/catalogosTipos");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarTipo()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['id']))
        {
            $this->load->model("Tipo_pantalon");
            $this->Tipo_pantalon->update(trim($this->input->post()['nombreE']), $this->input->post()['id']);
            redirect("/administracion/catalogosTipos");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoUsuario()
    {
        if ($this->input->post() && isset($this->input->post()['pass']) && isset($this->input->post()['tipo_usuario_id']) && isset($this->input->post()['nombre']) && isset($this->input->post()['nombre_completo']) && isset($this->input->post()['direccion']) && isset($this->input->post()['telefono']) && isset($this->input->post()['activo']) && isset($this->input->post()['puesto_id']))
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
                'puesto_id' => trim($this->input->post()['puesto_id'])
            );
            $this->Usuarios->insert($data);
            redirect("/administracion/catalogosUsuarios");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarUsuario()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['passE']) && isset($this->input->post()['tipo_usuario_idE']) && isset($this->input->post()['nombre_completoE']) && isset($this->input->post()['direccionE']) && isset($this->input->post()['telefonoE']) && isset($this->input->post()['activoE']) && isset($this->input->post()['puesto_idE']) && isset($this->input->post()['id']))
        {
            $this->load->model("Usuarios");
            $this->Usuarios->update(trim($this->input->post()['nombreE']), trim($this->input->post()['passE']), trim($this->input->post()['tipo_usuario_idE']), trim($this->input->post()['nombre_completoE']), trim($this->input->post()['direccionE']), trim($this->input->post()['telefonoE']), trim($this->input->post()['activoE']), trim($this->input->post()['puesto_idE']), $this->input->post()['id']);
            redirect("/administracion/catalogosUsuarios");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function nuevoPuesto()
    {
        if ($this->input->post() && isset($this->input->post()['nombre']))
        {
            $this->load->model("Puestos");
            $data = array(
                'nombre' => $this->input->post()['nombre']
            );
            $this->Puestos->insert($data);
            redirect("/administracion/catalogosPuestos");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function editarPuesto()
    {
        if ($this->input->post() && isset($this->input->post()['nombreE']) && isset($this->input->post()['id']))
        {
            $this->load->model("Puestos");
            $this->Puestos->update(trim($this->input->post()['nombreE']), $this->input->post()['id']);
            redirect("/administracion/catalogosPuestos");
        }
        else
            redirect('/administracion/index?q=error');
    }

    public function cambiarPass()
    {
        if ($this->input->post() && isset($this->input->post()['pass1']))
        {
            $this->load->model('Usuarios');
            $this->Usuarios->updateP($_SESSION['usuario_id'], md5($this->input->post()['pass1']));
            redirect('/administracion/index/-1');
        }
        else
        {
            $data['link'] = base_url() . 'index.php/administracion/cambiarPass';
            $titulo['titulo'] = 'Cambiar contraseña';
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('comunes/cambiarPass', $data);
            $this->load->view('comunes/foot');
        }
    }

    public function datos()
    {
        if ($this->input->post() && isset($this->input->post()['nombre_completo']) && isset($this->input->post()['direccion']) && isset($this->input->post()['telefono']))
        {
            $this->load->model('Usuarios');
            $this->Usuarios->updateD($_SESSION['usuario_id'], $this->input->post()['nombre_completo'], $this->input->post()['direccion'], $this->input->post()['telefono']);
            redirect('/administracion/index/-1');
        }
        else
        {
            $this->load->model('Usuarios');
            $data = array(
                'link' => base_url() . 'index.php/administracion/datos',
                'data' => $this->Usuarios->getById($_SESSION['usuario_id'])
            );
            $titulo['titulo'] = 'Cambiar datos personales';
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('comunes/cambiarDatos', $data);
            $this->load->view('comunes/foot');
        }
    }

    public function descuentos()
    {
        if ($this->input->get() && isset($this->input->get()['id']))
        {
            $id = $this->input->get()['id'];
            if ($id == '')
                redirect('/administracion/index?q=error');
            $this->load->model("Descuentos");
            $this->load->model("Usuarios");
            $data['descuentos'] = $this->Descuentos->getByIdUsuario($id);
            $data['usuario'] = $this->Usuarios->getById($id);
            if (count($data['usuario']) == 0)
                redirect('/administracion/index?q=error');
            $titulo['titulo'] = "Descuentos del operario " . $data['usuario'][0]['nombre'];
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/descuentosEspecifico', $data);
            $this->load->view('comunes/foot');
        }
        else
        {
            $this->load->model("Usuarios");
            $data['data'] = $this->Usuarios->getOperarios();
            $titulo['titulo'] = "Descuentos";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/descuentos', $data);
            $this->load->view('comunes/foot');
        }
    }

    public function editarDescuento()
    {
        if (! $this->input->post() || ! isset($this->input->post()['razonE']) || ! isset($this->input->post()['fechaE']) || ! isset($this->input->post()['cantidadE']) || ! isset($this->input->post()['corte_folioE']) || ! isset($this->input->post()['idE']))
            redirect('/administracion/index?q=error');
        $this->load->model("Descuentos");
        $this->Descuentos->update(trim($this->input->post()['razonE']), trim($this->input->post()['fechaE']), trim($this->input->post()['cantidadE']), trim($this->input->post()['corte_folioE']), trim($this->input->post()['idE']));
        // print_r($this->input->post());
        redirect('/administracion/descuentos?id=' . $this->input->post()['idUsuario']);
    }

    public function nuevoDescuento()
    {
        if (! $this->input->post() || ! isset($this->input->post()['fecha']) || ! isset($this->input->post()['razon']) || ! isset($this->input->post()['id']) || ! isset($this->input->post()['cantidad']) || ! isset($this->input->post()['corte_folio']))
            redirect('/administracion/index?q=error');
        $this->load->model("Descuentos");
        $data = array(
            'fecha' => $this->input->post()['fecha'],
            'razon' => trim($this->input->post()['razon']),
            'usuario_id' => $this->input->post()['id'],
            'cantidad' => $this->input->post()['cantidad'],
            'corte_folio' => $this->input->post()['corte_folio']
        );
        $this->Descuentos->insert($data);
        redirect('/administracion/descuentos?id=' . $data['usuario_id']);
    }

    public function eliminarDescuento()
    {
        if (! $this->input->post() || ! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $id = $this->input->post()['id'];
        $this->load->model("Descuentos");
        $this->Descuentos->delete($id);
    }

    public function ojal()
    {
        $this->load->model("ojal");
        if (! $this->input->post())
        {
            $data['costo'] = $this->ojal->get()[0]['costo'];
            $titulo['titulo'] = "Costo de ojal";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/ojal', $data);
            $this->load->view('comunes/foot');
        }
        else
        {
            if (! isset($this->input->post()['costo']))
                redirect('/administracion/index?q=error');
            $this->ojal->update($this->input->post()['costo']);
            redirect("administracion\index\-1");
        }
    }

    public function ver()
    {
        $titulo['titulo'] = "Ver detalles de corte";
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('comunes/ver');
        $this->load->view('comunes/foot');
    }

    // Funciones para modificar corte
    public function modificar()
    {
        if ($this->input->get())
        {
            // Cargar modelos
            $this->load->model(array(
                'Cliente',
                'Lavado',
                'Maquilero',
                'Marca',
                'ProcesoSeco',
                'Tipo_pantalon',
                'Corte',
                'corteAutorizado',
                'Usuarios',
                'CorteAutorizadoDatos',
                'SalidaInterna1',
                'SalidaInterna1Datos',
                'ProduccionProcesoSeco',
                'Reproceso',
                'ProduccionReproceso'
            ));
            if (! isset($this->input->get()['folio']))
                redirect('/administracion/index?q=error');
            $folio = $this->input->get()['folio'];
            if ($folio == '')
                redirect('/administracion/index?q=error');
            // DETALLES DEL CORTE
            $corte = $this->Corte->getByFolio($folio);
            if (count($corte) == 0)
                redirect('/administracion/index?q=error');
            $corte = $corte[0];
            $extensiones = array(
                "jpg",
                "jpeg",
                "png"
            );
            $ban = false;
            foreach ($extensiones as $key2 => $extension)
            {
                $url = base_url() . "img/fotos/" . $folio . "." . $extension;
                $headers = get_headers($url);
                if (stripos($headers[0], "200 OK"))
                {
                    $ban = true;
                    $imagen = "<img src='" . base_url() . "img/fotos/" . $folio . "." . $extension . "' class='img-fluid' alt='Responsive image'>";
                    break;
                }
            }
            if (! $ban)
                $imagen = "No hay imágen";
            $corte['imagen'] = $imagen;
            $data['generales'] = $corte;
            $data['clientes'] = $this->Cliente->get();
            $data['lavados'] = $this->Lavado->get();
            $data['maquileros'] = $this->Maquilero->get();
            $data['marcas'] = $this->Marca->get();
            $data['procesosecos'] = $this->ProcesoSeco->get();
            $data['jsonProcesos'] = json_encode($data['procesosecos']);
            $data['tipo'] = $this->Tipo_pantalon->get();
            $data['usuarios'] = $this->Usuarios->get();
            // Cargar datos de autorización de corte
            $autorizado = $this->corteAutorizado->getByFolio($folio);
            if (count($autorizado) == 0)
                $data['autorizado'] = 0;
            else
                $data['autorizado'] = $autorizado[0];
            // Cargar autorización datos de corte
            $autorizadoDatos = $this->CorteAutorizadoDatos->getByFolio($folio);
            if (count($autorizadoDatos) == 0)
                $data['autorizadoDatos'] = 0;
            else
                $data['autorizadoDatos'] = $autorizadoDatos;
            // Cargar Salida Interna
            $salidaInterna = $this->SalidaInterna1->getByFolio($folio);
            if (count($salidaInterna) == 0)
                $data['salidaInterna'] = 0;
            else
                $data['salidaInterna'] = $salidaInterna[0];
            // Cargar Salida Interna Datos
            $salidaInternaDatos = $this->SalidaInterna1Datos->getByFolioEspecifico($folio);
            if (count($salidaInternaDatos) == 0)
                $data['salidaInternaDatos'] = 0;
            else
                $data['salidaInternaDatos'] = $salidaInternaDatos;
            // Cargar datos de producción de proceso seco
            $produccionProcesoSeco = $this->ProduccionProcesoSeco->seleccionReporte($folio);
            if (count($produccionProcesoSeco) == 0)
                $data['produccionProcesoSeco'] = 0;
            else
                $data['produccionProcesoSeco'] = $produccionProcesoSeco;
            // Cargar los lavados del corte con sus cargas
            $lavadosCorte = $this->CorteAutorizadoDatos->getLavadosByFolio($folio);
            if (count($lavadosCorte) == 0)
                $data['lavadosCorte'] = 0;
            else
                $data['lavadosCorte'] = $lavadosCorte;
            // CARGAR REPROCESOS
            $reprocesos = $this->Reproceso->getByFolioEspecifico($folio);
            $data['reprocesos'] = (count($reprocesos) == 0) ? 0 : $reprocesos;
            // CARGAR PRODUCCIÓN DE REPROCESOS
            $produccionReprocesos = $this->ProduccionReproceso->getByFolioEspecifico($folio);
            $data['produccionReprocesos'] = (count($produccionReprocesos) == 0) ? 0 : $produccionReprocesos;
            // CARGAR VISTAS
            $titulo['titulo'] = "Modificar Corte con folio " . $this->input->get()['folio'];
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/modificarCorte', $data);
            $this->load->view('comunes/foot');
        }
        else
        {
            // Cargar vistas
            $titulo['titulo'] = "Modificar Corte";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/modificar');
            $this->load->view('comunes/foot');
        }
    }

    public function modificarGenerales()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $this->load->Model('Corte');
        $this->Corte->update($this->input->post());
        return json_encode(array(
            'respuesta' => true
        ));
    }

    public function modificarImagen()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $folio = $this->input->post()['folioCambiarImagen'];
        // Eliminar la imágen actualizaCosto
        $extensiones = array(
            "jpg",
            "jpeg",
            "png"
        );
        foreach ($extensiones as $key2 => $extension)
        {
            // $ruta = __DIR__."../../img/fotos/".$folio.".".$extension;
            $ruta = "img/fotos/" . $folio . "." . $extension;
            if (is_file($ruta))
            {
                unlink($ruta);
                break;
            }
        }
        // Subir la imagen nueva
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
        if (! $this->upload->do_upload($mi_imagen))
            $data['uploadError'] = $this->upload->display_errors();
        $data['uploadSuccess'] = $this->upload->data();
        // Retornar
        redirect('/administracion/modificar?folio=' . $this->input->post()['folio']);
    }

    public function editarAutorizacion()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $this->load->model('CorteAutorizado');
        $this->CorteAutorizado->update($this->input->post());
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarAutorizacion()
    {
        if (! $this->input->post() || ! isset($this->input->post()['folio']))
            redirect('/administracion/index?q=error');
        $folio = $this->input->post()['folio'];
        $this->load->model(array(
            'ProduccionProcesoSeco',
            'SalidaInterna1Datos',
            'SalidaInterna1',
            'CorteAutorizadoDatos',
            'CorteAutorizado'
        ));
        // Eliminar datos de producción de proceso Seco
        $this->ProduccionProcesoSeco->deleteByFolio($folio);
        // Eliminar datos de salida interna
        $this->SalidaInterna1Datos->deleteByFolio($folio);
        $this->SalidaInterna1->deleteByFolio($folio);
        // Eliminar datos de corte autorizado
        $this->CorteAutorizadoDatos->deleteByFolio($folio);
        $this->CorteAutorizado->deleteByFolio($folio);
        // Enviar respuesta
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function editarAutorizacionDatos()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $this->load->model("CorteAutorizadoDatos");
        $this->CorteAutorizadoDatos->update($this->input->post());
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarAutorizacionDatos()
    {
        if (! $this->input->post() || ! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $this->load->model("CorteAutorizadoDatos");
        $this->CorteAutorizadoDatos->deleteByID($this->input->post()['id']);
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function editarSalidaInterna()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $this->load->model("SalidaInterna1");
        $this->SalidaInterna1->update($this->input->post());
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarSalidaInterna()
    {
        if (! $this->input->post() || ! isset($this->input->post()['folio']))
            redirect('/administracion/index?q=error');
        $folio = $this->input->post()['folio'];
        $this->load->model(array(
            'ProduccionProcesoSeco',
            'SalidaInterna1Datos',
            'SalidaInterna1'
        ));
        // Eliminar datos de producción de proceso Seco
        $this->ProduccionProcesoSeco->deleteByFolio($folio);
        // Eliminar datos de salida interna
        $this->SalidaInterna1Datos->deleteByFolio($folio);
        $this->SalidaInterna1->deleteByFolio($folio);
        // Enviar respuesta
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function editarSalidaInternaDatos()
    {
        if (! $this->input->post() || ! isset($this->input->post()['piezas']) || ! isset($this->input->post()['id_carga']) || ! isset($this->input->post()['folio']))
            redirect('/administracion/index?q=error');
        $this->load->model(array(
            "SalidaInterna1Datos"
        ));
        // Actualizar salida_interna1_datos(piezas) con : id_carga, corte_folio
        $data = array(
            'piezas' => $this->input->post()['piezas'],
            'id_carga' => $this->input->post()['id_carga'],
            'corte_folio' => $this->input->post()['folio']
        );
        $this->SalidaInterna1Datos->updateAdministracion($data);
        // Regresar
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function editarLavadoCorte()
    {
        if (! $this->input->post() || ! isset($this->input->post()['lavado_id']) || ! isset($this->input->post()['folio']) || ! isset($this->input->post()['id_carga']))
            redirect('/administracion/index?q=error');
        $this->load->model(array(
            "CorteAutorizadoDatos",
            "SalidaInterna1Datos",
            "ProduccionProcesoSeco"
        ));
        // Actualizar corte_autorizado_datos(lavado_id) con: corte_folio, id_carga
        $data = array(
            'lavado_id' => $this->input->post()['lavado_id'],
            'corte_folio' => $this->input->post()['folio'],
            'id_carga' => $this->input->post()['id_carga']
        );
        $this->CorteAutorizadoDatos->updateAdministracion($data);
        // Actualizar produccion_proceso_seco(lavado_id) con: carga, corte_folio
        $data = array(
            'lavado_id' => $this->input->post()['lavado_id'],
            'carga' => $this->input->post()['id_carga'],
            'corte_folio' => $this->input->post()['folio']
        );
        $this->ProduccionProcesoSeco->updateAdministracion($data);
        // Regresar
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarLavadoCorte()
    {
        if (! $this->input->post() || ! isset($this->input->post()['folio']) || ! isset($this->input->post()['id_carga']))
            redirect('/administracion/index?q=error');
        $this->load->model(array(
            "CorteAutorizadoDatos",
            "SalidaInterna1Datos",
            "ProduccionProcesoSeco",
            "CorteAutorizado"
        ));
        // Eliminar corte_autorizado_datos con: corte_folio, id_carga
        $data = array(
            'corte_folio' => $this->input->post()['folio'],
            'id_carga' => $this->input->post()['id_carga']
        );
        $this->CorteAutorizadoDatos->deleteAdministracion($data);
        // Eliminar salida_interna1_datos con : id_carga, corte_folio
        $data = array(
            'id_carga' => $this->input->post()['id_carga'],
            'corte_folio' => $this->input->post()['folio']
        );
        $this->SalidaInterna1Datos->deleteAdministracion($data);
        // Eliminar produccion_proceso_seco con: carga, corte_folio
        $data = array(
            'carga' => $this->input->post()['id_carga'],
            'corte_folio' => $this->input->post()['folio']
        );
        $this->ProduccionProcesoSeco->deleteAdministracion($data);
        // Disminuir en 1 las cargas
        $this->CorteAutorizado->disminuyeCargasEn1($this->input->post()['folio']);
        // Regresar
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function editarProduccion()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $this->load->model("ProduccionProcesoSeco");
        $this->ProduccionProcesoSeco->updateById($this->input->post());
        // Regresar
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarProduccion()
    {
        if (! $this->input->post() || ! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $this->load->model("ProduccionProcesoSeco");
        $this->ProduccionProcesoSeco->deleteById($this->input->post()['id']);
        // Regresar
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function agregarLavado()
    {
        if (! $this->input->post() || ! isset($this->input->post()['procesoNuevo']) || ! isset($this->input->post()['corteFolioNuevoLavado']) || ! isset($this->input->post()['lavadoProcesoNuevo']))
            redirect('/administracion/index?q=error');
        $this->load->model(array(
            'CorteAutorizado',
            'CorteAutorizadoDatos',
            'ProcesoSeco'
        ));
        // Aumentar la cargas
        $this->CorteAutorizado->aumentaCargasEn1($this->input->post()['corteFolioNuevoLavado']);
        // Agregar en corte_autorizado_datos
        // Encontrar el id de la carga mas alta
        $carga = $this->CorteAutorizadoDatos->getCargaMaxima($this->input->post()['corteFolioNuevoLavado']);
        $idCarga = (count($carga) != 0) ? $carga[0]['maxima'] + 1 : 1;
        // recuperar procesos
        $procesos = $this->input->post()['procesoNuevo'];
        // si hay salida interna
        if (isset($this->input->post()['piezasLavadoNuevo']))
        {
            $this->load->model('SalidaInterna1Datos');
            // insertar en corte_autorizado_datos
            foreach ($procesos as $key => $value)
            {
                // Costo del proceso seco
                $costo = $this->ProcesoSeco->getById($value)[0]['costo'];
                // Llenar arreglo
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
            // Insrtar en salida_interna1_datos
            $this->load->model('SalidaInterna1Datos');
            $data = array(
                'id_carga' => $idCarga,
                'piezas' => $this->input->post()['piezasLavadoNuevo'],
                'corte_folio' => $this->input->post()['corteFolioNuevoLavado']
            );
            $this->SalidaInterna1Datos->agregar($data);
        } // si no hay salida interna
        else
        {
            foreach ($procesos as $key => $value)
            {
                $costo = $this->ProcesoSeco->getById($value)[0]['costo'];
                // Llenar arreglo
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
                $this->CorteAutorizadoDatos->agregar($data);
            }
        }
        redirect('/administracion/modificar?folio=' . $this->input->post()['corteFolioNuevoLavado']);
    }

    public function editarReproceso()
    {
        if (! $this->input->post())
            redirect('/administracion/index?q=error');
        $this->load->model('Reproceso');
        $this->Reproceso->update($this->input->post());
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarReproceso()
    {
        if (! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $this->load->model(array(
            'ProduccionReproceso',
            'Reproceso'
        ));
        $this->ProduccionReproceso->deleteByIdReproceso($this->input->post()['id']);
        $this->Reproceso->deleteById($this->input->post()['id']);
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function editarProduccionReproceso()
    {
        if (! isset($this->input->post()['id']) || ! isset($this->input->post()['piezas']) || ! isset($this->input->post()['defectos']) || ! isset($this->input->post()['estado_nomina']))
            redirect('/administracion/index?q=error');
        $this->load->model('ProduccionReproceso');
        $this->ProduccionReproceso->update($this->input->post());
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function eliminarProduccionReproceso()
    {
        if (! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $this->load->model('ProduccionReproceso');
        $this->ProduccionReproceso->deleteById($this->input->post()['id']);
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function ahorros()
    {
        if ($this->input->get())
        {
            if (! isset($this->input->get()['id']))
                redirect('/administracion/index?q=error');
            $id = $this->input->get()['id'];
            if ($id == '')
                redirect("/");
            $this->load->model("Ahorros");
            $this->load->model("Usuarios");
            $data['ahorros'] = $this->Ahorros->getByIdUsuario($id);
            $data['usuario'] = $this->Usuarios->getById($id);
            if (count($data['usuario']) == 0)
                redirect('/administracion/index?q=error');
            $titulo['titulo'] = "Ahorros del operario " . $data['usuario'][0]['nombre'];
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/ahorrosEspecifico', $data);
            $this->load->view('comunes/foot');
        }
        else
        {
            $this->load->model("Usuarios");
            $data['data'] = $this->Usuarios->getOperarios();
            $titulo['titulo'] = "Ahorros";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/ahorros', $data);
            $this->load->view('comunes/foot');
        }
    }

    public function nuevoAhorro()
    {
        if (! $this->input->post() || ! isset($this->input->post()['fecha']) || ! isset($this->input->post()['cantidad']) || ! isset($this->input->post()['id']) || ! isset($this->input->post()['aportacion']))
            redirect('/administracion/index?q=error');
        $this->load->model('Ahorros');
        $data = array(
            'fecha' => $this->input->post()['fecha'],
            'cantidad' => $this->input->post()['cantidad'],
            'usuario_id' => $this->input->post()['id'],
            'aportacion' => $this->input->post()['aportacion']
        );
        $this->Ahorros->insert($data);
        redirect("administracion/ahorros?id=" . $this->input->post()['id']);
    }

    public function editarAhorro()
    {
        if (! $this->input->post() || ! isset($this->input->post()['aportacionE']) || ! isset($this->input->post()['fechaE']) || ! isset($this->input->post()['cantidadE']) || ! isset($this->input->post()['idE']))
            redirect('/administracion/index?q=error');
        $this->load->model('Ahorros');
        $this->Ahorros->update($this->input->post()['aportacionE'], $this->input->post()['fechaE'], $this->input->post()['cantidadE'], $this->input->post()['idE']);
        redirect("administracion/ahorros?id=" . $this->input->post()['idUsuario']);
    }

    public function eliminarAhorro()
    {
        if (! $this->input->post() || ! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $this->load->model("Ahorros");
        $this->Ahorros->delete($this->input->post()['id']);
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function nomina()
    {
        $this->load->model("Nomina");
        $data['data'] = $this->Nomina->getDistinct();
        $titulo['titulo'] = "Nómina";
        $this->load->view('comunes/head', $titulo);
        $this->load->view('administracion/menu');
        $this->load->view('administracion/nomina', $data);
        $this->load->view('comunes/foot');
    }

    public function eliminarNomina()
    {
        if (! isset($this->input->post()['id']))
            redirect('/administracion/index?q=error');
        $this->load->model(array(
            'Nomina',
            'ProduccionReproceso',
            'ProduccionProcesoSeco'
        ));
        $this->Nomina->delete($this->input->post()['id']);
        // Regresar los procesos a la normalidad
        $this->ProduccionProcesoSeco->regresaNomina($this->input->post()['id']);
        $this->ProduccionReproceso->regresaNomina($this->input->post()['id']);
        echo json_encode(array(
            'respuesta' => true
        ));
    }

    public function nuevaNomina()
    {
        if (! $this->input->post())
        {
            $titulo['titulo'] = "Nueva nómina";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/opcionesNomina');
            $this->load->view('comunes/foot');
        }
        else
        {
            $this->load->model(array(
                "Nomina",
                "Descuentos",
                "Usuarios",
                "Ahorros",
                "ProduccionProcesoSeco",
                "ProduccionReproceso"
            ));
            $data['nomina'] = $this->Nomina->getUltimaNomina();
            $data['descuentos'] = $this->Descuentos->get();
            $data['operarios'] = $this->Usuarios->getOperariosEspecificos();
            $data['ahorros'] = $this->Ahorros->get();
            $data['pendientes_produccion'] = $this->ProduccionProcesoSeco->getPendientes();
            $data['pendientes_reproceso'] = $this->ProduccionReproceso->getPendientes();
            // Por fechas
            if ($this->input->post()['optionsRadios'] == 'option1')
            {
                if (! isset($this->input->post()['fechaInicial']) || ! isset($this->input->post()['fechaFinal']))
                    redirect('/administracion/index?q=error');
                $data['reprocesos'] = $this->ProduccionReproceso->getByFechas($this->input->post()['fechaInicial'], $this->input->post()['fechaFinal']);
                $data['descripcion'] = "Nómina destajo del " . $this->input->post()['fechaInicial'] . " al " . $this->input->post()['fechaFinal'];
                $data['produccion'] = $this->ProduccionProcesoSeco->getByFechas($this->input->post()['fechaInicial'], $this->input->post()['fechaFinal']);
            } // Por folios
            else
            {
                if (! isset($this->input->post()['folios']))
                    redirect('/administracion/index?q=error');
                $data['descripcion'] = "Nómina destajo de los folios " . $this->input->post()['folios'];
                $folios = explode(",", $this->input->post()['folios']);
                $data['produccion'] = $this->ProduccionProcesoSeco->getByFolios($folios);
                $data['reprocesos'] = $this->ProduccionReproceso->getByFolios($folios);
            }
            // Cargar vistas
            $titulo['titulo'] = "Nueva nómina";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/generarNomina', $data);
            $this->load->view('comunes/foot');
        }
    }

    public function verNomina()
    {
        // nueva nomina
        if ($this->input->post())
        {
            // print_r($this->input->post());
            // Update a ProduccionReproceso y a ProduccionProcesoSeco
            $this->load->model(array(
                'ProduccionReproceso',
                'ProduccionProcesoSeco'
            ));
            
            // Produccion proceso seco
            if (isset($this->input->post()['cantidad_pagar_produccion_proceso_seco_']))
            {
                foreach ($this->input->post()['cantidad_pagar_produccion_proceso_seco_'] as $key => $value)
                {
                    $data = array(
                        'id' => $key,
                        'estado_nomina' => $this->input->post()['estado_nomina_proceso_seco'][$key],
                        'cantidad_pagar' => $value,
                        'razon_pagar' => $this->input->post()['razonProduccionProcesoSeco'][$key],
                        'id_nomina' => $this->input->post()['idNomina']
                    );
                    $this->ProduccionProcesoSeco->update($data);
                }
            }
            
            // Pendientes proceso seco
            if (isset($this->input->post()['cantidad_pagar_pendientes_proceso_seco']))
            {
                foreach ($this->input->post()['cantidad_pagar_pendientes_proceso_seco'] as $key => $value)
                {
                    $data = array(
                        'id' => $key,
                        'estado_nomina' => $this->input->post()['estado_nomina_pendientes_proceso_seco'][$key],
                        'cantidad_pagar' => $value,
                        'razon_pagar' => $this->input->post()['razonPendientesProcesoSeco'][$key],
                        'id_nomina' => $this->input->post()['idNomina']
                    );
                    $this->ProduccionProcesoSeco->update($data);
                }
            }
            
            // Reproceso
            if (isset($this->input->post()['estado_nomina_reproceso']))
            {
                foreach ($this->input->post()['estado_nomina_reproceso'] as $key => $value)
                {
                    $data = array(
                        'id' => $key,
                        'estado_nomina' => $value,
                        'cantidad_pagar' => $this->input->post()['cantidad_pagar_reproceso'][$key],
                        'razon_pagar' => $this->input->post()['razonReproceso'][$key],
                        'id_nomina' => $this->input->post()['idNomina']
                    );
                    $this->ProduccionReproceso->update($data);
                }
            }
            
            // Pendientes Reproceso
            if (isset($this->input->post()['estado_nomina_pendientes_reproceso']))
            {
                foreach ($this->input->post()['estado_nomina_pendientes_reproceso'] as $key => $value)
                {
                    $data = array(
                        'id' => $key,
                        'estado_nomina' => $value,
                        'cantidad_pagar' => $this->input->post()['cantidad_pagar_pendientes_reproceso'][$key],
                        'razon_pagar' => $this->input->post()['razonPendientesReproceso'][$key],
                        'id_nomina' => $this->input->post()['idNomina']
                    );
                    $this->ProduccionReproceso->update($data);
                }
            }
            // Creacion del PDF
            /*
             * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
             * heredó todos las variables y métodos de fpdf
             */
            $this->load->library('pdf');
            $pdf = new Pdf(utf8_decode($this->input->post()['descripcion']), 'L');
            // Agregamos una página
            $pdf->SetAutoPageBreak(1, 20);
            // Define el alias para el número de página que se imprimirá en el pie
            $pdf->AliasNbPages();
            $pdf->AddPage();
            /*
             * Se define el titulo, márgenes izquierdo, derecho y
             * el color de relleno predeterminado
             */
            $pdf->SetTitle(utf8_decode($this->input->post()['descripcion']));
            // Tabla de producción
            $pdf->SetWidths(array(
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                62.307692308,
                62.307692308,
                20.769230769,
                20.769230769,
                20.769230769
            ));
            // Encabezado de tabla
            $pdf->SetFillColor(59, 131, 189);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->ban = true;
            $pdf->Row(array(
                utf8_decode("Nombre\n\n"),
                utf8_decode("Puesto\n\n"),
                utf8_decode("Saldo anterior\n"),
                utf8_decode("Nómina\n\n"),
                utf8_decode("\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tDescuentos\n\n"),
                utf8_decode("\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAhorro\n\n"),
                utf8_decode("Bonos\n\n"),
                utf8_decode("Total\n\n"),
                utf8_decode("Pagado\n\n")
            ));
            $antiguoX = $pdf->getX();
            $antiguoY = $pdf->getY();
            $pdf->SetY($pdf->GetY() - 5);
            $pdf->SetX(93.076923076);
            $pdf->SetWidths(array(
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769
            ));
            $pdf->Row(array(
                utf8_decode("Anterior"),
                utf8_decode("Abono"),
                utf8_decode("Saldo"),
                utf8_decode("Anterior"),
                utf8_decode("Abono"),
                utf8_decode("Saldo")
            ));
            // Llenar tabla
            $nombre = $this->input->post()['nombre'];
            $puesto = $this->input->post()['puesto'];
            $saldo_anterior = $this->input->post()['saldo_anterior'];
            $nomina = $this->input->post()['nomina'];
            $ahorro_anterior = $this->input->post()['ahorro_anterior'];
            $ahorro_abono = $this->input->post()['ahorro_abono'];
            $ahorro_saldo = $this->input->post()['ahorro_saldo'];
            $bonos = $this->input->post()['bonos'];
            $descuentos_anterior = $this->input->post()['descuentos_anterior'];
            $descuentos_abono = $this->input->post()['descuentos_abono'];
            $descuentos_saldo = $this->input->post()['descuentos_saldo'];
            $total = $this->input->post()['total'];
            $pagado = $this->input->post()['pagado'];
            $pdf->ban = false;
            $pdf->SetFont('Arial', '', 8);
            $produccion = 0;
            $this->load->model(array(
                'Nomina',
                'Ahorros',
                'Descuentos'
            ));
            // regresar coordenadas a la normalidad
            $pdf->SetXY($antiguoX, $antiguoY);
            $pdf->SetWidths(array(
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769,
                20.769230769
            ));
            foreach ($nombre as $key => $value)
            {
                // Agregar a tablas de bd
                $data = array(
                    'id' => $this->input->post()['idNomina'],
                    'fecha' => date('Y-m-d'),
                    'descripcion' => $this->input->post()['descripcion'],
                    'usuario_id' => $key,
                    'saldo_anterior' => $saldo_anterior[$key],
                    'nomina' => $nomina[$key],
                    'descuentos_anterior' => $descuentos_anterior[$key],
                    'descuentos_abono' => $descuentos_abono[$key],
                    'descuentos_saldo' => $descuentos_saldo[$key],
                    'ahorro_anterior' => $ahorro_anterior[$key],
                    'ahorro_abono' => $ahorro_abono[$key],
                    'ahorro_saldo' => $ahorro_saldo[$key],
                    'bonos' => $bonos[$key],
                    'total' => $total[$key],
                    'pagado' => $pagado[$key]
                );
                $this->Nomina->insert($data);
                if ($ahorro_abono[$key] != 0)
                {
                    $data = array(
                        'fecha' => date('Y-m-d'),
                        'cantidad' => $ahorro_abono[$key],
                        'usuario_id' => $key,
                        'aportacion' => 1
                    );
                    $this->Ahorros->insert($data);
                }
                if ($descuentos_abono[$key] != 0)
                {
                    $data = array(
                        'fecha' => date('Y-m-d'),
                        'razon' => 'Abonado de ' . $this->input->post()['descripcion'],
                        'usuario_id' => $key,
                        'cantidad ' => ($descuentos_abono[$key] * - 1)
                    );
                    $this->Descuentos->insert($data);
                }
                // Agregar al pdf
                $pdf->Row(array(
                    utf8_decode($nombre[$key]),
                    utf8_decode($puesto[$key]),
                    utf8_decode("$" . $saldo_anterior[$key]),
                    utf8_decode("$" . $nomina[$key]),
                    utf8_decode("$" . $descuentos_anterior[$key]),
                    utf8_decode("$" . $descuentos_abono[$key]),
                    utf8_decode("$" . $descuentos_saldo[$key]),
                    utf8_decode("$" . $ahorro_anterior[$key]),
                    utf8_decode("$" . $ahorro_abono[$key]),
                    utf8_decode("$" . $ahorro_saldo[$key]),
                    utf8_decode("$" . $bonos[$key]),
                    utf8_decode("$" . $total[$key]),
                    utf8_decode("$" . $pagado[$key])
                ));
            }
            /*
             * Se manda el pdf al navegador
             *
             * $this->pdf->Output(nombredelarchivo, destino);
             *
             * I = Muestra el pdf en el navegador
             * D = Envia el pdf para descarga
             *
             */
            $pdf->Output($this->input->post()['descripcion'] . ".pdf", 'I');
        }
        else
        {
            // Antigua nómina
            if ($this->input->get())
            {
                // print_r($this->input->post());
                // Creacion del PDF
                /*
                 * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
                 * heredó todos las variables y métodos de fpdf
                 */
                if (! $this->input->get())
                    redirect('/administracion/index?q=error');
                $this->load->model('Nomina');
                $nomina = $this->Nomina->getNominaById($this->input->get()['id']);
                // print_r($nomina);
                $this->load->library('pdf');
                $pdf = new Pdf(utf8_decode($nomina[0]['descripcion']), 'L');
                // Agregamos una página
                $pdf->SetAutoPageBreak(1, 20);
                // Define el alias para el número de página que se imprimirá en el pie
                $pdf->AliasNbPages();
                $pdf->AddPage();
                /*
                 * Se define el titulo, márgenes izquierdo, derecho y
                 * el color de relleno predeterminado
                 */
                $pdf->SetTitle(utf8_decode(utf8_decode($nomina[0]['descripcion'])));
                // Tabla de producción
                $pdf->SetWidths(array(
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    62.307692308,
                    62.307692308,
                    20.769230769,
                    20.769230769,
                    20.769230769
                ));
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->Row(array(
                    utf8_decode("Nombre\n\n"),
                    utf8_decode("Puesto\n\n"),
                    utf8_decode("Saldo anterior\n"),
                    utf8_decode("Nómina\n\n"),
                    utf8_decode("\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tDescuentos\n\n"),
                    utf8_decode("\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAhorro\n\n"),
                    utf8_decode("Bonos\n\n"),
                    utf8_decode("Total\n\n"),
                    utf8_decode("Pagado\n\n")
                ));
                $antiguoX = $pdf->getX();
                $antiguoY = $pdf->getY();
                $pdf->SetY($pdf->GetY() - 5);
                $pdf->SetX(93.076923076);
                $pdf->SetWidths(array(
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769
                ));
                $pdf->Row(array(
                    utf8_decode("Anterior"),
                    utf8_decode("Abono"),
                    utf8_decode("Saldo"),
                    utf8_decode("Anterior"),
                    utf8_decode("Abono"),
                    utf8_decode("Saldo")
                ));
                // Llenar tabla
                // regresar coordenadas a la normalidad
                $pdf->ban = false;
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY($antiguoX, $antiguoY);
                $pdf->SetWidths(array(
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769,
                    20.769230769
                ));
                foreach ($nomina as $key => $value)
                {
                    // Agregar al pdf
                    $pdf->Row(array(
                        utf8_decode($value['nombre']),
                        utf8_decode($value['puesto']),
                        utf8_decode('$' . $value['saldo_anterior']),
                        utf8_decode('$' . $value['nomina']),
                        utf8_decode('$' . $value['descuentos_anterior']),
                        utf8_decode('$' . $value['descuentos_abono']),
                        utf8_decode('$' . $value['descuentos_saldo']),
                        utf8_decode('$' . $value['ahorro_anterior']),
                        utf8_decode('$' . $value['ahorro_abono']),
                        utf8_decode('$' . $value['ahorro_saldo']),
                        utf8_decode('$' . $value['bonos']),
                        utf8_decode('$' . $value['total']),
                        utf8_decode('$' . $value['pagado'])
                    ));
                }
                /*
                 * Se manda el pdf al navegador
                 *
                 * $this->pdf->Output(nombredelarchivo, destino);
                 *
                 * I = Muestra el pdf en el navegador
                 * D = Envia el pdf para descarga
                 *
                 */
                $pdf->Output(utf8_decode($nomina[0]['descripcion']) . ".pdf", 'I');
            }
            else
                redirect('/administracion/index?q=error');
        }
    }

    public function reproceso()
    {
        if (! $this->input->post())
        {
            $this->load->model("ProcesoSeco");
            $data['procesos'] = $this->ProcesoSeco->get();
            $titulo['titulo'] = "Nuevo reproceso";
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/reproceso', $data);
            $this->load->view('comunes/foot');
        }
        else
        {
            if (! isset($this->input->post()['corte_folio']) || ! isset($this->input->post()['piezas']) || ! isset($this->input->post()['lavado']) || ! isset($this->input->post()['proceso']) || ! isset($this->input->post()['costo']) || ! is_numeric($this->input->post()['corte_folio']) || ! is_numeric($this->input->post()['lavado']) || ! is_numeric($this->input->post()['proceso']) || ! is_numeric($this->input->post()['costo']) || ! is_numeric($this->input->post()['piezas']))
                redirect('/administracion/index?q=error');
            $data = array(
                'corte_folio' => $this->input->post()['corte_folio'],
                'lavado_id' => $this->input->post()['lavado'],
                'proceso_seco_id' => $this->input->post()['proceso'],
                'costo' => $this->input->post()['costo'],
                'piezas_trabajadas' => $this->input->post()['piezas'],
                'defectos' => 0,
                'status' => 1,
                'fecha_registro' => date('Y-m-d'),
                'usuario_id' => $_SESSION['usuario_id']
            );
            $this->load->model('Reproceso');
            $this->Reproceso->insert($data);
            redirect('/administracion/index?q=reproceso');
        }
    }

    public function verNominaDetalles()
    {
        if (! isset($this->input->get()['id']) || ! is_numeric($this->input->get()['id']))
            redirect('/administracion/index?q=error');
        // Creacion del PDF
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->load->model(array(
            'Nomina',
            'ProduccionProcesoSeco',
            'ProduccionReproceso'
        ));
        $nomina = $this->Nomina->getNominaById($this->input->get()['id']);
        $produccion = $this->ProduccionProcesoSeco->nominaEspecifico($this->input->get()['id']);
        $reproceso = $this->ProduccionReproceso->nominaEspecifico($this->input->get()['id']);
        // print_r($nomina);
        $this->load->library('pdf');
        $pdf = new Pdf(utf8_decode($nomina[0]['descripcion']), 'L');
        // Agregamos una página
        $pdf->SetAutoPageBreak(1, 20);
        // Define el alias para el número de página que se imprimirá en el pie
        $pdf->AliasNbPages();
        $pdf->AddPage();
        /*
         * Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $pdf->SetTitle(utf8_decode(utf8_decode($nomina[0]['descripcion'])));
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 0, "Datos generales", 0, 1, 'C');
        $pdf->ln(5);
        // Tabla de producción
        $pdf->SetWidths(array(
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            62.307692308,
            62.307692308,
            20.769230769,
            20.769230769,
            20.769230769
        ));
        // Encabezado de tabla
        $pdf->SetFillColor(59, 131, 189);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->ban = true;
        $pdf->Row(array(
            utf8_decode("Nombre\n\n"),
            utf8_decode("Puesto\n\n"),
            utf8_decode("Saldo anterior\n"),
            utf8_decode("Nómina\n\n"),
            utf8_decode("\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tDescuentos\n\n"),
            utf8_decode("\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAhorro\n\n"),
            utf8_decode("Bonos\n\n"),
            utf8_decode("Total\n\n"),
            utf8_decode("Pagado\n\n")
        ));
        $antiguoX = $pdf->getX();
        $antiguoY = $pdf->getY();
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX(93.076923076);
        $pdf->SetWidths(array(
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769
        ));
        $pdf->Row(array(
            utf8_decode("Anterior"),
            utf8_decode("Abono"),
            utf8_decode("Saldo"),
            utf8_decode("Anterior"),
            utf8_decode("Abono"),
            utf8_decode("Saldo")
        ));
        // Llenar tabla
        // regresar coordenadas a la normalidad
        $pdf->ban = false;
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY($antiguoX, $antiguoY);
        $pdf->SetWidths(array(
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769,
            20.769230769
        ));
        foreach ($nomina as $key => $value)
        {
            // Agregar al pdf
            $pdf->Row(array(
                utf8_decode($value['nombre']),
                utf8_decode($value['puesto']),
                utf8_decode('$' . $value['saldo_anterior']),
                utf8_decode('$' . $value['nomina']),
                utf8_decode('$' . $value['descuentos_anterior']),
                utf8_decode('$' . $value['descuentos_abono']),
                utf8_decode('$' . $value['descuentos_saldo']),
                utf8_decode('$' . $value['ahorro_anterior']),
                utf8_decode('$' . $value['ahorro_abono']),
                utf8_decode('$' . $value['ahorro_saldo']),
                utf8_decode('$' . $value['bonos']),
                utf8_decode('$' . $value['total']),
                utf8_decode('$' . $value['pagado'])
            ));
        }
        // Datos específicos
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->AddPage();
        $pdf->Cell(0, 0, utf8_decode("Datos específicos"), 0, 2, 'C');
        $pdf->ln(5);
        $pdf->Cell(0, 0, utf8_decode("Datos de producción de proceso seco"), 0, 2, '');
        $pdf->ln(5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetWidths(array(
            30,
            30,
            30,
            30,
            30,
            30,
            30,
            30,
            30
        ));
        // Encabezado de tabla
        $pdf->SetFillColor(59, 131, 189);
        $pdf->ban = true;
        $pdf->Row(array(
            utf8_decode("Nombre\n\n"),
            utf8_decode("Folio\n\n"),
            utf8_decode("Lavado o carga\n\n"),
            utf8_decode("Proceso\n\n"),
            utf8_decode("¿Se pagó?\n\n"),
            utf8_decode("Razón por la cual no se pagó"),
            utf8_decode("Piezas trabajadas\n\n"),
            utf8_decode("Precio unitario\n\n"),
            utf8_decode("Total\n\n")
        ));
        $pdf->ban = false;
        $pdf->SetFont('Arial', '', 8);
        foreach ($produccion as $key => $value)
        {
            $n = $value['usuario_nombre'];
            $folio = $value['folio'];
            $lavado = $value['lavado'];
            $proceso = $value['proceso'];
            $estado = $value['estado'];
            $razon = $value['razon'];
            $piezas = $value['piezas'];
            $precio = '$' . $value['precio'];
            $costo = '$' . $value['costo'];
            switch ($estado)
            {
                case 1:
                    $estado = "Se pagó";
                    $razon = "";
                    break;
                case 2:
                    $estado = "Queda pendiente";
                    break;
                default:
                    $estado = "No se pagará nunca";
                    break;
            }
            $pdf->Row(array(
                utf8_decode($n),
                utf8_decode($folio),
                utf8_decode($lavado),
                utf8_decode($proceso),
                utf8_decode($estado),
                utf8_decode($razon),
                utf8_decode($piezas),
                utf8_decode($precio),
                utf8_decode($costo)
            
            ));
        }
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 0, "Datos de produccion de reproceso", 0, 2, '');
        $pdf->ln(5);
        $pdf->SetFillColor(59, 131, 189);
        $pdf->ban = true;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Row(array(
            utf8_decode("Nombre\n\n"),
            utf8_decode("Folio\n\n"),
            utf8_decode("Lavado o carga\n\n"),
            utf8_decode("Proceso\n\n"),
            utf8_decode("¿Se pagó?\n\n"),
            utf8_decode("Razón por la cual no se pagó"),
            utf8_decode("Piezas trabajadas\n\n"),
            utf8_decode("Precio unitario\n\n"),
            utf8_decode("Total\n\n")
        ));
        $pdf->ban = false;
        $pdf->SetFont('Arial', '', 10);
        foreach ($reproceso as $key => $value)
        {
            $n = $value['usuario_nombre'];
            $folio = $value['folio'];
            $lavado = $value['lavado'];
            $proceso = $value['proceso'];
            $estado = $value['estado'];
            $razon = $value['razon'];
            $piezas = $value['piezas'];
            $precio = '$' . $value['precio'];
            $costo = '$' . $value['costo'];
            switch ($estado)
            {
                case 1:
                    $estado = "Se pagó";
                    $razon = "";
                    break;
                case 2:
                    $estado = "Queda pendiente";
                    break;
                default:
                    $estado = "No se pagará nunca";
                    break;
            }
            $pdf->SetFont('Arial', '', 8);
            $pdf->Row(array(
                utf8_decode($n),
                utf8_decode($folio),
                utf8_decode($lavado),
                utf8_decode($proceso),
                utf8_decode($estado),
                utf8_decode($razon),
                utf8_decode($piezas),
                utf8_decode($precio),
                utf8_decode($costo)
            ));
        }
        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $pdf->Output(utf8_decode($nomina[0]['descripcion']) . ".pdf", 'I');
    }

    public function reporteCostos()
    {
        if ($this->input->get())
        {
            if (! isset($this->input->get()['folio']) || ! is_numeric($this->input->get()['folio']))
                redirect('/administracion/index?q=error');
            $this->load->model(array(
                'ProduccionProcesoSeco',
                'ProduccionReproceso'
            ));
            $data['produccion'] = $this->ProduccionProcesoSeco->getByFolioEspecifico($this->input->get()['folio']);
            $data['reproceso'] = $this->ProduccionReproceso->getByFolioEspecifico2($this->input->get()['folio']);
            if (count($data['produccion']) == 0 && count($data['reproceso']) == 0)
                redirect('/administracion/index?q=noCorte');
            $data['folio'] = $this->input->get()['folio'];
            // Conseguir datos del cortes
            $extensiones = array(
                "jpg",
                "jpeg",
                "png"
            );
            $ban = false;
            foreach ($extensiones as $key2 => $extension)
            {
                $url = base_url() . "img/fotos/" . $this->input->get()['folio'] . "." . $extension;
                $headers = get_headers($url);
                if (stripos($headers[0], "200 OK"))
                {
                    $ban = true;
                    $imagen = "<img src='" . base_url() . "img/fotos/" . $this->input->get()['folio'] . "." . $extension . "' class='img-fluid' alt='Responsive image'>";
                    break;
                }
            }
            if (! $ban)
                $imagen = "No hay imágen";
            // Información del corte
            $this->load->model("corte");
            $corte = $this->corte->getByFolioGeneral($this->input->get()['folio'])[0];
            $corte['imagen'] = $imagen;
            $data['corte'] = $corte;
            $titulo['titulo'] = "Reporte de costos del corte con folio " . $this->input->get()['folio'];
            $this->load->view('comunes/head', $titulo);
            $this->load->view('administracion/menu');
            $this->load->view('administracion/reporteCostosEspecifico', $data);
            $this->load->view('comunes/foot');
        }
        else
        {
            if ($this->input->post())
            {
                if (! isset($this->input->post()['id']))
                    redirect('/administracion/index?q=error');
                $this->load->model(array(
                    'Corte',
                    'CorteAutorizado',
                    'CorteAutorizadoDatos',
                    'SalidaInterna1',
                    'SalidaInterna1Datos',
                    'Reproceso',
                    'EntregaAlmacen',
                    'EntregaExterna'
                ));
                $corte = $this->Corte->getByFolioGeneral($this->input->post()['id']);
                if (count($corte) == 0)
                    redirect('/administracion/index?q=error');
                $corte = $corte[0];
                $this->load->library('pdf');
                // tamaño 190 mm
                $pdf = new Pdf(utf8_decode("Reporte de costos del folio " . $this->input->post()['id']));
                // Agregamos una página
                $pdf->SetAutoPageBreak(1, 20);
                // Define el alias para el número de página que se imprimirá en el pie
                $pdf->AliasNbPages();
                $pdf->AddPage();
                /*
                 * Se define el titulo, márgenes izquierdo, derecho y
                 * el color de relleno predeterminado
                 */
                $pdf->SetTitle(utf8_decode("Reporte de costos del folio " . $this->input->post()['id']));
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, "Datos generales", 0, 1, 'C');
                $pdf->ln(5);
                
                // Datos generales
                $pdf->SetWidths(array(
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111
                ));
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->Row(array(
                    utf8_decode("Imágen\n\n\n"),
                    utf8_decode("Fecha de entrada\n\n"),
                    utf8_decode("Corte\n\n\n"),
                    utf8_decode("Marca\n\n\n"),
                    utf8_decode("Maquilero\n\n\n"),
                    utf8_decode("Cliente\n\n\n"),
                    utf8_decode("Tipo de pantalón o prenda"),
                    utf8_decode("Piezas\n\n\n"),
                    utf8_decode("Ojales\n\n\n")
                ));
                // Información del corte
                $pdf->SetFont('Arial', '', 8);
                $extensiones = array(
                    "jpg",
                    "jpeg",
                    "png"
                );
                $ban = false;
                foreach ($extensiones as $key2 => $extension)
                {
                    $url = base_url() . "img/fotos/" . $this->input->post()['id'] . "." . $extension;
                    $headers = get_headers($url);
                    if (stripos($headers[0], "200 OK"))
                    {
                        $ban = true;
                        $imagen = base_url() . "img/fotos/" . $this->input->post()['id'] . "." . $extension;
                        break;
                    }
                }
                $pdf->SetWidths(array(
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111
                ));
                $cadena = "";
                if ($ban)
                    $pdf->Image($imagen, NULL, NULL, 21.111111111, 10);
                else
                    $cadena = utf8_decode("No hay imágen");
                $pdf->Cell(21.111111111, 0, $cadena, 1, 10, "");
                $pdf->SetY($pdf->getY() - 10);
                $pdf->SetX($pdf->getX() + 21.111111111);
                $corte = $this->Corte->getByFolioGeneral($this->input->post()['id'])[0];
                $corte['imagen'] = $imagen;
                $pdf->ban = false;
                $pdf->Row(array(
                    utf8_decode($corte['fecha']),
                    utf8_decode($corte['corte']),
                    utf8_decode($corte['marca']),
                    utf8_decode($corte['maquilero']),
                    utf8_decode($corte['cliente']),
                    utf8_decode($corte['tipo']),
                    utf8_decode($corte['piezas']),
                    utf8_decode($corte['ojales'])
                ));
                
                // Autorización del corete
                $pdf->ln(10);
                $corte = $this->CorteAutorizado->getByFolioEspecifico($this->input->post()['id']);
                if (count($corte) == 0)
                    redirect('/administracion/index?q=error');
                $corte = $corte[0];
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, utf8_decode("Datos generales de autorización del corte"), 0, 1, 'C');
                $pdf->ln(5);
                $pdf->SetWidths(array(
                    63.333333333,
                    63.333333333,
                    63.333333333
                ));
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->Row(array(
                    utf8_decode("Fecha de autorización"),
                    utf8_decode("Usuario que autorizó"),
                    utf8_decode("Número de cargas")
                ));
                // Información del corte
                $pdf->SetFont('Arial', '', 8);
                $pdf->ban = false;
                $pdf->Row(array(
                    utf8_decode($corte['fecha']),
                    utf8_decode($corte['operario']),
                    utf8_decode($corte['cargas'])
                ));
                
                // Datos específicos de autorización del corte del corete
                $pdf->ln(10);
                $corte = $this->CorteAutorizadoDatos->reporte($this->input->post()['id']);
                if (count($corte) == 0)
                    redirect('/administracion/index?q=error');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, utf8_decode("Datos específicos de autorización del corte"), 0, 1, 'C');
                $pdf->ln(5);
                $pdf->SetWidths(array());
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->SetWidths(array(
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111,
                    21.111111111
                ));
                $pdf->Row(array(
                    utf8_decode("Lavado o carga\n\n"),
                    utf8_decode("Proceso\n\n\n"),
                    utf8_decode("Costo del proceso\n\n"),
                    utf8_decode("Piezas que se trabajaron\n\n"),
                    utf8_decode("Defectos encontrados\n\n"),
                    utf8_decode("Estado del proceso\n\n"),
                    utf8_decode("Orden en el que se registró"),
                    utf8_decode("Fecha en el que se registró"),
                    utf8_decode("Usuario que registró\n\n")
                ));
                // Información del corte
                $pdf->SetFont('Arial', '', 8);
                $pdf->ban = false;
                foreach ($corte as $key => $value)
                {
                    $lavado = $value['lavado'];
                    $proceso = $value['proceso'];
                    $costo = $value['costo'];
                    $piezas = $value['piezas'];
                    $defectos = $value['defectos'];
                    $estado = $value['status'];
                    $orden = $value['orden'];
                    $fecha = $value['fecha'];
                    $usuario = $value['usuarioc'];
                    switch ($estado)
                    {
                        case 0:
                            $estado = "No se ha registrado";
                            break;
                        case 1:
                            $estado = "Listo para registrar";
                            break;
                        case 2:
                            $estado = "Registrado";
                            break;
                    }
                    $pdf->Row(array(
                        utf8_decode($lavado),
                        utf8_decode($proceso),
                        utf8_decode("$" . $costo),
                        utf8_decode($piezas),
                        utf8_decode($defectos),
                        utf8_decode($estado),
                        utf8_decode($orden),
                        utf8_decode($fecha),
                        utf8_decode($usuario)
                    ));
                }
                
                // Datos generales de salida interna
                $pdf->ln(10);
                $corte = $this->SalidaInterna1->getByFolioEspecifico($this->input->post()['id']);
                if (count($corte) == 0)
                    redirect('/administracion/index?q=error');
                $corte = $corte[0];
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, utf8_decode("Datos generales de salida interna del corte"), 0, 1, 'C');
                $pdf->ln(5);
                $pdf->SetWidths(array());
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->SetWidths(array(
                    63.333333333,
                    63.333333333,
                    63.333333333
                ));
                $pdf->Row(array(
                    utf8_decode("Fecha en que se dio la salida interna"),
                    utf8_decode("Muestras para el corte"),
                    utf8_decode("Usuario que dio la salida interna")
                ));
                // Información del corte
                $pdf->SetFont('Arial', '', 8);
                $pdf->ban = false;
                $pdf->Row(array(
                    utf8_decode($corte['fecha']),
                    utf8_decode($corte['muestras']),
                    utf8_decode($corte['usuario'])
                ));
                
                // Datos específicos de salida interna
                $pdf->ln(10);
                $corte = $this->SalidaInterna1Datos->getByFolioEspecifico2($this->input->post()['id']);
                if (count($corte) == 0)
                    redirect('/administracion/index?q=error');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, utf8_decode("Datos específicos de salida interna del corte"), 0, 1, 'C');
                $pdf->ln(5);
                $pdf->SetWidths(array());
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->SetWidths(array(
                    95,
                    95
                ));
                $pdf->Row(array(
                    utf8_decode("Carga o lavado"),
                    utf8_decode("Piezas destinadas a la carga o lavado")
                ));
                // Información del corte
                $pdf->SetFont('Arial', '', 8);
                $pdf->ban = false;
                foreach ($corte as $key => $value)
                {
                    $pdf->Row(array(
                        utf8_decode($value['lavado']),
                        utf8_decode($value['piezas'])
                    ));
                }
                
                // Entrega almacen
                $pdf->ln(10);
                $pdf->SetFont('Arial', 'B', 10);
                $corte = $this->EntregaAlmacen->getByFolioEspecifico($this->input->post()['id']);
                if (count($corte) > 0)
                {
                    $corte = $corte[0];
                    $pdf->Cell(0, 0, utf8_decode("Información de entrega a almacén"), 0, 1, 'C');
                    $pdf->ln(5);
                    $pdf->SetWidths(array());
                    // Encabezado de tabla
                    $pdf->SetFillColor(59, 131, 189);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->ban = true;
                    $pdf->SetWidths(array(
                        95,
                        95
                    ));
                    $pdf->Row(array(
                        utf8_decode("Fecha de entrega a almacen"),
                        utf8_decode("Usuario que dio salida a almacén")
                    ));
                    // Información del corte
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->ban = false;
                    $pdf->Row(array(
                        utf8_decode($corte['fecha']),
                        utf8_decode($corte['usuario'])
                    ));
                }
                else
                    $pdf->Cell(0, 0, utf8_decode("No hay datos de entrega a almacen de este corte"), 0, 1, 'C');
                
                // Entrega externa
                $pdf->ln(10);
                $pdf->SetFont('Arial', 'B', 10);
                $corte = $this->EntregaExterna->getByFolioEspecifico($this->input->post()['id']);
                if (count($corte) > 0)
                {
                    $corte = $corte[0];
                    $pdf->Cell(0, 0, utf8_decode("Información de entrega externa"), 0, 1, 'C');
                    $pdf->ln(5);
                    $pdf->SetWidths(array());
                    // Encabezado de tabla
                    $pdf->SetFillColor(59, 131, 189);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->ban = true;
                    $pdf->SetWidths(array(
                        95,
                        95
                    ));
                    $pdf->Row(array(
                        utf8_decode("Fecha de entrega externa"),
                        utf8_decode("Usuario que dio salida externa")
                    ));
                    // Información del corte
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->ban = false;
                    $pdf->Row(array(
                        utf8_decode($corte['fecha']),
                        utf8_decode($corte['usuario'])
                    ));
                }
                else
                    $pdf->Cell(0, 0, utf8_decode("No hay datos de entrega externa de este corte"), 0, 1, 'C');
                
                // datos de costos de producción de proceso seco
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, utf8_decode("Costos de producción de proceso seco"), 0, 1, 'C');
                $pdf->ln(5);
                $pdf->SetWidths(array());
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->SetWidths(array(
                    38,
                    38,
                    38,
                    38,
                    38
                ));
                $pdf->Row(array(
                    utf8_decode("Carga o lavado"),
                    utf8_decode("Proceso"),
                    utf8_decode("Piezas trabajadas"),
                    utf8_decode("Costo unitario"),
                    utf8_decode("Total")
                ));
                // Información del corte
                $pdf->SetFont('Arial', '', 8);
                $pdf->ban = false;
                foreach ($this->input->post()['lavadoProduccion'] as $key => $value)
                {
                    foreach ($value as $key2 => $value2)
                    {
                        $pdf->Row(array(
                            utf8_decode($value2),
                            utf8_decode($this->input->post()['procesoProduccion'][$key][$key2]),
                            utf8_decode($this->input->post()['piezasProduccion'][$key][$key2]),
                            utf8_decode("$" . $this->input->post()['costoProduccion'][$key][$key2]),
                            utf8_decode("$" . $this->input->post()['totalProduccion'][$key][$key2])
                        ));
                    }
                }
                
                // Producción de reprocesos
                $pdf->ln(10);
                $pdf->SetFont('Arial', 'B', 10);
                if (isset($this->input->post()['lavadoProduccionReprocesos']))
                {
                    // datos de costos
                    $pdf->Cell(0, 0, utf8_decode("Costos de producción de reprocesos"), 0, 1, 'C');
                    $pdf->ln(5);
                    $pdf->SetWidths(array());
                    // Encabezado de tabla
                    $pdf->SetFillColor(59, 131, 189);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->ban = true;
                    $pdf->SetWidths(array(
                        38,
                        38,
                        38,
                        38,
                        38
                    ));
                    $pdf->Row(array(
                        utf8_decode("Carga o lavado"),
                        utf8_decode("Proceso"),
                        utf8_decode("Piezas trabajadas"),
                        utf8_decode("Costo unitario"),
                        utf8_decode("Total")
                    ));
                    // Información del corte
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->ban = false;
                    foreach ($this->input->post()['lavadoProduccionReprocesos'] as $key => $value)
                    {
                        foreach ($value as $key2 => $value2)
                        {
                            $pdf->Row(array(
                                utf8_decode($value2),
                                utf8_decode($this->input->post()['procesoProduccionReprocesos'][$key][$key2]),
                                utf8_decode($this->input->post()['piezasProduccionReprocesos'][$key][$key2]),
                                utf8_decode("$" . $this->input->post()['costoProduccionReprocesos'][$key][$key2]),
                                utf8_decode("$" . $this->input->post()['totalProduccionReprocesos'][$key][$key2])
                            ));
                        }
                    }
                }
                else
                    $pdf->Cell(0, 0, utf8_decode("No hay datos de producción de reprocesos de este corte"), 0, 1, 'C');
                
                // Totales
                $pdf->ln(10);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 0, utf8_decode("Totales"), 0, 1, 'C');
                $pdf->ln(5);
                $pdf->SetWidths(array());
                // Encabezado de tabla
                $pdf->SetFillColor(59, 131, 189);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->ban = true;
                $pdf->SetWidths(array(
                    95,
                    95
                ));
                $pdf->Row(array(
                    utf8_decode("Total de produccion de proceso seco"),
                    utf8_decode("$" . $this->input->post()['totalProduccion-'])
                ));
                $pdf->Row(array(
                    utf8_decode("Total de producción de reprocesos"),
                    utf8_decode("$" . $this->input->post()['totalReprocesos-'])
                ));
                $pdf->Row(array(
                    utf8_decode("Total"),
                    utf8_decode("$" . $this->input->post()['total-'])
                ));
                
                /*
                 * Se manda el pdf al navegador
                 *
                 * $this->pdf->Output(nombredelarchivo, destino);
                 *
                 * I = Muestra el pdf en el navegador
                 * D = Envia el pdf para descarga
                 *
                 */
                $pdf->Output(utf8_decode("Reporte de costos del folio " . $this->input->post()['id']) . ".pdf", 'I');
            }
            else
            {
                $titulo['titulo'] = 'Generar reporte de costos';
                $this->load->view('comunes/head', $titulo);
                $this->load->view('administracion/menu');
                $this->load->view('administracion/reporteCostos');
                $this->load->view('comunes/foot');
            }
        }
    }
}
