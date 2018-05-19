<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Puestos extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get()
    {
        $this->db->from("puesto");
        $this->db->order_by("nombre", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update($nombre,$id)
    {
      $data = array(
        'nombre' => $nombre,
      );
      $this->db->where('id', $id);
      $this->db->update('puesto', $data);
    }

    public function insert($data)
    {
        $this->db->insert('puesto', $data);
        return $this->db->insert_id();
    }
}
