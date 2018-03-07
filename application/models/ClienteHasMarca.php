<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClienteHasMarca extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get($cliente)
  {
    $this->db->select('
      cliente_has_marca.cliente_id as idcliente,
      cliente_has_marca.marca_id as idmarca,
      marca.nombre as marca,
      cliente.nombre as cliente')
      ->from('cliente_has_marca')
      ->join('marca','cliente_has_marca.marca_id=marca.id')
      ->join('cliente','cliente_has_marca.cliente_id=cliente.id')
      ->where('cliente.id',$cliente)
      ->order_by('marca');
      return $this->db->get()->result_array();
  }

  public function delete($idcliente)
  {
    $this->db->where('cliente_id', $idcliente);
    $this->db->delete('cliente_has_marca');
  }

  public function insert($data)
  {
    $this->db->insert('cliente_has_marca', $data);
  }
}
