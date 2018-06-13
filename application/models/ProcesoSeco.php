<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProcesoSeco extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get()
    {
        $this->db->from("proceso_seco");
        $this->db->order_by("nombre", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getById($id)
    {
        $query = $this->db->get_where('proceso_seco', array(
            'id' => $id
        ));
        return $query->result_array();
    }

    public function update($nombre, $costo, $abreviatura, $id)
    {
        $data = array(
            'nombre' => $nombre,
            'costo' => $costo,
            'abreviatura' => $abreviatura
        );
        $this->db->where('id', $id);
        $this->db->update('proceso_seco', $data);
    }

    public function insert($data)
    {
        $this->db->insert('proceso_seco', $data);
        return $this->db->insert_id();
    }
}
