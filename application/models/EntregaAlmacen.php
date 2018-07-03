<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EntregaAlmacen extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
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
    usuario.nombre_completo as usuario
    ')
    ->from('entrega_almacen')
    ->join('usuario', 'usuario.id = entrega_almacen.usuario_id')
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
