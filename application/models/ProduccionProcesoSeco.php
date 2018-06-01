<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProduccionProcesoSeco extends CI_Model
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

public function seleccionDefinida($usuario,$folio,$carga,$idproceso)
{
  $query = $this->db->get_where(
    'produccion_proceso_seco',
    array('usuario_id' => $usuario, 'corte_folio' => $folio, 'carga' => $carga, 'proceso_seco_id' => $idproceso
  ));
  return $query->result_array();
}
public function getByFolio($folio)
{
  $query = $this->db->get_where(
    'produccion_proceso_seco',
    array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function seleccionDefinida2($usuario,$folio,$carga)
  {
    $this->db->select('
    usuario.nombre as usuario,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    produccion_proceso_seco.corte_folio as folio,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.defectos as defectos,
    produccion_proceso_seco.fecha as fecha')
    ->from('produccion_proceso_seco')
    ->join('lavado','produccion_proceso_seco.lavado_id=lavado.id')
    ->join('usuario','produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco','produccion_proceso_seco.proceso_seco_id=proceso_seco.id')
    ->where('produccion_proceso_seco.corte_folio',$folio)
    ->where('produccion_proceso_seco.carga',$carga);
    return $this->db->get()->result_array();
  }

  public function seleccionDefinida3($usuario,$folio,$carga,$proceso)
  {
    $this->db->select('
    usuario.nombre as usuario,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    produccion_proceso_seco.corte_folio as folio,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.defectos as defectos,
    produccion_proceso_seco.fecha as fecha')
    ->from('produccion_proceso_seco')
    ->join('lavado','produccion_proceso_seco.lavado_id=lavado.id')
    ->join('usuario','produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco','produccion_proceso_seco.proceso_seco_id=proceso_seco.id')
    ->where('produccion_proceso_seco.corte_folio',$folio)
    ->where('produccion_proceso_seco.carga',$carga)
    ->where('produccion_proceso_seco.proceso_seco_id',$proceso);
    return $this->db->get()->result_array();
  }

  public function insertar($data)
  {
    $datos= array(
      'usuario_id' => $data['usuarioid'],
      'corte_folio' => $data['folio'],
      'carga' => $data['carga'],
      'proceso_seco_id' => $data['proceso'],
      'piezas' => $data['piezas'],
      'fecha' => date('Y-m-d'),
      'lavado_id' => $data['idlavado'],
      'defectos' => $data['defectos']
    );
    $this->db->insert('produccion_proceso_seco',$datos);
  }

  public function editar($data)
  {
    $datos= array(
      'piezas' => $data['piezas'],
      'fecha' => date('Y-m-d'),
      'defectos' => $data['defectos']
    );
    $this->db->where('id',$data['idprod']);
    $this->db->update('produccion_proceso_seco',$datos);
  }

  public function seleccionReporte($folio)
  {
    $this->db->select('
    produccion_proceso_seco.estado_nomina as estado_nomina,
    produccion_proceso_seco.id_nomina as id_nomina,
    produccion_proceso_seco.cantidad_pagar as cantidad_pagar,
    produccion_proceso_seco.razon_pagar as razon_pagar,
    produccion_proceso_seco.id as id,
    usuario.nombre as usuario,
    usuario.nombre_completo as nombre_completo,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    round(proceso_seco.costo,2) as costo,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    produccion_proceso_seco.corte_folio as folio,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.defectos as defectos,
    round((proceso_seco.costo*produccion_proceso_seco.piezas),2) as total,
    produccion_proceso_seco.fecha as fecha')
    ->from('produccion_proceso_seco')
    ->join('lavado','produccion_proceso_seco.lavado_id=lavado.id')
    ->join('usuario','produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco','produccion_proceso_seco.proceso_seco_id=proceso_seco.id')
    ->where('produccion_proceso_seco.corte_folio',$folio);
    return $this->db->get()->result_array();
  }

  //Consulta para ver la producción de los usuarios de los cortes con cargas cerradas
  public function verProduccion($usuario,$fechaInicial,$fechaFinal)
  {
    $this->db->select('
    t1.fecha as fecha,
    t1.corte_folio as folio,
    t2.nombre as carga,
    t3.nombre as proceso,
    t1.piezas as piezas,
    t4.costo as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo'
    )
    ->from('produccion_proceso_seco as t1')
    ->join('lavado t2','t2.id=t1.lavado_id')
    ->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
    ->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
    ->where('t1.fecha<=',$fechaFinal)
    ->where('t1.fecha>=',$fechaInicial)
    ->where('t1.usuario_id',$usuario)
    ->where('t4.status=',2)
    ->order_by('t1.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function deleteByFolio($folio)
  {
    $this->db->where('corte_folio', $folio);
    $this->db->delete('produccion_proceso_seco');
  }

  public function updateAdministracion($data)
  {
    $this->db->where('carga', $data['carga']);
    $this->db->where('corte_folio', $data['corte_folio']);
    unset($data['carga']);
    unset($data['corte_folio']);
    $this->db->set($data);
    $this->db->update('produccion_proceso_seco');
  }

  public function deleteAdministracion($data)
  {
    $this->db->where($data);
    $this->db->delete('produccion_proceso_seco');
  }

  public function updateById($data)
  {
    $this->db->where('id', $data['id']);
    unset($data['id']);
    $this->db->set($data);
    $this->db->update('produccion_proceso_seco');
  }

  public function deleteById($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('produccion_proceso_seco');
  }

  public function getByFechas($fechaInicial,$fechaFinal)
  {
    $this->db->select('
    t1.razon_pagar as razon,
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo,
    t5.nombre_completo usuario_nombre
    '
    )
    ->from('produccion_proceso_seco as t1')
    ->join('lavado t2','t2.id=t1.lavado_id')
    ->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
    ->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where('t1.fecha<=',$fechaFinal)
    ->where('t1.fecha>=',$fechaInicial)
    ->where('t4.status=',2)
    ->where('t1.estado_nomina',0)
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
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo,
    t5.nombre_completo usuario_nombre'
    )
    ->from('produccion_proceso_seco as t1')
    ->join('lavado t2','t2.id=t1.lavado_id')
    ->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
    ->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where_in('t1.corte_folio', $folios)
    ->where('t4.status=',2)
    ->where('t1.estado_nomina',0)
    ->where('t5.activo',1)
    ->order_by('t5.nombre_completo')
    ->order_by('t1.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function getPendientes()
  {
    $this->db->select('
    t1.razon_pagar as razon,
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo,
    t5.nombre_completo usuario_nombre'
    )
    ->from('produccion_proceso_seco as t1')
    ->join('lavado t2','t2.id=t1.lavado_id')
    ->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
    ->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where('t1.estado_nomina',2)
    ->where('t5.activo',1)
    ->order_by('t5.nombre_completo')
    ->order_by('t1.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function update($data)
  {
    $this->db->where('id',$data['id']);
    unset($data['id']);
    $this->db->set($data)
    ->update('produccion_proceso_seco',$data);
  }

  public function nominaEspecifico($id)
  {
    $this->db->select('
    t1.estado_nomina as estado,
    t1.razon_pagar as razon,
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo'
    )
    ->from('produccion_proceso_seco as t1')
    ->join('lavado t2','t2.id=t1.lavado_id')
    ->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
    ->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
    ->join('usuario t5','t5.id=t1.usuario_id')
    ->where('t1.id_nomina',$id)
    ->order_by('t5.nombre_completo')
    ->order_by('t1.corte_folio')
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
    ->update('produccion_proceso_seco');
  }

  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
    t1.estado_nomina as estado_nomina,
    t1.id_nomina as id_nomina,
    t1.cantidad_pagar as cantidad_pagar,
    t1.razon_pagar as razon_pagar,
    t1.id as id,
    t4.nombre as usuario,
    t4.nombre_completo as nombre_completo,
    t3.id as idlavado,
    t3.nombre as lavado,
    round(t2.costo,2) as costo,
    t5.nombre as proceso,
    t5.id as idproceso,
    t1.corte_folio as folio,
    t1.piezas as piezas,
    t1.defectos as defectos,
    round((t2.costo*t1.piezas),2) as total,
    t1.fecha as fecha')
    ->from('produccion_proceso_seco t1')
    ->join('corte_autorizado_datos t2','t2.lavado_id=t1.lavado_id AND t2.proceso_seco_id=t1.proceso_seco_id AND t2.corte_folio=t1.corte_folio')
    ->join('lavado t3','t1.lavado_id=t3.id')
    ->join('usuario t4','t1.usuario_id=t4.id')
    ->join('proceso_seco t5','t1.proceso_seco_id=t5.id')
    ->where('t1.corte_folio',$folio)
    ->where('t1.estado_nomina',1)
    ->where('t2.costo!=',0);
    return $this->db->get()->result_array();
  }
}
