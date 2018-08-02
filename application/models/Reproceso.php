<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +---------------------+-------------+------+-----+---------+----------------+
* | Field               | Type        | Null | Key | Default | Extra          |
* +---------------------+-------------+------+-----+---------+----------------+
* | corte_autorizado_id | int(11)     | NO   | MUL | NULL    |                |
* | proceso_seco_id     | int(11)     | NO   | MUL | NULL    |                |
* | costo               | float       | NO   |     | NULL    |                |
* | piezas_trabajadas   | varchar(45) | NO   |     | 0       |                |
* | defectos            | varchar(45) | NO   |     | 0       |                |
* | status              | varchar(45) | NO   |     | 0       |                |
* | fecha_registro      | date        | NO   |     | NULL    |                |
* | usuario_id          | int(11)     | NO   | MUL | 6       |                |
* | id                  | int(11)     | NO   | PRI | NULL    | auto_increment |
* +---------------------+-------------+------+-----+---------+----------------+
*/

class Reproceso extends CI_Model
{

  /*
  * STATUS:
  * 0 no registrado
  * 1 para registrar
  * 2 registrado
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
    $query = $this->db->get_where('reproceso', array(
      'id' => $id
    ));
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

  //Cambios aplicados por la base de datos
  public function getByFolioOperarios($folio)
  {
    $this->db->select('
    reproceso.id as id,
    corte_autorizado.id_carga as id_carga,
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo,
    reproceso.corte_autorizado_id as corte_autorizado_id,
    reproceso.status as status,
    proceso_seco.nombre as proceso_seco,
    lavado.nombre as lavado
    ')
    ->from("reproceso")
    ->join('corte_autorizado','corte_autorizado.id=reproceso.corte_autorizado_id')
    ->join("proceso_seco", "proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado", "lavado.id=corte_autorizado.lavado_id")
    ->where("corte_folio", $folio)
    ->order_by("lavado.nombre,proceso_seco.nombre");
    return $this->db->get()->result_array();
  }

  //Cambios por base de datos
  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
    corte_autorizado.id as corte_autorizado_id,
    corte_autorizado.id_carga as id_carga,
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
    ->join('corte_autorizado','reproceso.corte_autorizado_id=corte_autorizado.id')
    ->join("proceso_seco", "proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado", "lavado.id=corte_autorizado.lavado_id")
    ->join("usuario", "reproceso.usuario_id=usuario.id")
    ->where("corte_autorizado.corte_folio", $folio)
    ->order_by("lavado.nombre,proceso_seco.nombre");
    return $this->db->get()->result_array();
  }

  public function deleteById($id)
  {
    $this->db->where('id', $id)->delete('reproceso');
  }
}
