<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operario extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $idusuario = $_SESSION['id'];
    if (!in_array($idusuario,array(4,5,6,7))) redirect('/');
  }

  public function index($datos = null)
  {
    if ($this->input->get())
    {
      if (! isset($this->input->get()['q'])) redirect('/');
      switch ($this->input->get()['q'])
      {
        case 'error':
        $data = array(
          'texto1' => "Se produjo un error",
          'texto2' => "Favor de notificar al administrador"
        );
        break;

        case 'reproceso':
        $data = array(
          'texto1' => "La producción de reproceso",
          'texto2' => "Se ha registrado con éxito"
        );
        break;

        case 'cerrarReproceso':
        $data = array(
          'texto1' => "El reproceso",
          'texto2' => "Se ha cerrado con éxito"
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
    $titulo = null;
    $titulo['titulo'] = 'Bienvenido a lavados especiales';
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('comunes/index', $data);
    $this->load->view('comunes/foot');
  }

  public function alta($f = null)
  {
    if(!in_array($_SESSION['id'],array(4,7))) redirect('/');
    $titulo = null;
    $titulo['titulo'] = 'Cerrar proceso';
    if ($this->input->post())
    {
      //si es POST
      $this->load->model('corteAutorizadoDatos');
      // Actualizando datos de proceso actual
      $this->corteAutorizadoDatos->actualiza2($this->input->post()['anterior'], $this->input->post()['piezas_trabajadas'], $this->input->post()['defectos'], date("Y/m/d"), $_SESSION['usuario_id']);
      // Actualizando datos de proceso siguiente
      if (isset($this->input->post()['siguiente'])) $this->corteAutorizadoDatos->actualiza($this->input->post()['siguiente'],  $this->input->post()['piezas_trabajadas'], $this->input->post()['orden'] + 1);
      redirect("/operario/alta/".$f);
    }
    elseif ($this->input->get())
    {
      //si es GET
      $this->load->model('corteAutorizadoDatos');
      $query = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros2($this->input->get()['id']);
      $data = $this->input->get();
      $data['piezas'] = $query[0]['piezas'];
      $data['nombreCarga'] = $query[0]['lavado'];
      $data['nombreProceso'] = $query[0]['proceso'];
      $data['idlavado'] = $query[0]['idlavado'];
      $data['orden'] = $query[0]['orden'];
      $data['faltantes'] = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros4($this->input->get()['f'], $this->input->get()['c']);
      $data['trabajadas'] = 0;
      $data['defectos'] = 0;
      $this->load->model('produccionProcesoSeco');
      $query = $this->produccionProcesoSeco->seleccionDefinidaPorProceso($this->input->get()['id']);
      foreach ($query as $key => $value)
      {
        $data['trabajadas'] += $value['piezas'];
        $data['defectos'] += $value['defectos'];
      }
      $data['query'] = $query;
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operario/altaConfirmacion', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      //si no es ninguno
      $data['folio'] = $f;
      $data['url'] = base_url() . "index.php/operario/insertar";
      $titulo = null;
      $titulo['titulo'] = 'Cerrar proceso';
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operario/alta', $data);
      $this->load->view('comunes/foot');
    }
  }


  public function insertar($f = null)
  {
    if ($this->input->post())
    {
      $this->load->model('produccionProcesoSeco');
      $datos = $this->input->post();
      $datos['usuario_id'] = $_SESSION['usuario_id'];
      if ($this->input->post()['nuevo'] == 1) $this->produccionProcesoSeco->insertar($datos);
      else $this->produccionProcesoSeco->editar($datos);
      redirect("/operario/insertar/".$f);
    }
    else
    {
      if ($this->input->get())
      {
        $data = $this->input->get();
        $this->load->model('produccionProcesoSeco');
        $query = $this->produccionProcesoSeco->seleccionDefinida($this->input->get()['id']);
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
      $data['f'] = $f;
      $data['url'] = base_url() . "index.php/operario/insertar";
      $titulo = null;
      $titulo['titulo'] = 'Insertar producción';
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operarios/insertar', $data);
      $this->load->view('comunes/foot');
    }
  }

  public function ver()
  {
    if (! $this->input->post())
    {
      $data['data'] = "operario";
      $titulo['titulo'] = "Ver Producción";
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operarios/verProduccion', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      $this->load->model("ProduccionProcesoSeco");
      $reporte = $this->ProduccionProcesoSeco->verProduccion($_SESSION['usuario_id'], $this->input->post()['fechaInicio'], $this->input->post()['fechaFinal']);
      $this->load->model("Descuentos");
      $descuentos = $this->Descuentos->consulta1($_SESSION['usuario_id'], $this->input->post()['fechaInicio'], $this->input->post()['fechaFinal']);
      $this->load->library('Comunes');
      $comunes = new Comunes();
      $comunes->verProduccion($reporte, $this->input->post()['fechaInicio'], $this->input->post()['fechaFinal'], $descuentos);
    }
  }

  public function verAhorro()
  {
    $this->load->model("Ahorros");
    $data = null;
    $data['ahorros'] = $this->Ahorros->getByIdUsuario($_SESSION['usuario_id']);
    $titulo = null;
    $titulo['titulo'] = "Ver caja de ahorro";
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('operarios/verAhorro', $data);
    $this->load->view('comunes/foot');
  }

  public function insertarReproceso()
  {
    if ($this->input->get())
    {
      // Validación de datos
      if (! isset($this->input->get()['id']) || ! isset($this->input->get()['lavado']) || ! isset($this->input->get()['proceso']) || ! is_numeric($this->input->get()['id']))
      redirect("operario/index?q=error");
      // Ver si ya hay produccion de este usuario en la base de datos
      $data = array(
        'reproceso_id' => $this->input->get()['id'],
        'usuario_id' => $_SESSION['usuario_id']
      );
      $this->load->model("ProduccionReproceso");
      $query = $this->ProduccionReproceso->getWhere($data);
      $data = $this->input->get();
      if (count($query) == 0)
      {
        $data['tipo'] = 0;
        $data['piezas'] = 0;
        $data['defectos'] = 0;
      }
      else
      {
        $data['tipo'] = 1;
        $data['piezas'] = $query[0]['piezas'];
        $data['defectos'] = $query[0]['defectos'];
      }
      $titulo['titulo'] = "Insertar producción de reproceso";
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operarios/insertarProduccionReproceso', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      if ($this->input->post())
      {
        if (! isset($this->input->post()['tipo']) || ! isset($this->input->post()['id']) || ! isset($this->input->post()['piezas']) || ! isset($this->input->post()['defectos']) || ! is_numeric($this->input->post()['tipo']) || ! is_numeric($this->input->post()['id']) || ! is_numeric($this->input->post()['piezas']) || ! is_numeric($this->input->post()['defectos']))
        redirect("operario/index?q=error");
        $this->load->model("ProduccionReproceso");
        $data = array(
          'usuario_id' => $_SESSION['usuario_id'],
          'piezas' => $this->input->post()['piezas'],
          'fecha' => date('Y-m-d'),
          'defectos' => $this->input->post()['defectos'],
          'reproceso_id' => $this->input->post()['id']
        );
        switch ($this->input->post()['tipo'])
        {
          case 0:
          // Nuevo registro
          $this->ProduccionReproceso->insertar($data);
          redirect("operario/index?q=reproceso");
          break;

          case 1:
          // registro ya existente
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
        $titulo['titulo'] = "Reproceso";
        $this->load->view('comunes/head', $titulo);
        $this->cargarMenu();
        $this->load->view('operarios/produccionReproceso');
        $this->load->view('comunes/foot');
      }
    }
  }

  public function cerrarReproceso()
  {
    if($_SESSION['id'] != 4) redirect('/');
    if ($this->input->get())
    {
      // validación
      if (! isset($this->input->get()['id']) || ! isset($this->input->get()['lavado']) || ! isset($this->input->get()['proceso']))
      redirect("operario/index?q=error");
      $this->load->model(array(
        'ProduccionReproceso',
        'Reproceso'
      ));
      // Recuerar datos
      $data = $this->input->get();
      $data['reprocesos'] = $this->ProduccionReproceso->getByIdEspecifico($this->input->get()['id']);
      $data['reproceso'] = $this->Reproceso->getById($this->input->get()['id'])[0];
      if (count($data['reproceso']) == 0) redirect("operario/index?q=error");
      if ($data['reproceso']['status'] == 2) redirect("operario/index?q=error");
      $titulo['titulo'] = "Cerrar reproceso";
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operario/cerrarReprocesoEspecifico', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      if ($this->input->post())
      {
        // Validación
        if (! isset($this->input->post()['id']) || ! isset($this->input->post()['piezas_trabajadas']) || ! isset($this->input->post()['defectos']) || ! is_numeric($this->input->post()['id']) || ! is_numeric($this->input->post()['piezas_trabajadas']) || ! is_numeric($this->input->post()['defectos'])) redirect("operario/index?q=error");
        print_r($this->input->post());
        $data = array(
          'piezas_trabajadas' => $this->input->post()['piezas_trabajadas'],
          'defectos' => $this->input->post()['defectos'],
          'status' => 2,
          'usuario_id' => $_SESSION['usuario_id'],
          'id' => $this->input->post()['id']
        );
        $this->load->model('Reproceso');
        $this->Reproceso->update($data);
        redirect("operario/index?q=cerrarReproceso");
      }
      else
      {
        $titulo['titulo'] = "Cerrar reproceso";
        $this->load->view('comunes/head', $titulo);
        $this->cargarMenu();
        $this->load->view('operario/cerrarReproceso');
        $this->load->view('comunes/foot');
      }
    }
  }

  public function verNominas()
  {
    if ($this->input->get())
    {
      if (! isset($this->input->get()['id']) || ! isset($this->input->get()['fecha']) || ! is_numeric($this->input->get()['id']))
      redirect("operario/index?q=error");
      $this->load->model(array(
        'Nomina',
        'ProduccionProcesoSeco',
        'ProduccionReproceso'
      ));
      $data = array(
        'usuario_id' => $_SESSION['usuario_id'],
        'id' => $this->input->get()['id']
      );
      $nomina = $this->Nomina->getWhere($data);
      if (count($nomina) < 1)
      redirect("operario/index?q=error");
      $data['nomina'] = $nomina[0];
      $datos = array(
        'id_nomina' => $this->input->get()['id'],
        'usuario_id' => $_SESSION['usuario_id']
      );
      $data['fecha'] = $this->input->get()['fecha'];
      $data['produccion'] = $this->ProduccionProcesoSeco->getWhereEspecifico($datos);
      $data['produccionReprocesos'] = $this->ProduccionReproceso->getWhereEspecifico($datos);
      $titulo['titulo'] = "Ver nómina";
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('operarios/verNominasEspecifico', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      if ($this->input->post())
      {
        // print_r($this->input->post());
        $this->load->library('pdf');
        // tamaño 190 mm
        $pdf = new Pdf(utf8_decode("Ver nómina del generada el " . $this->input->post()['fecha']));
        // Agregamos una página
        $pdf->SetAutoPageBreak(1, 20);
        // Define el alias para el número de página que se imprimirá en el pie
        $pdf->AliasNbPages();
        $pdf->AddPage();
        /*
        * Se define el titulo, márgenes izquierdo, derecho y
        * el color de relleno predeterminado
        */
        $pdf->SetTitle(utf8_decode("Ver nómina del generada el " . $this->input->post()['fecha']));

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 0, utf8_decode("Datos generales de la nómina"), 0, 1, 'C');
        $pdf->ln(5);
        // Datos generales
        $pdf->SetWidths(array(
          95,
          95
        ));
        // Encabezado de tabla
        $pdf->SetFillColor(59, 131, 189);
        $pdf->SetFont('Arial', '', 8);
        // $pdf->ban = true;
        $pdf->Row(array(
          utf8_decode('Saldo anterior'),
          utf8_decode('$' . $this->input->post()['saldo_anterior'])
        ));
        $pdf->Row(array(
          utf8_decode('Total de producción'),
          utf8_decode('$' . $this->input->post()['nomina'])
        ));
        $pdf->Row(array(
          utf8_decode('Saldo anterior de descuentos'),
          utf8_decode('$' . $this->input->post()['descuentos_anterior'])
        ));
        $pdf->Row(array(
          utf8_decode('Aportación a los descuentos'),
          utf8_decode('$' . $this->input->post()['descuentos_abono'])
        ));
        $pdf->Row(array(
          utf8_decode('Saldo de descuentos'),
          utf8_decode('$' . $this->input->post()['descuentos_saldo'])
        ));
        $pdf->Row(array(
          utf8_decode('Saldo anterior de ahorro'),
          utf8_decode('$' . $this->input->post()['ahorro_anterior'])
        ));
        $pdf->Row(array(
          utf8_decode('Aportación al ahorro'),
          utf8_decode('$' . $this->input->post()['ahorro_abono'])
        ));
        $pdf->Row(array(
          utf8_decode('Saldo de ahorro'),
          utf8_decode('$' . $this->input->post()['ahorro_saldo'])
        ));
        $pdf->Row(array(
          utf8_decode('Bonos'),
          utf8_decode('$' . $this->input->post()['bonos'])
        ));
        $pdf->Row(array(
          utf8_decode('Total'),
          utf8_decode('$' . $this->input->post()['total'])
        ));
        $pdf->Row(array(
          utf8_decode('Cantidad que se pagó'),
          utf8_decode('$' . $this->input->post()['pagado'])
        ));

        // Datos de la producción en proceso seco.
        if (isset($this->input->post()['produccion_folio']))
        {
          $pdf->ln(5);
          $pdf->SetFont('Arial', 'B', 10);
          $pdf->Cell(0, 0, utf8_decode("Datos de la producción en proceso seco"), 0, 1, 'C');
          $pdf->ln(5);
          // Datos generales
          $pdf->SetWidths(array(
            19,
            19,
            19,
            19,
            19,
            19,
            19,
            19,
            19,
            19
          ));
          // Encabezado de tabla
          $pdf->SetFillColor(59, 131, 189);
          $pdf->SetFont('Arial', 'B', 8);
          $pdf->ban = true;
          $pdf->Row(array(
            utf8_decode("Folio del corte\n\n"),
            utf8_decode("Fecha de registro\n\n"),
            utf8_decode("Carga o lavado\n\n"),
            utf8_decode("Proceso\n\n\n"),
            utf8_decode("Piezas registradas\n\n"),
            utf8_decode("Defectos registrados\n\n"),
            utf8_decode("Precio unitario\n\n"),
            utf8_decode("Cantidad para pagar\n\n"),
            utf8_decode("Estado de nómina\n\n"),
            utf8_decode("Razón por la que no se pagó")
          ));

          $pdf->SetFont('Arial', '', 8);
          $pdf->ban = false;
          foreach ($this->input->post()['produccion_folio'] as $key => $value)
          {
            $pdf->Row(array(
              utf8_decode($this->input->post()['produccion_folio'][$key]),
              utf8_decode($this->input->post()['produccion_fecha'][$key]),
              utf8_decode($this->input->post()['produccion_lavado'][$key]),
              utf8_decode($this->input->post()['produccion_proceso'][$key]),
              utf8_decode($this->input->post()['produccion_piezas'][$key]),
              utf8_decode($this->input->post()['produccion_defectos'][$key]),
              utf8_decode("$" . $this->input->post()['produccion_unitario'][$key]),
              utf8_decode("$" . $this->input->post()['produccion_cantidad_pagar'][$key]),
              utf8_decode($this->input->post()['produccion_razon'][$key]),
              utf8_decode($this->input->post()['produccion_razon_pagar'][$key])
            ));
          }
        }

        // Datos de la producción en proceso seco.
        if (isset($this->input->post()['reprocesos_folio']))
        {
          $pdf->ln(5);
          $pdf->SetFont('Arial', 'B', 10);
          $pdf->Cell(0, 0, utf8_decode("Datos de la producción en proceso seco"), 0, 1, 'C');
          $pdf->ln(5);
          // Datos generales
          $pdf->SetWidths(array(
            19,
            19,
            19,
            19,
            19,
            19,
            19,
            19,
            19,
            19
          ));
          // Encabezado de tabla
          $pdf->SetFillColor(59, 131, 189);
          $pdf->SetFont('Arial', 'B', 8);
          $pdf->ban = true;
          $pdf->Row(array(
            utf8_decode("Folio del corte\n\n"),
            utf8_decode("Fecha de registro\n\n"),
            utf8_decode("Carga o lavado\n\n"),
            utf8_decode("Proceso\n\n\n"),
            utf8_decode("Piezas registradas\n\n"),
            utf8_decode("Defectos registrados\n\n"),
            utf8_decode("Precio unitario\n\n"),
            utf8_decode("Cantidad para pagar\n\n"),
            utf8_decode("Estado de nómina\n\n"),
            utf8_decode("Razón por la que no se pagó")
          ));

          $pdf->SetFont('Arial', '', 8);
          $pdf->ban = false;
          foreach ($this->input->post()['produccion_folio'] as $key => $value)
          {
            $pdf->Row(array(
              utf8_decode($this->input->post()['reprocesos_folio'][$key]),
              utf8_decode($this->input->post()['reprocesos_fecha'][$key]),
              utf8_decode($this->input->post()['reprocesos_lavado'][$key]),
              utf8_decode($this->input->post()['reprocesos_proceso'][$key]),
              utf8_decode($this->input->post()['reprocesos_piezas'][$key]),
              utf8_decode($this->input->post()['reprocesos_defectos'][$key]),
              utf8_decode("$" . $this->input->post()['reprocesos_unitario'][$key]),
              utf8_decode("$" . $this->input->post()['reprocesos_cantidad_pagar'][$key]),
              utf8_decode($this->input->post()['reprocesos_razon'][$key]),
              utf8_decode($this->input->post()['reprocesos_razon_pagar'][$key])
            ));
          }
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
        $pdf->Output(utf8_decode("Ver nómina del generada el " . $this->input->post()['fecha']) . ".pdf", 'I');
      }
      else
      {
        // si no es ninguno
        $this->load->model(array(
          'ProduccionProcesoSeco',
          'ProduccionReproceso'
        ));
        $data['nominasProduccion'] = $this->ProduccionProcesoSeco->getNominasByOperario($_SESSION['usuario_id']);
        $data['nominasProduccionReproceso'] = $this->ProduccionReproceso->getNominasByOperario($_SESSION['usuario_id']);
        $titulo['titulo'] = "Ver nóminas";
        $this->load->view('comunes/head', $titulo);
        $this->cargarMenu();
        $this->load->view('operarios/verNominas', $data);
        $this->load->view('comunes/foot');
      }
    }
  }
}
