<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +-------------+---------+------+-----+---------+----------------+
* | Field       | Type    | Null | Key | Default | Extra          |
* +-------------+---------+------+-----+---------+----------------+
* | fecha       | date    | NO   |     | NULL    |                |
* | razon       | text    | NO   |     | NULL    |                |
* | usuario_id  | int(11) | NO   | MUL | NULL    |                |
* | cantidad    | float   | NO   |     | NULL    |                |
* | corte_folio | int(11) | YES  | MUL | NULL    |                |
* | id          | int(11) | NO   | PRI | NULL    | auto_increment |
* +-------------+---------+------+-----+---------+----------------+
*/

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
    $query = $this->db->get_where('descuento', array(
      'id' => $id
    ));
    return $query->result_array();
  }

  public function getByIdUsuario($id)
  {
    $query = $this->db->get_where('descuento', array(
      'usuario_id' => $id
    ));
    return $query->result_array();
  }

  public function update($razon, $fecha, $cantidad, $folio, $id)
  {
    $data = array(
      'fecha' => $fecha,
      'razon' => $razon,
      'cantidad' => $cantidad,
      'corte_folio' => $folio
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
    $this->db->delete('descuento', array(
      'id' => $id
    ));
  }

  // Consulta para los operarios
  public function consulta1($idUsuario, $fechaInicial, $fechaFinal)
  {
    $this->db->select('fecha, razon, cantidad, corte_folio')
    ->from('descuento')
    ->where('usuario_id', $idUsuario)
    ->where('fecha>=', $fechaInicial)
    ->where('fecha<=', $fechaFinal)
    ->order_by('fecha');
    return $this->db->get()->result_array();
  }
}
