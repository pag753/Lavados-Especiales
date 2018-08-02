<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +----------------+------------+------+-----+---------+----------------+
* | Field          | Type       | Null | Key | Default | Extra          |
* +----------------+------------+------+-----+---------+----------------+
* | id             | int(11)    | NO   | PRI | NULL    | auto_increment |
* | usuario_id     | int(11)    | NO   | MUL | NULL    |                |
* | piezas         | int(11)    | NO   |     | NULL    |                |
* | fecha          | date       | NO   |     | NULL    |                |
* | defectos       | int(11)    | NO   |     | NULL    |                |
* | reproceso_id   | int(11)    | NO   | MUL | NULL    |                |
* | estado_nomina  | tinyint(4) | YES  |     | NULL    |                |
* | cantidad_pagar | float      | NO   |     | NULL    |                |
* | nomina_id      | int(11)    | YES  |     | NULL    |                |
* | razon_pagar    | text       | YES  |     | NULL    |                |
* +----------------+------------+------+-----+---------+----------------+
*/

class ProduccionReproceso extends CI_Model
{

  /*
  * estado_nomina:
  * 0: NO Pagado
  * 1: Pagado
  * 2: Pendiente
  * 3: No se pagará
  *
  */
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio)
  {
    $query = $this->db->get_where('produccion_reproceso', array(
      'corte_folio' => $folio
    ));
    return $query->result_array();
  }

  public function insertar($data)
  {
    $this->db->insert('produccion_reproceso', $data);
  }

  public function getWhere($datos)
  {
    $query = $this->db->get_where('produccion_reproceso', $datos);
    return $query->result_array();
  }

  public function updateByOperario($data)
  {
    $this->db->where('reproceso_id', $data['reproceso_id'])->where('usuario_id', $data['usuario_id']);
    unset($data['reproceso_id']);
    unset($data['usuario_id']);
    $this->db->update('produccion_reproceso', $data);
  }

  public function getByIdEspecifico($id)
  {
    $this->db->select('
    t1.nombre_completo as nombre,
    t0.piezas as piezas,
    t0.fecha as fecha,
    t0.defectos as defectos
    ')
    ->from('produccion_reproceso t0')
    ->join('usuario t1', 't1.id=t0.usuario_id')
    ->join('reproceso t2', 't2.id=t0.reproceso_id')
    ->order_by('t0.fecha')
    ->where('t2.id', $id);
    return $this->db->get()->result_array();
  }

  public function getByFechas($fechaInicial, $fechaFinal)
  {
    $this->db->select('
    t7.id_carga as carga,
    t1.razon_pagar as razon,
    t7.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo')
    ->from('produccion_reproceso as t1')
    ->join('reproceso t6','t6.id=t1.reproceso_id')
    ->join('corte_autorizado t7','t7.id=t6.corte_autorizado_id')
    ->join('reproceso t4', 't4.id=t1.reproceso_id')
    ->join('lavado t2', 't2.id=t7.lavado_id')
    ->join('proceso_seco t3', 't3.id=t4.proceso_seco_id')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where('t1.fecha<=', $fechaFinal)
    ->where('t1.fecha>=', $fechaInicial)
    ->where('t4.status=', 2)
    ->where('t1.estado_nomina', 0)
    ->where('t4.costo!=', 0)
    ->where('t5.activo', 1)
    ->order_by('t5.nombre_completo')
    ->order_by('t7.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function getByFolios($folios)
  {
    $this->db->select('
    t7.id_carga as carga,
    t1.razon_pagar as razon,
    t7.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo')
    ->from('produccion_reproceso as t1')
    ->join('reproceso t6','t6.id=t1.reproceso_id')
    ->join('corte_autorizado t7','t7.id=t6.corte_autorizado_id')
    ->join('reproceso t4', 't4.id=t1.reproceso_id')
    ->join('lavado t2', 't2.id=t7.lavado_id')
    ->join('proceso_seco t3', 't3.id=t4.proceso_seco_id')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where_in('t7.corte_folio', $folios)
    ->where('t4.status=', 2)
    ->where('t1.estado_nomina', 0)
    ->where('t4.costo!=', 0)
    ->where('t5.activo', 1)
    ->order_by('t5.nombre_completo')
    ->order_by('t7.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function getPendientes()
  {
    $this->db->select('
    t6.id_carga as carga,
    t1.razon_pagar as razon,
    t6.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo')
    ->from('produccion_reproceso as t1')
    //->join('corte_autorizado_datos t7','t7.id=t1.corte_autorizado_datos_id')
    ->join('reproceso t4', 't4.id=t1.reproceso_id')
    ->join('corte_autorizado t6','t6.id=t4.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t6.lavado_id')
    ->join('proceso_seco t3', 't3.id=t4.proceso_seco_id')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where('t1.estado_nomina', 2)
    ->where('t4.costo!=', 0)
    ->where('t5.activo', 1)
    ->order_by('t5.nombre_completo')
    ->order_by('t6.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function update($data)
  {
    $this->db->where('id', $data['id']);
    unset($data['id']);
    $this->db->set($data)->update('produccion_reproceso');
  }

  public function nominaEspecifico($id)
  {
    $this->db->select('
    t6.id_carga as carga,
    t1.estado_nomina as estado,
    t1.razon_pagar as razon,
    t6.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo')
    ->from('produccion_reproceso as t1')
    ->join('reproceso t4', 't4.id=t1.reproceso_id')
    ->join('corte_autorizado t6','t6.id=t4.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t6.lavado_id')
    ->join('proceso_seco t3', 't3.id=t4.proceso_seco_id')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where('t1.nomina_id', $id)
    ->where('t4.costo!=', 0)
    ->order_by('t5.nombre_completo')
    ->order_by('t6.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function regresaNomina($id)
  {
    $this->db->where('id_nomina', $id)
    ->set(array(
      'estado_nomina' => 0,
      'id_nomina' => 0,
      'cantidad_pagar' => 0,
      'razon_pagar' => ""
    ))
    ->update('produccion_reproceso');
  }

  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
    corte_autorizado.id_carga as id_carga,
    produccion_reproceso.id as id,
    produccion_reproceso.piezas as piezas,
    produccion_reproceso.fecha as fecha,
    produccion_reproceso.defectos as defectos,
    produccion_reproceso.estado_nomina as estado_nomina,
    produccion_reproceso.razon_pagar as razon_pagar,
    lavado.nombre as lavado_nombre,
    proceso_seco.nombre as proceso_seco,
    usuario.nombre_completo as usuario_nombre,
    proceso_seco.nombre as proceso,
    TRUNCATE(reproceso.costo,2) as costo,
    TRUNCATE((reproceso.costo*produccion_reproceso.piezas),2) as total
    ')
    ->from("produccion_reproceso")
    ->join("reproceso", "reproceso.id=produccion_reproceso.reproceso_id")
    ->join('corte_autorizado','corte_autorizado.id=reproceso.corte_autorizado_id')
    ->join("proceso_seco", "proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado", "lavado.id=corte_autorizado.lavado_id")
    ->join("usuario", "produccion_reproceso.usuario_id=usuario.id")
    ->where("corte_autorizado.corte_folio", $folio)
    ->order_by("lavado.nombre,proceso_seco.nombre");
    return $this->db->get()->result_array();
  }

  public function deleteByIdReproceso($id)
  {
    $this->db->where('reproceso_id', $id)->delete('produccion_reproceso');
  }

  public function deleteById($id)
  {
    $this->db->where('id', $id)->delete('produccion_reproceso');
  }

  public function getByFolioEspecifico2($folio)
  {
    $this->db->select('
    corte_autorizado.id_carga as carga,
    produccion_reproceso.id as id,
    produccion_reproceso.piezas as piezas,
    produccion_reproceso.fecha as fecha,
    produccion_reproceso.defectos as defectos,
    produccion_reproceso.estado_nomina as estado_nomina,
    produccion_reproceso.razon_pagar as razon_pagar,
    lavado.nombre as lavado_nombre,
    lavado.id as lavado_id,
    proceso_seco.nombre as proceso_seco,
    proceso_seco.id as proceso_seco_id,
    usuario.nombre_completo as usuario_nombre,
    proceso_seco.nombre as proceso,
    TRUNCATE(reproceso.costo,2) as costo,
    TRUNCATE((reproceso.costo*produccion_reproceso.piezas),2) as total
    ')
    ->from("produccion_reproceso")
    ->join("reproceso", "reproceso.id=produccion_reproceso.reproceso_id")
    ->join('corte_autorizado','corte_autorizado.id=reproceso.corte_autorizado_id')
    ->join("proceso_seco", "proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado", "lavado.id=corte_autorizado.lavado_id")
    ->join("usuario", "produccion_reproceso.usuario_id=usuario.id")
    ->where("corte_autorizado.corte_folio", $folio)
    ->where('produccion_reproceso.estado_nomina', 1)
    ->where('reproceso.costo!=', 0);
    return $this->db->get()->result_array();
  }

  public function getNominasByOperario($id)
  {
    $this->db->distinct()
    ->select('
    produccion_reproceso.id_nomina as id_nomina,
    nomina.fecha as fecha
    ')
    ->from('produccion_reproceso')
    ->join('nomina', 'nomina.id= produccion_reproceso.id_nomina')
    ->where('nomina.usuario_id', $id);
    return $this->db->get()->result_array();
  }

  public function getWhereEspecifico($data)
  {
    $this->db->select('
    reproceso.corte_folio as folio,
    produccion_reproceso.piezas as piezas,
    produccion_reproceso.fecha as fecha,
    produccion_reproceso.defectos as defectos,
    produccion_reproceso.estado_nomina as estado,
    produccion_reproceso.cantidad_pagar as cantidad_pagar,
    produccion_reproceso.razon_pagar as razon_pagar,
    proceso_seco.nombre as proceso,
    lavado.nombre as lavado,
    ')
    ->from('produccion_reproceso')
    ->join('reproceso', 'reproceso.id=produccion_reproceso.id_nomina')
    ->join('lavado', 'lavado.id=reproceso.lavado_id')
    ->join('proceso_seco', 'proceso_seco.id=reproceso.proceso_seco_id')
    ->where('produccion_reproceso.usuario_id', $data['usuario_id'])
    ->where('produccion_reproceso.id_nomina', $data['id_nomina']);
    return $this->db->get()->result_array();
  }
}
