<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Pdf.php";
/**
 * Clase para las funciones comunes que se usan para múltiples roles de usuario
 */
class Comunes
{
    public function verProduccion($reporte,$fechaInicial,$fechaFinal,$descuentos)
    {
        // Creacion del PDF
        /*
        * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
        * heredó todos las variables y métodos de fpdf
        */
        $pdf = new Pdf(utf8_decode("Ver producción del usuario ".$_SESSION['username']." del ".$fechaInicial." al ".$fechaFinal));
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
        $pdf->SetFillColor(59,131,189);
        $pdf->ban = true;
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
        $pdf->ban = false;
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
        //Encabezado de tabla descuentos
        $pdf->ban = true;
        $pdf->SetFillColor(59,131,189);
        $pdf->SetWidths(array(63.333333333,63.333333333,63.333333333));
        $pdf->SetFont('Arial','B',8);
        $pdf->Row(array("Fecha",utf8_decode("Razón"),"Cantidad"));
        //LLenar Tabla descuentos
        $pdf->ban = false;
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
