<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reproceso extends CI_Model
{
  /*
  STATUS:
  0 no registrado
  1 para registrar
  2 registrado
  */
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("reproceso");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('reproceso', array('id' => $id ));
    return $query->result_array();
  }

  public function update($data)
  {
    $this->db->where('id', $data['id']);
    unset($data['id']);
    $this->db->set($data);
    $this->db->update('reproceso');
  }

  public function insert($data)
  {
    $this->db->insert('reproceso', $data);
    return $this->db->insert_id();
  }

  public function getByFolioOperarios($folio)
  {
    $this->db->select('
    reproceso.id as id,
    proceso_seco.nombre as proceso_seco,
    lavado.nombre as lavado,
    reproceso.status as status
    ')
    ->from("reproceso")
    ->join("proceso_seco","proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado","lavado.id=reproceso.lavado_id")
    ->where("corte_folio",$folio)
    ->order_by("lavado.nombre,proceso_seco.nombre");
    return $this->db->get()->result_array();
  }

  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
    reproceso.id as id,
    proceso_seco.nombre as proceso_seco,
    lavado.nombre as lavado,
    reproceso.costo as costo,
    reproceso.piezas_trabajadas as piezas_trabajadas,
    reproceso.defectos as defectos,
    reproceso.status as status,
    reproceso.fecha_registro as fecha_registro,
    reproceso.usuario_id as usuario_id,
    proceso_seco.id as proceso_seco_id,
    lavado.id as lavado_id,
    usuario.nombre_completo as usuario_nombre
    ')
    ->from("reproceso")
    ->join("proceso_seco","proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado","lavado.id=reproceso.lavado_id")
    ->join("usuario","reproceso.usuario_id=usuario.id")
    ->where("reproceso.corte_folio",$folio)
    ->order_by("lavado.nombre,proceso_seco.nombre");
    return $this->db->get()->result_array();
  }
  public function deleteById($id)
  {
    $this->db
    ->where('id',$id)
    ->delete('reproceso');
  }
}
