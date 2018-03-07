<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CostoCorte extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio=null)
  {
    $query = $this->db->get_where('costo_corte', array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function agregar($datos=null)
  {
    $data=$this->db->insert('costo_corte',$datos);
    return $data;
  }
}
