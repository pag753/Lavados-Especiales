<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +-------------+---------+------+-----+---------+-------+
* | Field       | Type    | Null | Key | Default | Extra |
* +-------------+---------+------+-----+---------+-------+
* | corte_folio | int(11) | NO   | PRI | NULL    |       |
* | fecha       | date    | NO   |     | NULL    |       |
* | muestras    | int(11) | NO   |     | NULL    |       |
* | usuario_id  | int(11) | NO   | MUL | NULL    |       |
* +-------------+---------+------+-----+---------+-------+
*/

class SalidaInterna1 extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio = null)
  {
    $query = $this->db->get_where('salida_interna1', array(
      'corte_folio' => $folio
    ));
    return $query->result_array();
  }

  public function getByFolioEspecifico($folio = null)
  {
    $this->db->select('
    salida_interna1.fecha as fecha,
    salida_interna1.muestras as muestras,
    usuario.nombre_completo as usuario
    ')
    ->from('salida_interna1')
    ->join('usuario', 'usuario.id=salida_interna1.usuario_id')
    ->where('salida_interna1.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  public function agregar($datos = null)
  {
    $datos['usuario_id'] = $_SESSION['usuario_id'];
    $data = $this->db->insert('salida_interna1', $datos);
    return $data;
  }

  public function deleteByFolio($folio)
  {
    $this->db->from('salida_interna1')
    ->where('corte_folio', $folio)
    ->delete('salida_interna1');
  }

  public function update($data)
  {
    $this->db->where('corte_folio', $data['corte_folio']);
    unset($data['corte_folio']);
    $this->db->update('salida_interna1', $data);
  }
}
