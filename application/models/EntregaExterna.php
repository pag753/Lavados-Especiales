<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EntregaExterna extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio=null)
  {
    $query = $this->db->get_where('entrega_externa', array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function agregar($datos=null)
  {
    $data=$this->db->insert('entrega_externa',$datos);
    return $data;
  }
}
