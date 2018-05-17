<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalidaInterna1 extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getByFolio($folio=null)
    {
        $query = $this->db->get_where('salida_interna1', array('corte_folio' => $folio));
        return $query->result_array();
    }

    public function agregar($datos=null)
    {
        $datos['usuario_id'] = $_SESSION['usuario_id'];
        $data=$this->db->insert('salida_interna1',$datos);
        return $data;
    }

    public function deleteByFolio($folio)
    {
      $this->db->where('corte_folio', $folio);
      $this->db->delete('salida_interna1');
    }

    public function update($data)
    {
      $this->db->where('corte_folio', $data['corte_folio']);
  		unset($data['corte_folio']);
      $this->db->update('salida_interna1', $data);
    }
}
