<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ahorros extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("ahorro");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('ahorro', array(
      'id' => $id
    ));
    return $query->result_array();
  }

  public function getByIdUsuario($id)
  {
    $query = $this->db->get_where('ahorro', array(
      'usuario_id' => $id
    ));
    return $query->result_array();
  }

  public function update($aportacion, $fecha, $cantidad, $id)
  {
    $data = array(
      'fecha' => $fecha,
      'aportacion' => $aportacion,
      'cantidad' => $cantidad
    );
    $this->db->where('id', $id);
    $this->db->update('ahorro', $data);
  }

  public function insert($data)
  {
    $this->db->insert('ahorro', $data);
    return $this->db->insert_id();
  }

  public function delete($id)
  {
    $this->db->delete('ahorro', array(
      'id' => $id
    ));
  }
}
