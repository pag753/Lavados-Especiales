<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produccion extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $idusuario = $_SESSION['id'];
    if (!in_array($idusuario,array(3,5,1,2))) redirect('/');
  }

  public function index($datos = null)
  {
    if ($datos == null)
    $data = array(
      'texto1' => "Bienvenido(a)",
      'texto2' => $_SESSION['username']
    );
    elseif ($datos < 0)
    $data = array(
      'texto1' => "Los datos",
      'texto2' => "Se han actualizado con éxito"
    );
    else
    $data = array(
      'texto1' => "El corte con folio " . $datos,
      'texto2' => "Se ha autorizado con éxito"
    );
    $titulo = null;
    $titulo['titulo'] = 'Bienvenido a lavados especiales';
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('comunes/index', $data);
    $this->load->view('comunes/foot');
  }

  public function autorizar()
  {
    if ($this->input->post())
    {
      if ($this->input->post()['cargas'] == 0) redirect('/');
      //Cargar modelos
      $this->load->model(array('corteAutorizado','corteAutorizadoDatos','procesoSeco'));
      //Conseguir los costos
      $ps = $this->procesoSeco->get();
      $precios = null;
      foreach ($ps as $value) $precios[$value['id']] = $value['costo'];
      for ($i=1; $i <= $this->input->post()['cargas'] ; $i++)
      {
        //insertar en corte autorizado
        $data = null;
        $data['corte_folio'] = $this->input->post()['folio'];
        $data['fecha_autorizado'] = $this->input->post()['fecha'];
        $data['id_carga'] = $i;
        $data['lavado_id'] = $this->input->post()['lavado'][$i];
        $data['color_hilo'] = $this->input->post()['color_hilo'][$i];
        $data['tipo'] = $this->input->post()['tipo'][$i];
        $n = $this->corteAutorizado->agregar($data);
        foreach ($this->input->post()['proceso_seco'][$i] as $value)
        {
          //Insertar en corte autorizado datos
          $data = null;
          $data['corte_autorizado_id'] = $n;
          $data['proceso_seco_id'] = $value;
          $data['costo'] = $precios[$value];
          $data['status'] = ($data['costo'] == 0)? 2 : 0;
          $data['piezas_trabajadas'] = 0;
          $data['defectos'] = 0;
          $data['orden'] = 0;
          $data['fecha_registro'] = $this->input->post()['fecha'];
          $this->corteAutorizadoDatos->agregar($data);
        }
      }
      redirect('/produccion/index/' . $data['datos_corte']['folio']);
      /*
      $datos['datos_corte'] = $this->input->post();
      $this->load->model('corte');
      $resultado = $this->corte->getByFolio($datos['datos_corte']['folio']);

      $resultado2 = $this->corteAutorizado->getByFolio($datos['datos_corte']['folio']);
      $data['corte_folio'] = $datos['datos_corte']['folio'];
      $data['fecha_autorizado'] = substr($datos['datos_corte']['fecha'], 0, 10);
      if ($this->input->post()['lavados'] != 0)
      {
        $data['cargas'] = count($this->input->post()['lavado']);
        $this->load->model('corteAutorizadoDatos');
        $data['usuario_id'] = $_SESSION['usuario_id'];
        $this->corteAutorizado->agregar($data);
        $data = null;
        $data['corte_folio'] = $datos['datos_corte']['folio'];
        $contador = 1;
        foreach ($this->input->post()['lavado'] as $key => $value)
        {
          $this->load->model('procesoSeco');
          $ps = $this->procesoSeco->get();
          foreach ($ps as $key2 => $value) $precios[$value['id']] = $value['costo'];
          $data['id_carga'] = $contador;
          $data['lavado_id'] = $this->input->post()['lavado'][$key];
          foreach ($this->input->post()['proceso_seco'][$key] as $num => $valor)
          {
            $data['proceso_seco_id'] = $valor;
            $data['costo'] = $precios[$valor];
            if ($data['costo'] == 0) $data['status'] = 2;
            else $data['status'] = 0;
            $data['usuario_id'] = $_SESSION['usuario_id'];
            $data['fecha_registro'] = date('Y-m-d');
            $n = $this->corteAutorizadoDatos->agregar($data);
          }
          $contador ++;
        }
        redirect('/produccion/index/' . $datos['datos_corte']['folio']);
      }*/

    }
    else $this->cargarAutorizacion('', 'Autorización de Corte', 'Ingrese los datos');
  }

  private function cargarAutorizacion($entrada = null, $texto1, $texto2)
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
    $titulo = null;
    $titulo['titulo'] = 'Autorizar corte';
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('produccion/cargarAutorizacion', $datos);
    $this->load->view('comunes/foot');
  }
}
