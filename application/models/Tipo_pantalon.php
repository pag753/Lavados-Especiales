<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Tipo_pantalon extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get()
    {
        $this->db->from("tipo_pantalon");
        $this->db->order_by("nombre", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getById($id)
    {
        $query = $this->db->get_where('tipo_pantalon', array('id' => $id ));
        return $query->result_array();
    }

    public function update($nombre,$id)
    {
        $data = array('nombre' => $nombre,);

        $this->db->where('id', $id);
        $this->db->update('tipo_pantalon', $data);
    }

    public function insert($data)
    {
        $this->db->insert('tipo_pantalon', $data);
        return $this->db->insert_id();
    }
}
