<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class Marca extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("marca");
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('marca', array('id' => $id ));
    return $query->result_array();
  }

  public function update($nombre,$id)
  {
    $data = array('nombre' => $nombre);
    $this->db->where('id', $id);
    $this->db->update('marca', $data);
  }

  public function insert($data)
  {
    $this->db->insert('marca', $data);
    return $this->db->insert_id();
  }
}
