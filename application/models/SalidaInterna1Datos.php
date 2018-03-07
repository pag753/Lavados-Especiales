<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalidaInterna1Datos extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio=null)
  {
    $query = $this->db->get_where('salida_interna1_datos', array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function agregar($datos=null)
  {
    $data=$this->db->insert('salida_interna1_datos',$datos);
    return $data;
  }
}
