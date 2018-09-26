<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH . "third_party/fpdf/fpdf.php";

// Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Pdf extends FPDF
{

  var $widths;

  var $aligns;

  var $cadena;

  var $o;

  var $ban;

  public function __construct($n = "", $o = 'P')
  {
    $this->cadena = $n;
    $this->o = $o;
    parent::__construct($o);
    $ban = false;
  }

  // El encabezado del PDF
  public function Header()
  {
    if ($this->o == 'P') {
      $this->Image(APPPATH . 'libraries/logo.jpg', 10, 8, 22);
      $this->SetFont('Arial', 'B', 13);
      $this->Cell(30);
      $this->Cell(120, 10, 'LAVADOS ESPECIALES', 0, 0, 'C');
      $this->Ln('5');
      $this->SetFont('Arial', 'B', 8);
      $this->Cell(30);
      $this->Cell(120, 10, $this->cadena . "    " . date("d/m/Y"), 0, 0, 'C');
      $this->Ln(20);
    }
    else
    {
      $this->Image(APPPATH . 'libraries/logo.jpg', 10, 8, 22);
      $this->SetFont('Arial', 'B', 13);
      $this->Cell(30);
      $this->Cell('c', 10, 'LAVADOS ESPECIALES', 0, 0, 'C');
      $this->Ln('5');
      $this->SetFont('Arial', 'B', 8);
      $this->Cell(30);
      $this->Cell('c', 10, $this->cadena . "    " . date("d/m/Y"), 0, 0, 'C');
      $this->Ln(20);
    }
  }

  // El pie del pdf
  public function Footer()
  {
    $this->SetY(- 15);
    $this->SetFont('Arial', 'I', 8);
    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
  }

  // Funciones que permiten en manejo de alineaciones para generación de tablas en el documento.
  function SetWidths($w)
  {
    // Establecer el tamaño de columnas para el arreglo.
    $this->widths = $w;
  }

  function SetAligns($a)
  {
    // Establecer las alineaciones de las columnas para el arreglo.
    $this->aligns = $a;
  }

  function Row($data)
  {
    // Calcular la altura del renglón dinámicamente.
    $nb = 0;
    for ($i = 0; $i < count($data); $i ++) $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    $h = 5 * $nb;
    // Instrucción y Procedimiento que realizan un salto de página en caso de ser necesario.
    $this->CheckPageBreak($h);
    // Dibujar las celdas del renglón.
    for ($i = 0; $i < count($data); $i ++)
    {
      $w = $this->widths[$i];
      $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
      // Guarda la posición actual con las cordenadas X y Y.
      $x = $this->GetX();
      $y = $this->GetY();
      // Despliega el Texto.
      $this->MultiCell($w, 5, $data[$i], 0, $a, $this->ban);
      // Dibuja el borde.
      $this->Rect($x, $y, $w, $h);
      // Establece la posición a la derecha de la celda.
      $this->SetXY($x + $w, $y);
    }
    // Ir a la siguiente línea.
    $this->Ln($h);
  }

  function CheckPageBreak($h)
  {
    // Si la altura de la siguiente línea h fuera cortada, se agregará una nueva página inmediatamente para evitarlo.
    if ($this->GetY() + $h > $this->PageBreakTrigger)
    $this->AddPage($this->CurOrientation);
  }

  function NbLines($w, $txt)
  {
    // Procesa el número de líneas a la función MultiCell como el ancho que esta deberá tomar.
    $cw = &$this->CurrentFont['cw'];
    if ($w == 0)
    $w = $this->w - $this->rMargin - $this->x;
    $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
    $s = str_replace("\r", '', $txt);
    $nb = strlen($s);
    if ($nb > 0 and $s[$nb - 1] == "\n")
    $nb --;
    $sep = - 1;
    $i = 0;
    $j = 0;
    $l = 0;
    $nl = 1;
    while ($i < $nb) {
      $c = $s[$i];
      if ($c == "\n") {
        $i ++;
        $sep = - 1;
        $j = $i;
        $l = 0;
        $nl ++;
        continue;
      }
      if ($c == ' ') $sep = $i;
      $l += $cw[$c];
      if ($l > $wmax)
      {
        if ($sep == - 1)
        {
          if ($i == $j)
          $i ++;
        }
        else $i = $sep + 1;
        $sep = - 1;
        $j = $i;
        $l = 0;
        $nl ++;
      } else
      $i ++;
    }
    return $nl;
  }

  var $extgstates = array();

  // alpha: real value from 0 (transparent) to 1 (opaque)
  // bm: blend mode, one of the following:
  // Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
  // HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
  function SetAlpha($alpha, $bm = 'Normal')
  {
    // set alpha for stroking (CA) and non-stroking (ca) operations
    $gs = $this->AddExtGState(array(
      'ca' => $alpha,
      'CA' => $alpha,
      'BM' => '/' . $bm
    ));
    $this->SetExtGState($gs);
  }

  function AddExtGState($parms)
  {
    $n = count($this->extgstates) + 1;
    $this->extgstates[$n]['parms'] = $parms;
    return $n;
  }

  function SetExtGState($gs)
  {
    $this->_out(sprintf('/GS%d gs', $gs));
  }

  function _enddoc()
  {
    if (! empty($this->extgstates) && $this->PDFVersion < '1.4')
    $this->PDFVersion = '1.4';
    parent::_enddoc();
  }

  function _putextgstates()
  {
    for ($i = 1; $i <= count($this->extgstates); $i ++) {
      $this->_newobj();
      $this->extgstates[$i]['n'] = $this->n;
      $this->_out('<</Type /ExtGState');
      $parms = $this->extgstates[$i]['parms'];
      $this->_out(sprintf('/ca %.3F', $parms['ca']));
      $this->_out(sprintf('/CA %.3F', $parms['CA']));
      $this->_out('/BM ' . $parms['BM']);
      $this->_out('>>');
      $this->_out('endobj');
    }
  }

  function _putresourcedict()
  {
    parent::_putresourcedict();
    $this->_out('/ExtGState <<');
    foreach ($this->extgstates as $k => $extgstate) $this->_out('/GS' . $k . ' ' . $extgstate['n'] . ' 0 R');
    $this->_out('>>');
  }

  function _putresources()
  {
    $this->_putextgstates();
    parent::_putresources();
  }
}
?>
