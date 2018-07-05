<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* Modelo de tabla entrega_almacen
* +-------------+---------+------+-----+---------+----------------+
* | Field       | Type    | Null | Key | Default | Extra          |
* +-------------+---------+------+-----+---------+----------------+
* | corte_folio | int(11) | NO   | MUL | NULL    |                |
* | fecha       | date    | NO   |     | NULL    |                |
* | usuario_id  | int(11) | NO   | MUL | NULL    |                |
* | lavado_id   | int(11) | NO   | MUL | NULL    |                |
* | id          | int(11) | NO   | PRI | NULL    | auto_increment |
* +-------------+---------+------+-----+---------+----------------+
*/
class EntregaAlmacen extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function existe($folio,$lavado)
  {
    $query = $this->db->get_where('entrega_almacen',array('corte_folio' => $folio, 'lavado_id' => $lavado));
    return $query->result_array();
  }

  public function getByFolio($folio = null)
  {
    $query = $this->db->get_where('entrega_almacen', array(
      'corte_folio' => $folio
    ));
    return $query->result_array();
  }

  public function getByFolioEspecifico($folio = null)
  {
    $this->db->select('
    entrega_almacen.fecha,
    usuario.nombre_completo as usuario,
    entrega_almacen.lavado_id as lavadoid,
    lavado.nombre as lavado
    ')
    ->from('entrega_almacen')
    ->join('usuario', 'usuario.id = entrega_almacen.usuario_id')
    ->join('lavado', 'lavado.id = entrega_almacen.lavado_id')
    ->where('entrega_almacen.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  public function agregar($datos = null)
  {
    $datos['usuario_id'] = $_SESSION['usuario_id'];
    $data = $this->db->insert('entrega_almacen', $datos);
    return $data;
  }
}
