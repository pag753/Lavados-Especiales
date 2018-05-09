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
		$titulo['titulo'] = 'Bienvenido a lavados especiales';
		$this->load->view('head',$titulo);
		$this->load->view('operario/menu');
		$this->load->view('operario/index',$data);
		$this->load->view('foot');
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
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operario/altaConfirmacion',$data);
			$this->load->view('foot');
		}
		else
		{
			$titulo['titulo'] = 'Cerrar proceso';
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operario/alta');
			$this->load->view('foot');
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
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
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
			redirect('/operario/index/-1');
		}
		else
		{
			$data['link'] = base_url().'index.php/operario/datos';
			$this->load->model('Usuarios');
			$data['data'] = $this->Usuarios->getById($_SESSION['usuario_id']);
			$titulo['titulo'] = 'Cambiar datos personales';
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('cambiarDatos',$data);
			$this->load->view('foot');
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
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operarios/insertar',$data);
			$this->load->view('foot');
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
			$this->load->view('head',$titulo);
			$this->load->view('operario/menu');
			$this->load->view('operarios/verProduccion',$data);
			$this->load->view('foot');
		}
		else 
		{
			$this->load->library('pdf');
			// Creacion del PDF
			/*
			* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
			* heredó todos las variables y métodos de fpdf
			*/
			$this->load->model('ProduccionProcesoSeco');
			$reporte = $this->ProduccionProcesoSeco->verProduccion(
				$_SESSION['usuario_id'],
				$this->input->post()['fechaInicio'],
				$this->input->post()['fechaFinal']
			);
			$pdf = new Pdf(utf8_decode("Ver producción del usuario ".$_SESSION['username']." del ".$this->input->post()['fechaInicio']." al ".$this->input->post()['fechaFinal']));
			// Agregamos una página
			$pdf->SetAutoPageBreak(1,20);
			// Define el alias para el número de página que se imprimirá en el pie
			$pdf->AliasNbPages();
			$pdf->AddPage();
			/* Se define el titulo, márgenes izquierdo, derecho y
			* el color de relleno predeterminado
			*/
			$pdf->SetTitle(utf8_decode("Ver producción del usuario ".$_SESSION['username']));
			//Título de tabla de producción
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(0,10,utf8_decode('Información de Producción'),0,1,'C');
			//Tabla de producción
			$pdf->SetWidths(array(27.142857143,27.142857143,27.142857143,27.142857143,27.142857143,27.142857143,27.142857143));
			//Encabezado de tabla
			$pdf->SetFont('Arial','B',8);
			$pdf->Row(array(
				utf8_decode("Fecha"),
				utf8_decode("Folio"),
				utf8_decode("Carga"),
				utf8_decode("Proceso"),
				utf8_decode("Piezas"),
				utf8_decode("Precio"),
				utf8_decode("Costo")
			));
			//Llenar tabla de producción
			$pdf->SetFont('Arial','',8);
			$produccion=0;
			foreach ($reporte as $key => $value)
			{
				$pdf->Row(array(
					utf8_decode($value['fecha']),
					utf8_decode($value['folio']),
					utf8_decode($value['carga']),
					utf8_decode($value['proceso']),
					utf8_decode($value['piezas']),
					utf8_decode("$".$value['precio']),
					utf8_decode("$".$value['costo'])
				));
				$produccion += $value['costo'];
			}
			//Pie de total de producción
			$pdf->SetX(145.714285715);
			$pdf->SetWidths(array(27.142857143,27.142857143));
			$pdf->Row(array(utf8_decode('Total de producción'),"$".$produccion));
			//Título de tabla descuentos
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(0,10,utf8_decode('Descuentos'),0,1,'C');
			//Consulta a tabla descuentos
			$pdf->SetFont('Arial','B',8);
			$this->load->model("Descuentos");
			$descuentos=$this->Descuentos->consulta1(
				$_SESSION['usuario_id'],
				$this->input->post()['fechaInicio'],
				$this->input->post()['fechaFinal']
			);
			//Encabezado de tabla descuentos
			$pdf->SetWidths(array(63.333333333,63.333333333,63.333333333));
			$pdf->SetFont('Arial','B',8);
			$pdf->Row(array("Fecha",utf8_decode("Razón"),"Cantidad"));
			//LLenar Tabla descuentos
			$pdf->SetFont('Arial','',8);
			$desc = 0;
			foreach ($descuentos as $key => $value)
			{
				$pdf->Row(array(
					$value['fecha'],
					$value['razon'],
					"$".$value['cantidad']
				));
				$desc += $value['cantidad'];
			}
			//Pie de total de descuentos
			$pdf->SetX(73.333333333);
			$pdf->SetWidths(array(63.333333333,63.333333333));
			$pdf->Row(array(utf8_decode('Total de descuentos'),"$".$desc));
			//Título de Totales
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(0,10,utf8_decode('Total'),0,1,'C');
			//Totales
			$pdf->SetFont('Arial','B',8);
			$pdf->SetX(73.333333333);
			$pdf->SetWidths(array(63.333333333,63.333333333));
			$pdf->Row(array(utf8_decode('+ Total de producción'),"$".$produccion));
			$pdf->SetX(73.333333333);
			$pdf->Row(array(utf8_decode('- Total de descuentos'),"$".$desc));
			$pdf->SetX(73.333333333);
			$pdf->Row(array(utf8_decode('Total hasta el momento'),"$".($produccion-$desc)));
			/*
			* Se manda el pdf al navegador
			*
			* $this->pdf->Output(nombredelarchivo, destino);
			*
			* I = Muestra el pdf en el navegador
			* D = Envia el pdf para descarga
			*
			*/
			$pdf->Output("Reporte.pdf", 'I');
		}
	}
}
