<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EntregaAlmacen extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio=null)
  {
    $query = $this->db->get_where('entrega_almacen', array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function agregar($datos=null)
  {
    $data=$this->db->insert('entrega_almacen',$datos);
    return $data;
  }
}
