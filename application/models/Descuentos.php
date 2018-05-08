<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Descuentos extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("descuento");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('descuento', array('id' => $id ));
    return $query->result_array();
  }

  public function getByIdUsuario($id)
  {
    $query = $this->db->get_where('descuento', array('usuario_id' => $id ));
    return $query->result_array();
  }

  public function update($razon,$fecha,$cantidad,$id)
  {
    $data = array(
      'fecha' => $fecha,
      'razon' => $razon,
      'cantidad' => $cantidad,
    );
    $this->db->where('id', $id);
    $this->db->update('descuento', $data);
  }

  public function insert($data)
  {
    $this->db->insert('descuento', $data);
    return $this->db->insert_id();
  }

  public function delete($id)
  {
    $this->db->delete('descuento', array('id' => $id));
  }
}