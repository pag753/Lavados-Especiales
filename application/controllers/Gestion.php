<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gestion extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $idusuario = $_SESSION['id'];
    if (! in_array($idusuario, array(
      1,
      2,
      5,
      8
    )))
      redirect('/');
  }

  public function index($datos = null)
  {
    if ($datos != null)
    {
      if ($datos == - 1)
      {
        $data = array(
          'texto1' => "Los datos",
          'texto2' => "Se han actualizado con éxito"
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
    else
    {
      $data = array(
        'texto1' => "Bienvenido(a)",
        'texto2' => $_SESSION['username']
      );
    }
    $titulo = array(
      'titulo' => 'Bienvenido a lavados especiales'
    );
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('comunes/index', $data);
    $this->load->view('comunes/foot');
  }

  public function alta()
  {
    if ($this->input->post())
    {
      // Recuperando datos de post
      $datos['datos_corte'] = $this->input->post();
      // Sección de para subir imágen
      $mi_imagen = 'mi_imagen';
      $config = array(
        'upload_path' => "img/fotos",
        'file_name' => $datos['datos_corte']['folio'],
        'allowed_types' => "gif|jpg|jpeg|png",
        'max_size' => "500000",
        'max_width' => "20000",
        'max_height' => "20000"
      );
      $this->load->library('upload', $config);
      if (! $this->upload->do_upload($mi_imagen))
        $data['uploadError'] = $this->upload->display_errors();
      $data['uploadSuccess'] = $this->upload->data();
      // Cuestion del ojal
      if (! isset($datos['datos_corte']['ojal']))
        $datos['datos_corte']['cantidadOjales'] = 0;
      $this->load->model('corte');
      $this->corte->agregar($datos['datos_corte']);
      redirect('/gestion/index/' . $datos['datos_corte']['folio']);
    }
    else
    {
      $this->load->model(array(
        'marca',
        'maquilero',
        'cliente',
        'tipo_pantalon',
        'corte'
      ));
      $c = $this->corte->get();
      if (count($c) == 0)
        $id_corte = 1;
      else
        $id_corte = $this->corte->get()[count($this->corte->get()) - 1]['folio'] + 1;
      $datos = array(
        'marcas' => $this->marca->get(),
        'maquileros' => $this->maquilero->get(),
        'clientes' => $this->cliente->get(),
        'tipos' => $this->tipo_pantalon->get(),
        'corte' => $id_corte
      );
      $titulo = array('titulo' => 'Alta de corte');
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/alta', $datos);
      $this->load->view('comunes/foot');
    }
  }

  public function ver()
  {
    $titulo = array('titulo' => 'Ver detalles de corte');
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('comunes/ver');
    $this->load->view('comunes/foot');
  }

  public function salidaInterna()
  {
    if ($this->input->post())
    {
      //$this->load->model('corteAutorizadoDatos');
      //$query = $this->corteAutorizadoDatos->joinLavado($this->input->post()['folio']);
      // $f = explode("/",$this->input->post()['fecha']);
      $this->load->model(array('salidaInterna1','salidaInterna1Datos','corteAutorizadoDatos'));
      $data['corte_folio'] = $this->input->post()['folio'];
      $data['fecha'] = date("Y-m-d");
      $data['muestras'] = $this->input->post()['muestras'];
      $data['usuario_id'] = $_SESSION['usuario_id'];
      //Agregar a la tabla de la salida interna
      $this->salidaInterna1->agregar($data);
      $data = null;
      //$this->load->model('salidaInterna1Datos');
      //$data['corte_folio'] = $this->input->post()['folio'];
      foreach ($this->input->post()['piezas_parcial'] as $key => $value)
      {
        //Agregar a la salida interna datos
        $data['corte_autorizado_id'] = $key;
        $data['piezas'] = $value;
        $this->salidaInterna1Datos->agregar($data);
        //actualizar en corte autorizado datos
        $this->corteAutorizadoDatos->actualiza($this->input->post()['primero'][$key], $value, 1);
      }
      /*
      for ($i = 1; $i <= $this->input->post()['cargas']; $i ++)
      {
        //$data['corte_autorizado_id'] = $;
        $data['piezas'] = $this->input->post()['piezas_parcial'][$i];
        $this->salidaInterna1Datos->agregar($data);
        $this->corteAutorizadoDatos->actualiza($this->input->post()['primero'][$i], $this->input->post()['folio'], $i + 1, $this->input->post()['piezas_parcial' . $i], 1);
      }*/
      redirect('/gestion/index/' . $this->input->post()['folio']);
    }
    else
    {
      $titulo = array('titulo' => 'Salida interna');
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/salidaInterna');
      $this->load->view('comunes/foot');
    }
  }

  public function salidaAlmacen()
  {
    if ($this->input->post())
    {
      $this->load->model('entregaAlmacen');
      $resp = $this->entregaAlmacen->agregar(array(
        'corte_autorizado_id' => $this->input->post()['id'],
        'usuario_id' => $_SESSION['usuario_id'],
        'fecha' => date("Y-m-d")
      ));
      echo $resp;
    }
    else
    {
      $titulo['titulo'] = 'Salida a almacen';
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/salidaAlmacen');
      $this->load->view('comunes/foot');
    }
  }

  public function salidaExterna()
  {
    if ($this->input->post())
    {
      $this->load->model('entregaExterna');
      $a = $this->entregaExterna->agregar(array(
        'corte_autorizado_id' => $this->input->post()['id'],
        'fecha' => date("Y-m-d"),
        'usuario_id' => $_SESSION['usuario_id'],
      ));
      echo $a;
      /*
       * redirect("/");
       */
    }
    else
    {
      $titulo['titulo'] = 'Salida externa';
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/salidaExterna');
      $this->load->view('comunes/foot');
    }
  }

  public function reportes()
  {
    $this->load->model(array(
      'Cliente',
      'Marca',
      'Maquilero',
      'Tipo_pantalon'
    ));
    $datos = array(
      'clientes' => $this->Cliente->get(),
      'marcas' => $this->Marca->get(),
      'maquileros' => $this->Maquilero->get(),
      'tipos' => $this->Tipo_pantalon->get()
    );
    $titulo['titulo'] = 'Reportes';
    $this->load->view('comunes/head', $titulo);
    $this->cargarMenu();
    $this->load->view('gestion/reportes', $datos);
    $this->load->view('comunes/foot');
  }

  public function generaReporte()
  {
    if (! $this->input->get())
      redirect('/');
    switch ($this->input->get()['reporte'])
    {
      case 1:
      /*
      * reporte de cortes en almacen de entrada
      * -> cortes autorizados y no autorizados
      * -> cortes sin salida interna
      */
      $this->reporte1();
        break;
      case 2:
      /*
      * reporte de cortes autorizados
      * cortes autorizados
      * cortes sin salida interna
      */
      $this->reporte2();
        break;
      case 3:
      /*
      * reporte de cargas entregadas
      * cargas con salida externa
      */
      $this->reporte3();
        break;
      case 4:
      /*
      * Reporte de cargas en producción
      * -> cargas autorizadas
      * -> cargas con salida interna
      * -> cargas sin salida a almacén
      */
      $this->reporte4();
        break;
      case 5:
      /*
      * Reporte de cargas en almacén de salida
      * -> cargas con salida a almacén
      * -> cargas sin salida externa
      */
      $this->reporte5();
        break;
    }
    // print_r($this->input->post());
  }

  /*
   * reporte de cortes en almacen de entrada
   * -> cortes autorizados y no autorizados
   * -> cortes sin salida interna
   */
  public function reporte1()
  {
    $titulo['titulo'] = 'Reporte de cortes en almacen de entrada.';
    if (! $this->input->post())
    {
      $this->load->model('corte');
      $data['data'] = $this->corte->reporte1();
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/reporte1', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      // Se carga la libreria fpdf
      $this->load->library('pdf');
      // Creacion del PDF
      /*
       * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
       * heredó todos las variables y métodos de fpdf
       */
      $pdf = new Pdf("Reporte de cortes en almacen de entrada");
      // Agregamos una página
      $pdf->SetAutoPageBreak(1, 20);
      // Define el alias para el número de página que se imprimirá en el pie
      $pdf->AliasNbPages();
      $pdf->AddPage();
      /*
       * Se define el titulo, márgenes izquierdo, derecho y
       * el color de relleno predeterminado
       */
      $pdf->SetTitle("Reporte de cortes en almacen de entrada");
      // 190 vertical
      $pdf->SetFillColor(59, 131, 189);
      $pdf->ban = true;
      $pdf->SetFont('Arial', 'B', 8);
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
        utf8_decode("Folio del corte"),
        utf8_decode("Fecha de entrada"),
        utf8_decode("Corte\n\n"),
        utf8_decode("Marca\n\n"),
        utf8_decode("Maquilero\n\n"),
        utf8_decode("Cliente\n\n"),
        utf8_decode("Tipo de pantalón"),
        utf8_decode("Número de piezas"),
        utf8_decode("Número de ojales")
      ));
      $pdf->SetFont('Arial', '', 8);
      $pdf->ban = false;
      foreach ($this->input->post()['fecha'] as $key => $value)
      {
        $pdf->Row(array(
          utf8_decode($key),
          utf8_decode($value),
          utf8_decode($this->input->post()['corte'][$key]),
          utf8_decode($this->input->post()['marca'][$key]),
          utf8_decode($this->input->post()['maquilero'][$key]),
          utf8_decode($this->input->post()['cliente'][$key]),
          utf8_decode($this->input->post()['tipo'][$key]),
          utf8_decode($this->input->post()['piezas'][$key]),
          utf8_decode($this->input->post()['ojales'][$key])
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
      $pdf->Output("Reporte de cortes en almacen de entrada.pdf", 'I');
    }
  }

  /*
   * reporte de cortes autorizados
   * cortes autorizados
   * cortes sin salida interna
   */
  public function reporte2()
  {
    $titulo['titulo'] = 'Reporte de cortes autorizados.';
    if (! $this->input->post())
    {
      $this->load->model('corte');
      $data['data'] = $this->corte->reporte2();
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/reporte2', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      // Se carga la libreria fpdf
      $this->load->library('pdf');
      // Creacion del PDF
      /*
       * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
       * heredó todos las variables y métodos de fpdf
       */
      $pdf = new Pdf("Reporte de cortes autorizados");
      // Agregamos una página
      $pdf->SetAutoPageBreak(1, 20);
      // Define el alias para el número de página que se imprimirá en el pie
      $pdf->AliasNbPages();
      $pdf->AddPage();
      /*
       * Se define el titulo, márgenes izquierdo, derecho y
       * el color de relleno predeterminado
       */
      $pdf->SetTitle("Reporte de cortes autorizados");
      // 190 vertical
      $pdf->SetFillColor(59, 131, 189);
      $pdf->ban = true;
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetWidths(array(
        63.333333333,
        63.333333333,
        63.333333333
      ));
      $pdf->Row(array(
        utf8_decode("Folio del corte"),
        utf8_decode("Carga o lavado"),
        utf8_decode("Proceso seco")
      ));
      $pdf->SetFont('Arial', '', 8);
      $pdf->ban = false;
      foreach ($this->input->post()['lavado'] as $key => $value)
      {
        $pdf->Row(array(
          utf8_decode($this->input->post()['folio'][$key]),
          utf8_decode($value),
          utf8_decode($this->input->post()['proceso'][$key])
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
      $pdf->Output("Reporte de cortes autorizados.pdf", 'I');
    }
  }

  /*
   * reporte de cargas entregadas
   * cargas con salida externa
   */
  public function reporte3()
  {
    $titulo['titulo'] = 'Reporte de cargas entregadas.';
    if ($this->input->get())
    {
      $this->load->model('corte');
      $data['data'] = $this->corte->reporte3($this->input->get());
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/reporte3', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      if ($this->input->post())
      {
        // Se carga la libreria fpdf
        $this->load->library('pdf');
        // Creacion del PDF
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $pdf = new Pdf("Reporte de cargas entregadas");
        // Agregamos una página
        $pdf->SetAutoPageBreak(1, 20);
        // Define el alias para el número de página que se imprimirá en el pie
        $pdf->AliasNbPages();
        $pdf->AddPage();
        /*
         * Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $pdf->SetTitle("Reporte de cargas entregadas");
        // 190 vertical
        $pdf->SetFillColor(59, 131, 189);
        $pdf->ban = true;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetWidths(array(
          38,
          38,
          38,
          38,
          38
        ));
        $pdf->Row(array(
          utf8_decode("Folio del corte"),
          utf8_decode("Carga o lavado"),
          utf8_decode("# de piezas"),
          utf8_decode("Cliente"),
          utf8_decode("Fecha de entrega")
        ));
        $pdf->SetFont('Arial', '', 8);
        $pdf->ban = false;
        foreach ($this->input->post()['lavado'] as $key => $value)
        {
          $pdf->Row(array(
            utf8_decode($this->input->post()['folio'][$key]),
            utf8_decode($value),
            utf8_decode($this->input->post()['piezas'][$key]),
            utf8_decode($this->input->post()['cliente'][$key]),
            utf8_decode($this->input->post()['fecha'][$key])
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
        $pdf->Output("Reporte de cargas entregadas.pdf", 'I');
      }
      else
        redirect('/');
    }
  }

  /*
   * Reporte de cargas en producción
   * -> cargas autorizadas
   * -> cargas con salida interna
   * -> cargas sin salida a almacén
   */
  public function reporte4()
  {
    $titulo['titulo'] = "Reporte de cargas en producción";
    if (! $this->input->post())
    {
      $this->load->model(array(
        'corte',
        'corteAutorizadoDatos'
      ));
      $data = $this->corte->reporte4();
      foreach ($data as $key => $value)
      {
        $proceso = $this->corteAutorizadoDatos->getProcesoActivo($value['folio'], $value['lavado']);
        $data[$key]['proceso'] = (count($proceso) == 0) ? "Ninguno" : $proceso[0]['proceso'];
      }
      $datos['data'] = $data;
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/reporte4', $datos);
      $this->load->view('comunes/foot');
    }
    else
    {
      // Se carga la libreria fpdf
      $this->load->library('pdf');
      // Creacion del PDF
      /*
       * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
       * heredó todos las variables y métodos de fpdf
       */
      $pdf = new Pdf(utf8_decode("Reporte de cargas en producción"));
      // Agregamos una página
      $pdf->SetAutoPageBreak(1, 20);
      // Define el alias para el número de página que se imprimirá en el pie
      $pdf->AliasNbPages();
      $pdf->AddPage();
      /*
       * Se define el titulo, márgenes izquierdo, derecho y
       * el color de relleno predeterminado
       */
      $pdf->SetTitle(utf8_decode("Reporte de cargas en producción"));
      // 190 vertical
      $pdf->SetFillColor(59, 131, 189);
      $pdf->ban = true;
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetWidths(array(
        63.333333333,
        63.333333333,
        63.333333333
      ));
      $pdf->Row(array(
        utf8_decode("Folio del corte"),
        utf8_decode("Carga - lavado"),
        utf8_decode("Proceso abierto")
      ));
      $pdf->SetFont('Arial', '', 8);
      $pdf->ban = false;
      foreach ($this->input->post()['lavado'] as $key => $value)
      {
        $pdf->Row(array(
          utf8_decode($this->input->post()['folio'][$key]),
          utf8_decode($value),
          utf8_decode($this->input->post()['proceso'][$key])
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
      $pdf->Output(utf8_decode("Reporte de cargas en producción.pdf"), 'I');
    }
  }

  /*
   * Reporte de cargas en almacén de salida
   * -> cargas con salida a almacén
   * -> cargas sin salida externa
   */
  public function reporte5()
  {
    $titulo['titulo'] = "Reporte de cargas en el almacén de salida";
    if (! $this->input->post())
    {
      $this->load->model('corte');
      $data['data'] = $this->corte->reporte5();
      $this->load->view('comunes/head', $titulo);
      $this->cargarMenu();
      $this->load->view('gestion/reporte5', $data);
      $this->load->view('comunes/foot');
    }
    else
    {
      // Se carga la libreria fpdf
      $this->load->library('pdf');
      // Creacion del PDF
      /*
       * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
       * heredó todos las variables y métodos de fpdf
       */
      $pdf = new Pdf(utf8_decode("Reporte de cargas en el almacén de salida"));
      // Agregamos una página
      $pdf->SetAutoPageBreak(1, 20);
      // Define el alias para el número de página que se imprimirá en el pie
      $pdf->AliasNbPages();
      $pdf->AddPage();
      /*
       * Se define el titulo, márgenes izquierdo, derecho y
       * el color de relleno predeterminado
       */
      $pdf->SetTitle(utf8_decode("Reporte de cargas en el almacén de salida"));
      // 190 vertical
      $pdf->SetFillColor(59, 131, 189);
      $pdf->ban = true;
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetWidths(array(
        38,
        38,
        38,
        38,
        38
      ));
      $pdf->Row(array(
        utf8_decode("Folio del corte\n\n"),
        utf8_decode("Carga o lavado\n\n"),
        utf8_decode("Cliente\n\n"),
        utf8_decode("# de piezas\n\n"),
        utf8_decode("Fecha de ingreso a almacén")
      ));
      $pdf->SetFont('Arial', '', 8);
      $pdf->ban = false;
      foreach ($this->input->post()['lavado'] as $key => $value)
      {
        $pdf->Row(array(
          utf8_decode($this->input->post()['folio'][$key]),
          utf8_decode($value),
          utf8_decode($this->input->post()['cliente'][$key]),
          utf8_decode($this->input->post()['piezas'][$key]),
          utf8_decode($this->input->post()['fecha'][$key])
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
      $pdf->Output(utf8_decode("Reporte de cargas en el almacén de salida.pdf"), 'I');
    }
  }
}
