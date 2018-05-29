<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProduccionReproceso extends CI_Model
{
  /*
  estado_nomina:{
  0: NO Pagado
  1: Pagado
  2: Pendiente
  3: No se pagará
  }
  */
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio)
  {
    $query = $this->db->get_where('produccion_reproceso', array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function insertar($data)
  {
    $this->db->insert('produccion_reproceso',$data);
  }

  public function getWhere($datos)
  {
    $query = $this->db->get_where('produccion_reproceso', $datos);
    return $query->result_array();
  }

  public function updateByOperario($data)
  {
    $this->db->where('reproceso_id',$data['reproceso_id'])
    ->where('usuario_id',$data['usuario_id']);
      unset($data['reproceso_id']);
      unset($data['usuario_id']);
    $this->db->update('produccion_reproceso',$data);
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
    ->join('usuario t1','t1.id=t0.usuario_id')
    ->join('reproceso t2','t2.id=t0.reproceso_id')
    ->order_by('t0.fecha')
    ->where('t2.id',$id);
    return $this->db->get()->result_array();
  }

  public function getByFechas($fechaInicial,$fechaFinal)
  {
    $this->db->select('
    t1.razon_pagar as razon,
    t4.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo'
    )
    ->from('produccion_reproceso as t1')
    ->join('reproceso t4','t4.id=t1.reproceso_id')
    ->join('lavado t2','t2.id=t4.lavado_id')
    ->join('proceso_seco t3','t3.id=t4.proceso_seco_id')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where('t1.fecha<=',$fechaFinal)
    ->where('t1.fecha>=',$fechaInicial)
    ->where('t4.status=',2)
    ->where('t1.estado_nomina',0)
    ->where('t4.costo!=',0)
    ->where('t5.activo',1)
    ->order_by('t5.nombre_completo')
    ->order_by('t1.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function getByFolios($folios)
  {
    $this->db->select('
    t1.razon_pagar as razon,
    t4.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo'
    )
    ->from('produccion_reproceso as t1')
    ->join('reproceso t4','t4.id=t1.reproceso_id')
    ->join('lavado t2','t2.id=t4.lavado_id')
    ->join('proceso_seco t3','t3.id=t4.proceso_seco_id')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where_in('t4.corte_folio', $folios)
    ->where('t4.status=',2)
    ->where('t1.estado_nomina',0)
    ->where('t4.costo!=',0)
    ->where('t5.activo',1)
    ->order_by('t5.nombre_completo')
    ->order_by('t4.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function getPendientes()
  {
    $this->db->select('
    t1.razon_pagar as razon,
    t4.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo'
    )
    ->from('produccion_reproceso as t1')
    ->join('reproceso t4','t4.id=t1.reproceso_id')
    ->join('lavado t2','t2.id=t4.lavado_id')
    ->join('proceso_seco t3','t3.id=t4.proceso_seco_id')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where('t1.estado_nomina',2)
    ->where('t4.costo!=',0)
    ->where('t5.activo',1)
    ->order_by('t5.nombre_completo')
    ->order_by('t4.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function update($data)
  {
    $this->db->where('id',$data['id']);
    unset($data['id']);
    $this->db->set($data)
    ->update('produccion_reproceso');
  }

  public function nominaEspecifico($id)
  {
    $this->db->select('
    t1.estado_nomina as estado,
    t1.razon_pagar as razon,
    t4.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.id as id_produccion_reproceso,
    t1.usuario_id as usuario_id,
    t5.nombre_completo as usuario_nombre,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo'
    )
    ->from('produccion_reproceso as t1')
    ->join('reproceso t4','t4.id=t1.reproceso_id')
    ->join('lavado t2','t2.id=t4.lavado_id')
    ->join('proceso_seco t3','t3.id=t4.proceso_seco_id')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where('t1.id_nomina',$id)
    ->where('t4.costo!=',0)
    ->order_by('t5.nombre_completo')
    ->order_by('t4.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function regresaNomina($id)
  {
    $this->db
    ->where('id_nomina',$id)
    ->set(array(
      'estado_nomina' => 0,
      'id_nomina' => 0,
      'cantidad_pagar' => 0,
      'razon_pagar' => "",
    ))
    ->update('produccion_reproceso');
  }

  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
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
    ->join("reproceso","reproceso.id=produccion_reproceso.reproceso_id")
    ->join("proceso_seco","proceso_seco.id=reproceso.proceso_seco_id")
    ->join("lavado","lavado.id=reproceso.lavado_id")
    ->join("usuario","produccion_reproceso.usuario_id=usuario.id")
    ->where("corte_folio",$folio)
    ->order_by("lavado.nombre,proceso_seco.nombre");
    return $this->db->get()->result_array();
  }

  public function deleteByIdReproceso($id)
  {
    $this->db
    ->where('reproceso_id',$id)
    ->delete('produccion_reproceso');
  }

  public function deleteById($id)
  {
    $this->db
    ->where('id',$id)
    ->delete('produccion_reproceso');
  }
}
