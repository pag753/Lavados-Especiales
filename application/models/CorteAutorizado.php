<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CorteAutorizado extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getByFolio($folio = null)
	{
		$query = $this->db->get_where('corte_autorizado', array('corte_folio' => $folio));
		return $query->result_array();
	}

	public function agregar($datos = null)
	{
		$datos['usuario_id'] = $_SESSION['usuario_id'];
		$data=$this->db->insert('corte_autorizado',$datos);
		return $data;
	}

	public function update($data)
	{
		$this->db->where('corte_folio', $data['corte_folio']);
		unset($data['corte_folio']);
    $this->db->update('corte_autorizado', $data);
	}

	public function deleteByFolio($folio)
  {
    $this->db->where('corte_folio', $folio);
    $this->db->delete('corte_autorizado');
  }

	public function disminuyeCargasEn1($folio)
	{
		$this->db->query("UPDATE corte_autorizado SET cargas=cargas-1 WHERE corte_folio='".$folio."';");
	}
}
