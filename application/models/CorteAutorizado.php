<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CorteAutorizado extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio = null)
  {
    $query = $this->db->get_where('corte_autorizado', array(
      'corte_folio' => $folio
    ));
    return $query->result_array();
  }

  public function getByFolioEspecifico($folio = null)
  {
    $this->db->select('
    corte_autorizado.id_carga as carga,
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo,
    lavado.nombre as lavado,
    corte_autorizado.fecha_autorizado as fecha,
    usuario.nombre_completo as operario')
    ->from('corte_autorizado')
    ->join('lavado','lavado.id=corte_autorizado.lavado_id')
    ->join('usuario', 'usuario.id=corte_autorizado.usuario_id')
    ->where('corte_autorizado.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  public function agregar($datos = null)
  {
    $datos['usuario_id'] = $_SESSION['usuario_id'];
    $this->db->insert('corte_autorizado', $datos);
    return $this->db->insert_id();
  }

  public function update($data)
  {
    $this->db->where('corte_folio', $data['corte_folio']);
    unset($data['corte_folio']);
    $this->db->update('corte_autorizado', $data);
  }

  public function deleteByFolio($folio)
  {
    $this->db->where('corte_folio', $folio);
    $this->db->delete('corte_autorizado');
  }

  public function disminuyeCargasEn1($folio)
  {
    $this->db->query("UPDATE corte_autorizado SET cargas=cargas-1 WHERE corte_folio='" . $folio . "';");
  }

  public function aumentaCargasEn1($folio)
  {
    $this->db->query("UPDATE corte_autorizado SET cargas=cargas+1 WHERE corte_folio='" . $folio . "';");
  }

  // NUEVOS QUERYS DESDE EL CAMBIO DE BASE DE DATOS
  public function getCargas($folio)
  {
    $this->db->select('
    corte_autorizado.id as id,
    corte_autorizado.id_carga as id_carga,
    lavado.nombre as lavado,
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo')
    ->from('corte_autorizado')
    ->join('lavado','lavado.id=corte_autorizado.lavado_id')
    ->where('corte_autorizado.corte_folio',$folio)
    ->order_by('corte_autorizado.id_carga');
    return $this->db->get()->result_array();
  }

  public function updateAdministracion($data)
  {
    $this->db->where('id', $data['id']);
    unset($data['id']);
    $this->db->set($data);
    $this->db->update('corte_autorizado');
  }

  public function deleteAdministracion($data)
  {
    $this->db->where($data);
    $this->db->delete('corte_autorizado');
  }

  public function nuevoLavado($data)
  {
    $this->db->insert('corte_autorizado', $data);
    return $this->db->insert_id();
  }
}
