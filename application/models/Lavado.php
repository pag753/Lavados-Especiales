<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lavado extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("lavado");
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('lavado', array('id' => $id ));
    return $query->result_array();
  }

  public function update($nombre,$id)
  {
    $data = array('nombre' => $nombre);
    $this->db->where('id', $id);
    $this->db->update('lavado', $data);
  }

  public function insert($data)
  {
    $this->db->insert('lavado', $data);
    return $this->db->insert_id();
  }
}
