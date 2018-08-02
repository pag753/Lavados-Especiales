<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +-----------------+-------------+------+-----+---------+----------------+
* | Field           | Type        | Null | Key | Default | Extra          |
* +-----------------+-------------+------+-----+---------+----------------+
* | id              | int(11)     | NO   | PRI | NULL    | auto_increment |
* | tipo_usuariocol | varchar(45) | NO   |     | NULL    |                |
* +-----------------+-------------+------+-----+---------+----------------+
*/
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
