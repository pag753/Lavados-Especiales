<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +------------+-------------+------+-----+---------+----------------+
* | Field      | Type        | Null | Key | Default | Extra          |
* +------------+-------------+------+-----+---------+----------------+
* | nombre     | varchar(45) | NO   |     | NULL    |                |
* | cliente_id | int(11)     | NO   | MUL | NULL    |                |
* | id         | int(11)     | NO   | PRI | NULL    | auto_increment |
* +------------+-------------+------+-----+---------+----------------+
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

  public function getJoin()
  {
    $this->db->select('
    cliente.nombre as clienteNombre,
    cliente.id as clienteId,
    marca.id as marcaId,
    marca.nombre as marcaNombre')
    ->from('marca')
    ->join('cliente', 'marca.cliente_id=cliente.id', 'left');
    return $this->db->get()->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('marca', array(
      'id' => $id
    ));
    return $query->result_array();
  }

  public function getByCliente($cliente)
  {
    $this->db->select('
    cliente.nombre as clienteNombre,
    cliente.id as clienteId,
    marca.id as marcaId,
    marca.nombre as marcaNombre')
    ->from('marca')
    ->join('cliente', 'marca.cliente_id=cliente.id')
    ->where('marca.cliente_id', $cliente);
    return $this->db->get()->result_array();
  }

  public function update($nombre, $cliente, $id)
  {
    $data = array(
      'nombre' => $nombre,
      'cliente_id' => $cliente
    );
    $this->db->where('id', $id);
    $this->db->update('marca', $data);
  }

  public function insert($data)
  {
    $this->db->insert('marca', $data);
    return $this->db->insert_id();
  }
}
