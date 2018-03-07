<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Maquilero extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("maquilero");
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('maquilero', array('id' => $id ));
    return $query->result_array();
  }

  public function update($nombre,$direccion,$telefono,$id)
  {
    $data = array('nombre' => $nombre,
                  'direccion'=>$direccion,
                  'telefono'=>$telefono);
    $this->db->where('id', $id);
    $this->db->update('maquilero', $data);
  }

  public function insert($data)
  {
    $this->db->insert('maquilero', $data);
    return $this->db->insert_id();
  }
}
