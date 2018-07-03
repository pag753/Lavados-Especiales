<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

  public function getWhere($data)
  {
    $query = $this->db->get_where('nomina', $data);
    return $query->result_array();
  }

  public function getDistinct()
  {
    $query = $this->db->query("SELECT DISTINCT id, fecha, descripcion FROM nomina");
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('nomina', array(
      'id' => $id
    ));
    return $query->result_array();
  }

  public function insert($data)
  {
    $this->db->insert('nomina', $data);
    return $this->db->insert_id();
  }

  public function delete($id)
  {
    $this->db->delete('nomina', array(
      'id' => $id
    ));
  }

  public function getUltimaNomina()
  {
    $query = $this->db->query('SELECT * from nomina WHERE id=(SELECT MAX(id) FROM nomina)');
    return $query->result_array();
  }

  public function getNominaById($id = null)
  {
    $this->db->select('
    nomina.descripcion as descripcion,
    usuario.nombre_completo as nombre,
    puesto.nombre as puesto,
    nomina.fecha as fecha,
    nomina.descripcion as descripcion,
    nomina.usuario_id as usuario_id,
    nomina.saldo_anterior as saldo_anterior,
    nomina.nomina as nomina,
    nomina.descuentos_anterior as descuentos_anterior,
    nomina.descuentos_abono as descuentos_abono,
    nomina.descuentos_saldo as descuentos_saldo,
    nomina.ahorro_anterior as ahorro_anterior,
    nomina.ahorro_abono as ahorro_abono,
    nomina.ahorro_saldo as ahorro_saldo,
    nomina.bonos as bonos,
    nomina.total as total,
    nomina.pagado as pagado')
    ->from('nomina')
    ->join('usuario', 'usuario.id=nomina.usuario_id')
    ->join('puesto', 'usuario.puesto_id=puesto.id')
    ->where('nomina.id', $id);
    return $this->db->get()->result_array();
  }
}
