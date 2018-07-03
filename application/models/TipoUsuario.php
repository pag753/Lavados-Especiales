<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TipoUsuario extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("tipo_usuario");
    $this->db->where("id!=", 5);
    $this->db->order_by("tipo_usuariocol", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }
}
