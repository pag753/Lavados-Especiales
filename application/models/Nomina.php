<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nomina extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("nomina");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getDistinct()
  {
    $query = $this->db->query("SELECT DISTINCT id, fecha, descripcion FROM nomina");
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('nomina', array('id' => $id ));
    return $query->result_array();
  }

  public function insert($data)
  {
    $this->db->insert('nomina', $data);
    return $this->db->insert_id();
  }

  public function delete($id)
  {
    $this->db->delete('nomina', array('id' => $id));
  }

  public function getUltimaNomina()
  {
    $query = $this->db->query('SELECT * from nomina WHERE id=(SELECT MAX(id) FROM nomina)');
    return $query->result_array();
  }
}
