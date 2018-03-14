<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gestion extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
    	$idusuario=$_SESSION['id'];
		if($idusuario==2 || $idusuario==5)
		{
		}
		else
			redirect('/');
	}

	public function index($datos=null)
	{
		if($datos!=null)
		{
			if($datos==-1)
			{
				$data['texto1']="Los datos";
				$data['texto2']="Se han actualizado con éxito";
			}
			else
			{
				$data['texto1']="El corte con folio ".$datos;
				$data['texto2']="Se ha registrado con éxito";
			}
		}
		else
		{
			$data['texto1']="Bienvenido(a) usuario de gestión";
			$data['texto2']=$_SESSION['username'];
		}
		/*
		$this->load->view('encabezado_principal');
		$this->load->view('gestion_base');
 		$this->load->view('gestion_principal',$data);
		$this->load->view('footer');*/

		$this->load->view('head');
		$this->load->view('gestion/menu');
		$this->load->view('gestion/index',$data);
		$this->load->view('foot');

	}

	public function cerrar_sesion()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function alta()
	{
		if($this->input->post())
		{
			$datos['datos_corte']=$this->input->post();
			$mi_imagen = 'mi_imagen';
      			$config['upload_path'] = "img/fotos";
			$config['file_name'] = $datos['datos_corte']['folio'];
			$config['allowed_types'] = "gif|jpg|jpeg|png";
			$config['max_size'] = "500000";
			$config['max_width'] = "20000";
			$config['max_height'] = "20000";

			$this->load->library('upload', $config);

      			if (!$this->upload->do_upload($mi_imagen))
			{
				// ocurrio un error
				$data['uploadError'] = $this->upload->display_errors();
				//echo $this->upload->display_errors();
			}
			$data['uploadSuccess'] = $this->upload->data();

			$this->load->model('corte');
			$this->corte->agregar($datos['datos_corte']);
			redirect('/gestion/index/'.$datos['datos_corte']['folio']);
			}

			else
			{
			$this->load->model(array('marca','maquilero','cliente','tipo_pantalon','corte'));
			$c=$this->corte->get();
			if(count($c)==0)
				$id_corte=1;

			else
			{
				if(count(count($c)-1)==0)
					$id_corte=0;
				else
					$id_corte=$this->corte->get()[count($this->corte->get())-1]['folio']+1;
			}
			$datos['marcas']=$this->marca->get();
			$datos['maquileros']=$this->maquilero->get();
			$datos['clientes']=$this->cliente->get();
			$datos['tipos']=$this->tipo_pantalon->get();
			$datos['corte']=$id_corte;

			$this->load->view('head');
			$this->load->view('gestion/menu');
			$this->load->view('gestion/alta',$datos);
			$this->load->view('foot');

			/*
			$this->load->view('encabezado_principal');
			$this->load->view('gestion_base');
			$this->load->view('gestion_alta_corte',$datos);
			$this->load->view('footer');*/
		}
	}

	public function salidaInterna()
	{
		if($this->input->post())
		{
			$this->load->model('corteAutorizadoDatos');
			$query=$this->corteAutorizadoDatos->joinLavado($this->input->post()['folio']);
			$data['corte_folio']=$this->input->post()['folio'];
			$f=explode("/",$this->input->post()['fecha']);
			$data['fecha']=$f[2]."/".$f[1]."/".$f[0]."/";
			$data['muestras']=$this->input->post()['muestras'];
			$this->load->model('salidaInterna1');
			$this->salidaInterna1->agregar($data);
			$data=null;
			$this->load->model('salidaInterna1Datos');
			$data['corte_folio']=$this->input->post()['folio'];
			for ($i=0; $i <$this->input->post()['cargas'] ; $i++)
			{
				$data['id_carga']=$query[$i]['id_carga'];
				$data['piezas']=$this->input->post()['piezas_parcial'.$i];
				$this->salidaInterna1Datos->agregar($data);
				$this->corteAutorizadoDatos->actualiza($this->input->post()['primero'][$i],$this->input->post()['folio'],$i+1,$this->input->post()['piezas_parcial'.$i],1);
			}
			redirect('/gestion/index/'.$this->input->post()['folio']);
		}
		else
		{
			$datos['fecha']=date("d/m/Y");

			$this->load->view('head');
			$this->load->view('gestion/menu');
			$this->load->view('gestion/salidaInterna',$datos);
			$this->load->view('foot');

			/*
			$this->load->view('encabezado_principal');
			$this->load->view('gestion_base');
			$this->load->view('gestion_salidaInterna',$datos);
			$this->load->view('footer');*/
		}
	}

	public function reportes()
	{
		$this->load->model( array('Cliente', 'Marca', 'Maquilero', 'Tipo_pantalon' ));
		$datos['clientes']=$this->Cliente->get();
		$datos['marcas']=$this->Marca->get();
		$datos['maquileros']=$this->Maquilero->get();
		$datos['tipos']=$this->Tipo_pantalon->get();

		$this->load->view('head');
		$this->load->view('gestion/menu');
		$this->load->view('gestion/reportes',$datos);
		$this->load->view('foot');
		/*
		$this->load->view('encabezado_principal');
		if($_SESSION['id']!=5)
			$this->load->view('gestion_base');
		else
			$this->load->view('rootBase');
		$this->load->view('gestion_reportes',$datos);
		$this->load->view('footer');*/
	}

	public function generaReporte()
	{
		if(!$this->input->post())
			redirect('/');
		switch ($this->input->post()['reporte'])
		{
			case 1://reporte de cortes en almacen -> cortes no autorizados
				//campos: todos los de de corte
				$this->reporte1();
				break;
			case 2://reporte de cortes autorizados -> cortes autorizados no en proceso sin salida externa
				//campos: todos los de corte, datos de autorización y si hay de salida interna
				$this->reporte2();
				break;
			case 3://reporte de cortes entregados -> cortes que ya se entregaron
				//campos: TODOS
				$this->reporte3();
				break;
			case 4://reporte de cortes en proceso -> cortes que están en proceso sin salida externa
				//campos: todos los de corte, datos de autorización y salida interna
				$this->reporte4();
				break;
		}
		//print_r($this->input->post());
	}

	public function reporte1()
	{
		if(!$this->input->post())
			redirect("/");
		// Se carga la libreria fpdf
		if(isset($this->input->post()['check']))
			$check=TRUE;
		else
			$check=FALSE;
    		$this->load->library('pdf');
		// Creacion del PDF
		/*
     		* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     		* heredó todos las variables y métodos de fpdf
     		*/
		//$pdf=new PDF('L','mm','Letter');
		//$pdf->Open();
		$this->load->model('Corte');
		$cortes=$this->Corte->reporte1($this->input->post());
		//print_r($cortes);
		//print_r($this->input->post());

		$pdf = new Pdf("REPORTE DE CORTES EN ALMACEN");
    		// Agregamos una página
		$pdf->SetAutoPageBreak(1,20);
	    	// Define el alias para el número de página que se imprimirá en el pie
    		$pdf->AliasNbPages();
		//$pdf->Open();
		$pdf->AddPage();
		/* Se define el titulo, márgenes izquierdo, derecho y
     		* el color de relleno predeterminado
     		*/
    		$pdf->SetTitle("Reporte");
		$this->load->model('corte');
		$cortes=$this->corte->reporte1($this->input->post());
		//190 vertical
		if ($check)
		{
			$pdf->SetWidths(array(52.44,10,10,21.11,21.11,21.11,21.11,11,21.11));
			$pdf->Row(array(utf8_decode("Imágen"),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha")));
			$extensiones = array("jpg","jpeg","png");

			foreach ($cortes as $key => $value)
			{
				if($key>0 && $key%6==0)
				{
					$pdf->SetFont('Arial','B',8);
					$pdf->AddPage();
					$pdf->Row(array(utf8_decode("Imágen"),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha")));
				}

				$pdf->SetFont('Arial','',8);

				foreach ($extensiones as $key2 => $extension)
				{
					$file="/var/www/html/lavanderia/img/fotos/".$value['folio'].".".$extension;
					if(is_file($file))
					{
						$pdf->Image(base_url()."img/fotos/".$value['folio'].".".$extension,$pdf->GetX(),$pdf->GetY(),53,30);
						break;
					}
				}
				$peso="\n\n\n\n\n\n";
				$pdf->Row(array($peso,utf8_decode($value['folio']),utf8_decode($value['corte']),utf8_decode($value['marca']),utf8_decode($value['maquilero']),utf8_decode($value['cliente']),utf8_decode($value['tipo']),utf8_decode($value['piezas']),utf8_decode($value['fecha'])));
			}
		}
		else
		{
			$pdf->SetWidths(array(23.75,23.75,23.75,23.75,23.75,23.75,23.75,23.75));
			$pdf->Row(array(utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),
			utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha")));
			$pdf->SetFont('Arial','',8);
			foreach ($cortes as $key => $value)
			{
				$pdf->Row(array(utf8_decode($value['folio']),utf8_decode($value['corte']),utf8_decode($value['marca']),utf8_decode($value['maquilero']),
				utf8_decode($value['cliente']),utf8_decode($value['tipo']),utf8_decode($value['piezas']),utf8_decode($value['fecha'])));
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
		$pdf->Output("Reporte.pdf", 'I');
	}

	/*
	public function reporte2()
	{
		//reporte de cortes autorizados -> cortes autorizados no en proceso sin salida externa
		//campos: todos los de corte, datos de autorización y si hay de salida interna
		if(!$this->input->post())
			redirect("/");
		// Se carga la libreria fpdf
		if(isset($this->input->post()['check']))
			$check=TRUE;
		else
			$check=FALSE;
    		$this->load->library('pdf');
		// Creacion del PDF
		/*
     		* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     		* heredó todos las variables y métodos de fpdf
     		*/
		//$pdf=new PDF('L','mm','Letter');
		//$pdf->Open();
		/*
		$this->load->model('Corte');
		$cortes=$this->Corte->reporte1($this->input->post());
		//print_r($cortes);
		//print_r($this->input->post());

		$pdf = new Pdf("REPORTE DE CORTES AUTORIZADOS",'L');
    		// Agregamos una página
		$pdf->SetAutoPageBreak(1,20);
	    	// Define el alias para el número de página que se imprimirá en el pie
    	$pdf->AliasNbPages();
		//$pdf->Open();
		$pdf->AddPage();
		/* Se define el titulo, márgenes izquierdo, derecho y
     		* el color de relleno predeterminado
     	*/
/*
		$pdf->SetTitle("Reporte");
		$this->load->model('corte');
		$cortes=$this->corte->reporte2($this->input->post());

		//190 vertical
		if ($check)
		{
			$valor=42.769230769
			$arregloPrincipal=array($valor,8.769230769,10.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769);
			$pdf->SetWidths($arregloPrincipal);
			$pdf->Row(array(utf8_decode('Imágen'),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Lavado")));
			$extensiones = array("jpg","jpeg","png");

			foreach ($cortes as $key => $value)
			{
				$cargas=$value['cargas'];
				$folio=$value['folio'];
				$corte=$value['corte'];
				$marca=$value['marca'];
				$maquilero=$value['maquilero'];
				$cliente=$value['cliente'];
				$tipo=$value['tipo'];
				$piezas=$value['piezas'];
				$fecha=$value['fecha'];
				$fechaAutorizado=$value['fechaAutorizado'];

				if($pdf->GetY()+($cargas*15)>170)
				{
					$pdf->AddPage();
					$pdf->SetFont('Arial','B',9);
					$pdf->SetWidths($arregloPrincipal);
					$pdf->Row(array(utf8_decode('Imágen'),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Lavado")));
				}

				$pdf->SetWidths(array(8.769230769,10.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769,20.769230769));

				$pdf->SetFont('Arial','',9);

				foreach ($extensiones as $key2 => $extension)
				{
					$file="/var/www/html/lavanderia/img/fotos/".$folio.".".$extension;
					if(is_file($file))
					{
						$pdf->Image(base_url()."img/fotos/".$folio.".".$extension,$pdf->GetX(),$pdf->GetY(),49.75,30);
						break;
					}
				}
				$this->load->model('corteAutorizadoDatos');
				for ($carga=1; $carga <=$cargas ; $carga++)
				{
					$corteAutorizado=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros5($folio,$carga);
					$proceso='';
					foreach ($corteAutorizado as $key2 => $value2)
					{
						$lavado=$value2['lavado'];
						$proceso=$proceso.$value2['proceso'].", ";
					}

					$pdf->SetX($pdf->GetX()+$valor);
					$pdf->Row(array(utf8_decode($folio),utf8_decode($corte),utf8_decode($marca),utf8_decode($maquilero),utf8_decode($cliente),utf8_decode($tipo),utf8_decode($piezas),utf8_decode($fecha),utf8_decode($cargas),utf8_decode($fechaAutorizado),utf8_decode($lavado),utf8_decode($proceso)));
				}
				$pdf->SetY($pdf->GetY()+5);
			}
		}
		else
		{
			$pdf->SetFont('Arial','B',9);
			$pdf->SetWidths(array(22.5,22.5,22.5,22.5,22.5,22.5,22.5,22.5,22.5,22.5,22.5,22.5));
			$pdf->Row(array(utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Lavado"),utf8_decode("Procesos")));

			$pdf->SetFont('Arial','',9);
			foreach ($cortes as $key => $value)
			{
				$cargas=$value['cargas'];
				$folio=$value['folio'];
				$corte=$value['corte'];
				$marca=$value['marca'];
				$maquilero=$value['maquilero'];
				$cliente=$value['cliente'];
				$tipo=$value['tipo'];
				$piezas=$value['piezas'];
				$fecha=$value['fecha'];
				$fechaAutorizado=$value['fechaAutorizado'];
				$this->load->model('corteAutorizadoDatos');
				$lavado='';
				$proceso='';
				for ($carga=1; $carga <=$cargas ; $carga++)
				{
					$corteAutorizado=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros5($folio,$carga);
					$proceso='';
					foreach ($corteAutorizado as $key2 => $value2)
					{
						$lavado=$value2['lavado'];
						$proceso=$proceso.$value2['proceso'].", ";
					}

					$pdf->Row(array(utf8_decode($folio),utf8_decode($corte),utf8_decode($marca),utf8_decode($maquilero),utf8_decode($cliente),utf8_decode($tipo),utf8_decode($piezas),utf8_decode($fecha),utf8_decode($cargas),utf8_decode($fechaAutorizado),utf8_decode($lavado),utf8_decode($proceso)));
				}
				$pdf->SetY($pdf->GetY()+5);
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
		/*
		$pdf->Output("Reporte.pdf", 'I');
	}*/

	public function reporte3()
	{
		//reporte de cortes en proceso -> cortes que están en proceso sin salida externa
		//campos: todos los de corte, datos de autorización y salida interna
		if(!$this->input->post())
			redirect("/");
		// Se carga la libreria fpdf
		if(isset($this->input->post()['check']))
			$check=TRUE;
		else
			$check=FALSE;
    		$this->load->library('pdf');
		// Creacion del PDF
		/*
     		* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     		* heredó todos las variables y métodos de fpdf
     		*/
		//$pdf=new PDF('L','mm','Letter');
		//$pdf->Open();
		$this->load->model('Corte');
		$cortes=$this->Corte->reporte1($this->input->post());
		//print_r($cortes);
		//print_r($this->input->post());

		$pdf = new Pdf("REPORTE DE CORTES EN PROCESO",'L');
		// Agregamos una página
		$pdf->SetAutoPageBreak(1,20);
		// Define el alias para el número de página que se imprimirá en el pie
		$pdf->AliasNbPages();
		//$pdf->Open();
		$pdf->AddPage();
		/* Se define el titulo, márgenes izquierdo, derecho y
		* el color de relleno predeterminado
		*/

		$pdf->SetTitle("Reporte");
		$this->load->model('corte');
		$cortes=$this->corte->reporte3($this->input->post());

		//190 vertical
		if ($check)
		{
			$pdf->SetWidths(array(38,9,9.5,16.875,16.875,16.875,16.875,10.875,16.875,12.875,16.875,16.875,16.875,15,16.875,16.875,16.875));
			$pdf->Row(array(utf8_decode('Imágen'),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Fecha entrega"),utf8_decode("Muestras"),utf8_decode("Lavado"),utf8_decode("Procesos"),utf8_decode("Piezas de carga")));
			$extensiones = array("jpg","jpeg","png");

			foreach ($cortes as $key => $value)
			{
				$cargas=$value['cargas'];
				$folio=$value['folio'];
				$corte=$value['corte'];
				$marca=$value['marca'];
				$maquilero=$value['maquilero'];
				$cliente=$value['cliente'];
				$tipo=$value['tipo'];
				$piezas=$value['piezas'];
				$fecha=$value['fecha'];
				$fechaAutorizado=$value['fechaAutorizado'];
				$fechaSalidaInterna=$value['fechaSalidaInterna'];
				$muestras=$value['muestras'];
				$fechaSalida=$value['fechaSalida'];

				if($pdf->GetY()+($cargas*15)>170)
				{
					$pdf->AddPage();
					$pdf->SetFont('Arial','B',8);
					$pdf->SetWidths(array(38,9,9.5,16.875,16.875,16.875,16.875,10.875,16.875,12.875,16.875,16.875,16.875,15,16.875,16.875,16.875));
					$pdf->Row(array(utf8_decode('Imágen'),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Fecha entrega"),utf8_decode("Muestras"),utf8_decode("Lavado"),utf8_decode("Procesos"),utf8_decode("Piezas de carga")));
				}

				$pdf->SetWidths(array(9,9.5,16.875,16.875,16.875,16.875,10.875,16.875,12.875,16.875,16.875,16.875,15,16.875,16.875,16.875));

				$pdf->SetFont('Arial','',8);

				foreach ($extensiones as $key2 => $extension)
				{
					$file="/var/www/html/lavanderia/img/fotos/".$folio.".".$extension;
					if(is_file($file))
					{
						$pdf->Image(base_url()."img/fotos/".$folio.".".$extension,$pdf->GetX(),$pdf->GetY(),38,21.4);
						break;
					}
				}

				$this->load->model('salidaInterna1Datos');
				$salidaInterna=$this->salidaInterna1Datos->getByFolio($folio);
				$this->load->model('corteAutorizadoDatos');
				$lavado='';
				$proceso='';
				for ($carga=1; $carga <=$cargas ; $carga++)
				{
					$corteAutorizado=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros5($folio,$carga);
					$proceso='';
					foreach ($corteAutorizado as $key2 => $value2)
					{
						$lavado=$value2['lavado'];
						$proceso=$proceso.$value2['proceso'].", ";
					}
					$piezasCarga=$salidaInterna[$carga-1]['piezas'];

					$pdf->SetX($pdf->GetX()+38);
					$pdf->Row(array(utf8_decode($folio),utf8_decode($corte),utf8_decode($marca),utf8_decode($maquilero),utf8_decode($cliente),utf8_decode($tipo),utf8_decode($piezas),utf8_decode($fecha),utf8_decode($cargas),utf8_decode($fechaAutorizado),utf8_decode($fechaSalidaInterna),utf8_decode($fechaSalida),utf8_decode($muestras),utf8_decode($lavado),utf8_decode($proceso),utf8_decode($piezasCarga)));
				}
				$pdf->SetY($pdf->GetY()+5);
			}
		}
		else
		{
			$pdf->SetFont('Arial','B',8);
			$pdf->SetWidths(array(16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875,16.875));
			$pdf->Row(array(utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Fecha entrega"),utf8_decode("Muestras"),utf8_decode("Lavado"),utf8_decode("Procesos"),utf8_decode("Piezas de carga")));

			$pdf->SetFont('Arial','',8);
			foreach ($cortes as $key => $value)
			{
				$cargas=$value['cargas'];
				$folio=$value['folio'];
				$corte=$value['corte'];
				$marca=$value['marca'];
				$maquilero=$value['maquilero'];
				$cliente=$value['cliente'];
				$tipo=$value['tipo'];
				$piezas=$value['piezas'];
				$fecha=$value['fecha'];
				$fechaAutorizado=$value['fechaAutorizado'];
				$fechaSalidaInterna=$value['fechaSalidaInterna'];
				$muestras=$value['muestras'];
				$fechaSalida=$value['fechaSalida'];

				$this->load->model('salidaInterna1Datos');
				$salidaInterna=$this->salidaInterna1Datos->getByFolio($folio);
				$this->load->model('corteAutorizadoDatos');
				for ($carga=1; $carga <=$cargas ; $carga++)
				{
					$corteAutorizado=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros5($folio,$carga);
					$proceso='';
					foreach ($corteAutorizado as $key2 => $value2)
					{
						$lavado=$value2['lavado'];
						$proceso=$proceso.$value2['proceso'].", ";
					}
					$piezasCarga=$salidaInterna[$carga-1]['piezas'];

					$pdf->Row(array(utf8_decode($folio),utf8_decode($corte),utf8_decode($marca),utf8_decode($maquilero),utf8_decode($cliente),utf8_decode($tipo),utf8_decode($piezas),utf8_decode($fecha),utf8_decode($cargas),utf8_decode($fechaAutorizado),utf8_decode($fechaSalidaInterna),utf8_decode($fechaSalida),utf8_decode($muestras),utf8_decode($lavado),utf8_decode($proceso),utf8_decode($piezasCarga)));
				}
				$pdf->SetY($pdf->GetY()+5);
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
		$pdf->Output("Reporte.pdf", 'I');
	}

	public function reporte4()
	{
		//reporte de cortes en proceso -> cortes que están en proceso sin salida externa
		//campos: todos los de corte, datos de autorización y salida interna
		if(!$this->input->post())
			redirect("/");
		// Se carga la libreria fpdf
		if(isset($this->input->post()['check']))
			$check=TRUE;
		else
			$check=FALSE;
    		$this->load->library('pdf');
		// Creacion del PDF
		/*
     		* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     		* heredó todos las variables y métodos de fpdf
     		*/
		//$pdf=new PDF('L','mm','Letter');
		//$pdf->Open();
		$this->load->model('Corte');
		$cortes=$this->Corte->reporte1($this->input->post());
		//print_r($cortes);
		//print_r($this->input->post());

		$pdf = new Pdf("REPORTE DE CORTES EN PROCESO",'L');
		// Agregamos una página
		$pdf->SetAutoPageBreak(1,20);
		// Define el alias para el número de página que se imprimirá en el pie
		$pdf->AliasNbPages();
		//$pdf->Open();
		$pdf->AddPage();
		/* Se define el titulo, márgenes izquierdo, derecho y
		* el color de relleno predeterminado
		*/

		$pdf->SetTitle("Reporte");
		$this->load->model('corte');
		$cortes=$this->corte->reporte4($this->input->post());

		//190 vertical
		if ($check)
		{
			$pdf->SetWidths(array(49.75,9,10.875,16.875,16.875,16.875,16.875,10.875,16.875,12.875,16.875,16.875,16.875,16.875,16.875,16.875));
			$pdf->Row(array(utf8_decode('Imágen'),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Muestras"),utf8_decode("Lavado"),utf8_decode("Procesos"),utf8_decode("Piezas de carga")));
			$extensiones = array("jpg","jpeg","png");

			foreach ($cortes as $key => $value)
			{
				$cargas=$value['cargas'];
				$folio=$value['folio'];
				$corte=$value['corte'];
				$marca=$value['marca'];
				$maquilero=$value['maquilero'];
				$cliente=$value['cliente'];
				$tipo=$value['tipo'];
				$piezas=$value['piezas'];
				$fecha=$value['fecha'];
				$fechaAutorizado=$value['fechaAutorizado'];

				if($pdf->GetY()+($cargas*15)>170)
				{
					$pdf->AddPage();
					$pdf->SetFont('Arial','B',8);
					$pdf->SetWidths(array(49.75,9,10.875,16.875,16.875,16.875,16.875,10.875,16.875,12.875,16.875,16.875,16.875,16.875,16.875,16.875));
					$pdf->Row(array(utf8_decode('Imágen'),utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Muestras"),utf8_decode("Lavado"),utf8_decode("Procesos"),utf8_decode("Piezas de carga")));
				}

				$pdf->SetWidths(array(9,10.875,16.875,16.875,16.875,16.875,10.875,16.875,12.875,16.875,16.875,16.875,16.875,16.875,16.875));

				$pdf->SetFont('Arial','',8);

				foreach ($extensiones as $key2 => $extension)
				{
					$file="/var/www/html/lavanderia/img/fotos/".$folio.".".$extension;
					if(is_file($file))
					{
						$pdf->Image(base_url()."img/fotos/".$folio.".".$extension,$pdf->GetX(),$pdf->GetY(),49.75,30);
						break;
					}
				}

				$fechaSalidaInterna=$value['fechaSalidaInterna'];
				$muestras=$value['muestras'];


				$this->load->model('salidaInterna1Datos');
				$salidaInterna=$this->salidaInterna1Datos->getByFolio($folio);
				$this->load->model('corteAutorizadoDatos');
				$lavado='';
				$proceso='';
				for ($carga=1; $carga <=$cargas ; $carga++)
				{
					$corteAutorizado=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros5($folio,$carga);
					$proceso='';
					foreach ($corteAutorizado as $key2 => $value2)
					{
						$lavado=$value2['lavado'];
						$proceso=$proceso.$value2['proceso'].", ";
					}
					$piezasCarga=$salidaInterna[$carga-1]['piezas'];

					$pdf->SetX($pdf->GetX()+49.75);
					$pdf->Row(array(utf8_decode($folio),utf8_decode($corte),utf8_decode($marca),utf8_decode($maquilero),utf8_decode($cliente),utf8_decode($tipo),utf8_decode($piezas),utf8_decode($fecha),utf8_decode($cargas),utf8_decode($fechaAutorizado),utf8_decode($fechaSalidaInterna),utf8_decode($muestras),utf8_decode($lavado),utf8_decode($proceso),utf8_decode($piezasCarga)));
				}
				$pdf->SetY($pdf->GetY()+5);
			}
		}
		else
		{
			$pdf->SetFont('Arial','B',8);
			$pdf->SetWidths(array(18,18,18,18,18,18,18,18,18,18,18,18,18,18,18));
			$pdf->Row(array(utf8_decode("Folio"),utf8_decode("Corte"),utf8_decode("Marca"),utf8_decode("Maquilero"),utf8_decode("Cliente"),utf8_decode("Tipo"),utf8_decode("Piezas"),utf8_decode("Fecha entrada"),utf8_decode("Cargas"),utf8_decode("Fecha autorización"),utf8_decode("Fecha salida interna"),utf8_decode("Muestras"),utf8_decode("Lavado"),utf8_decode("Procesos"),utf8_decode("Piezas de carga")));

			$pdf->SetFont('Arial','',8);
			foreach ($cortes as $key => $value)
			{
				$cargas=$value['cargas'];
				$folio=$value['folio'];
				$corte=$value['corte'];
				$marca=$value['marca'];
				$maquilero=$value['maquilero'];
				$cliente=$value['cliente'];
				$tipo=$value['tipo'];
				$piezas=$value['piezas'];
				$fecha=$value['fecha'];
				$fechaAutorizado=$value['fechaAutorizado'];

				$fechaSalidaInterna=$value['fechaSalidaInterna'];
				$muestras=$value['muestras'];

				$this->load->model('salidaInterna1Datos');
				$salidaInterna=$this->salidaInterna1Datos->getByFolio($folio);
				$this->load->model('corteAutorizadoDatos');
				$lavado='';
				$proceso='';
				for ($carga=1; $carga <=$cargas ; $carga++)
				{
					$corteAutorizado=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros5($folio,$carga);
					$proceso='';
					foreach ($corteAutorizado as $key2 => $value2)
					{
						$lavado=$value2['lavado'];
						$proceso=$proceso.$value2['proceso'].", ";
					}
					$piezasCarga=$salidaInterna[$carga-1]['piezas'];

					$pdf->Row(array(utf8_decode($folio),utf8_decode($corte),utf8_decode($marca),utf8_decode($maquilero),utf8_decode($cliente),utf8_decode($tipo),utf8_decode($piezas),utf8_decode($fecha),utf8_decode($cargas),utf8_decode($fechaAutorizado),utf8_decode($fechaSalidaInterna),utf8_decode($muestras),utf8_decode($lavado),utf8_decode($proceso),utf8_decode($piezasCarga)));
				}
				$pdf->SetY($pdf->GetY()+5);
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
		$pdf->Output("Reporte.pdf", 'I');
	}

	public function cambiarPass()
	{
		$this->load->view('head');
		$this->load->view('gestion/menu');
		$this->load->view('gestion/cambiarPass');
		$this->load->view('foot');
		/*
		$this->load->view('encabezado_principal');
		$this->load->view('gestion_base');
		$this->load->view('generalCambiarContraseña.php');
		$this->load->view('footer');*/
	}

	private function errorSalidaInterna1($texto2,$autorizado)
	{
		$datos['autorizado']=$autorizado;
		$datos['texto1']="Salida Interna";
		$datos['texto2']=$texto2;
		$datos['folio']=$this->input->post()['folio'];
		$datos['piezas']=$this->input->post()['piezas'];
		$datos['fecha']=$this->input->post()['fecha'];
		$datos['muestras']=$this->input->post()['muestras'];
		$datos['piezas_parcial']=$this->input->post()['piezas_parcial'];
		$this->cargaSalidaInterna1($datos);
	}

	private function cargaSalidaInterna1Validacion($datos=null)
	{
		if(isset($this->input->post()['folio']))
		{
			$datos['folio']=$this->input->post()['folio'];
			$this->load->view('head');
			$this->load->view('gestion/menu');
			$this->load->view('gestion/cargaSalidaInterna1Validacion',$datos);
			$this->load->view('foot');
		}
		/*
		$this->load->view('encabezado_principal');
		$this->load->view('gestion_base');
		$this->load->view('gestion_salidaInterna1Validacion',$datos);
		$this->load->view('footer');*/
	}
}
?>
