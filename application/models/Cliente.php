<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +-----------+--------------+------+-----+---------+----------------+
* | Field     | Type         | Null | Key | Default | Extra          |
* +-----------+--------------+------+-----+---------+----------------+
* | nombre    | varchar(100) | NO   |     | NULL    |                |
* | direccion | varchar(100) | NO   |     | NULL    |                |
* | telefono  | varchar(45)  | NO   |     | NULL    |                |
* | id        | int(11)      | NO   | PRI | NULL    | auto_increment |
* +-----------+--------------+------+-----+---------+----------------+
*/

class Cliente extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("cliente");
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('cliente', array(
      'id' => $id
    ));
    return $query->result_array();
  }

  public function update($nombre, $direccion, $telefono, $id)
  {
    $data = array(
      'nombre' => $nombre,
      'direccion' => $direccion,
      'telefono' => $telefono
    );
    $this->db->where('id', $id);
    $this->db->update('cliente', $data);
  }

  public function insert($data)
  {
    $this->db->insert('cliente', $data);
    return $this->db->insert_id();
  }
}
