<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProduccionProcesoSeco extends CI_Model
{

  /*
  * estado_nomina:{
  * 0: NO Pagado
  * 1: Pagado
  * 2: Pendiente
  * 3: No se pagará
  * }
  */
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function seleccionDefinida($id)
  {
    $query = $this->db->get_where('produccion_proceso_seco', array(
      'usuario_id' => $_SESSION['usuario_id'],
      'corte_autorizado_datos_id' => $id
    ));
    return $query->result_array();
  }

  public function getByFolio($folio)
  {
    $query = $this->db->get_where('produccion_proceso_seco', array(
      'corte_folio' => $folio
    ));
    return $query->result_array();
  }

  public function seleccionDefinida2($usuario, $folio, $carga)
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
    ->join('lavado', 'produccion_proceso_seco.lavado_id=lavado.id')
    ->join('usuario', 'produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco', 'produccion_proceso_seco.proceso_seco_id=proceso_seco.id')
    ->where('produccion_proceso_seco.corte_folio', $folio)
    ->where('produccion_proceso_seco.carga', $carga);
    return $this->db->get()->result_array();
  }

  public function seleccionDefinida3($usuario, $folio, $carga, $proceso)
  {
    $this->db->select('
    usuario.nombre as usuario,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado.corte_folio as folio,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.defectos as defectos,
    produccion_proceso_seco.fecha as fecha')
    ->from('produccion_proceso_seco')
    ->join('corte_autorizado_datos','corte_autorizado_datos.id=produccion_proceso_seco.corte_autorizado_datos_id')
    ->join('corte_autorizado','corte_autorizado_datos.corte_autorizado_id=corte_autorizado.id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->where('corte_autorizado.id_carga', $carga)
    ->where('corte_autorizado_datos.proceso_seco_id', $proceso);
    return $this->db->get()->result_array();
  }

  public function insertar($data)
  {
    $datos = array(
      'usuario_id' => $data['usuario_id'],
      'corte_autorizado_datos_id' => $data['id'],
      'piezas' => $data['piezas'],
      'defectos' => $data['defectos'],
      'fecha' => date('Y-m-d'),
    );
    $this->db->insert('produccion_proceso_seco', $datos);
  }

  public function editar($data)
  {
    $datos = array(
      'piezas' => $data['piezas'],
      'fecha' => date('Y-m-d'),
      'defectos' => $data['defectos']
    );
    $this->db->where('corte_autorizado_datos_id', $data['id'])
    ->where('usuario_id',$data['usuario_id'])
    ->update('produccion_proceso_seco', $datos);
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
    corte_autorizado.corte_folio as folio,
    corte_autorizado.id_carga as id_carga,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.defectos as defectos,
    round((proceso_seco.costo*produccion_proceso_seco.piezas),2) as total,
    produccion_proceso_seco.fecha as fecha')
    ->from('produccion_proceso_seco')
    ->join('corte_autorizado','corte_autorizado.id=produccion_proceso_seco.corte_autorizado_datos_id')
    ->join('corte_autorizado_datos','corte_autorizado_datos.id=produccion_proceso_seco.corte_autorizado_datos_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  // Consulta para ver la producción de los usuarios de los cortes con cargas cerradas
  public function verProduccion($usuario, $fechaInicial, $fechaFinal)
  {
    $this->db->select('
    t5.id_carga as carga,
    t1.fecha as fecha,
    t5.corte_folio as folio,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t1.piezas as piezas,
    t4.costo as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo')
    ->from('produccion_proceso_seco as t1')
    ->join('corte_autorizado_datos t4','t4.id=t1.corte_autorizado_datos_id')
    ->join('corte_autorizado t5','t5.id=t4.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t5.lavado_id')
    ->join('proceso_seco t3', 't3.id=t4.proceso_seco_id')
    ->where('t1.fecha<=', $fechaFinal)
    ->where('t1.fecha>=', $fechaInicial)
    ->where('t1.usuario_id', $usuario)
    ->where('t4.status=', 2)
    ->order_by('t5.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  //Cambios por base de datos
  public function deleteByFolio($folio)
  {
    $sql = "DELETE produccion_proceso_seco FROM produccion_proceso_seco INNER JOIN corte_autorizado_datos on corte_autorizado_datos.id = produccion_proceso_seco.corte_autorizado_datos_id INNER JOIN corte_autorizado on corte_autorizado.id = corte_autorizado_datos.corte_autorizado_id WHERE corte_autorizado.corte_folio = $folio";
    $this->db->query($sql);
    /*
    $this->db->from('produccion_proceso_seco')
    ->where('corte_autorizado.corte_folio', $folio)
    ->join('corte_autorizado_datos','corte_autorizado_datos.id=produccion_proceso_seco.corte_autorizado_datos_id','inner')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_datos','inner')
    ->delete('produccion_proceso_seco');
    */
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

  public function getByFechas($fechaInicial, $fechaFinal)
  {
    $this->db->select('
    t7.id_carga as carga,
    t1.razon_pagar as razon,
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t7.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t6.costo,2) as precio,
    TRUNCATE((t1.piezas*t6.costo),2) as costo,
    t5.nombre_completo usuario_nombre')
    ->from('produccion_proceso_seco as t1')
    ->join('corte_autorizado_datos t6','t6.id=t1.corte_autorizado_datos_id')
    ->join('corte_autorizado t7','t7.id=t6.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t7.lavado_id')
    ->join('proceso_seco t3', 't3.id=t6.proceso_seco_id')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where('t1.fecha<=', $fechaFinal)
    ->where('t1.fecha>=', $fechaInicial)
    ->where('t6.status=', 2)
    ->where('t6.costo>', 0)
    ->where('t1.estado_nomina', 0)
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
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t7.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t6.costo,2) as precio,
    TRUNCATE((t1.piezas*t6.costo),2) as costo,
    t5.nombre_completo usuario_nombre')
    ->from('produccion_proceso_seco as t1')
    ->join('corte_autorizado_datos t6','t6.id=t1.corte_autorizado_datos_id')
    ->join('corte_autorizado t7','t7.id=t6.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t7.lavado_id')
    ->join('proceso_seco t3', 't3.id=t6.proceso_seco_id')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where_in('t7.corte_folio', $folios)
    ->where('t6.status=', 2)
    ->where('t6.costo>', 0)
    ->where('t1.estado_nomina', 0)
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
    t7.id_carga as carga,
    t1.razon_pagar as razon,
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t7.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t6.costo,2) as precio,
    TRUNCATE((t1.piezas*t6.costo),2) as costo,
    t5.nombre_completo usuario_nombre')
    ->from('produccion_proceso_seco as t1')
    ->join('corte_autorizado_datos t6','t6.id=t1.corte_autorizado_datos_id')
    ->join('corte_autorizado t7','t7.id=t6.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t7.lavado_id')
    ->join('proceso_seco t3', 't3.id=t6.proceso_seco_id')
    //->join('corte_autorizado_datos t4', 't7.lavado_id=t7.lavado_id AND t4.proceso_seco_id=t6.proceso_seco_id AND t7.corte_folio=t7.corte_folio')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where('t1.estado_nomina', 2)
    ->where('t5.activo', 1)
    ->order_by('t5.nombre_completo')
    ->order_by('t7.corte_folio')
    ->order_by('t2.nombre')
    ->order_by('t3.nombre');
    return $this->db->get()->result_array();
  }

  public function update($data)
  {
    $this->db->where('id', $data['id']);
    unset($data['id']);
    $this->db->set($data)->update('produccion_proceso_seco', $data);
  }

  public function nominaEspecifico($id)
  {
    $this->db->select('
    t6.id_carga as carga,
    t1.estado_nomina as estado,
    t1.razon_pagar as razon,
    t5.nombre_completo as usuario_nombre,
    t2.nombre as lavado,
    t3.nombre as proceso,
    t6.corte_folio as folio,
    t1.id as id_produccion,
    t1.usuario_id as id,
    t1.piezas as piezas,
    TRUNCATE(t4.costo,2) as precio,
    TRUNCATE((t1.piezas*t4.costo),2) as costo')
    ->from('produccion_proceso_seco as t1')
    ->join('corte_autorizado_datos t4','t1.corte_autorizado_datos_id=t4.id')
    ->join('corte_autorizado t6','t6.id=t4.corte_autorizado_id')
    ->join('lavado t2', 't2.id=t6.lavado_id')
    ->join('proceso_seco t3', 't3.id=t4.proceso_seco_id')
    //->join('corte_autorizado_datos t4', 't4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
    ->join('usuario t5', 't5.id=t1.usuario_id')
    ->where('t1.id_nomina', $id)
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
    ->update('produccion_proceso_seco');
  }

  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
    t6.id_carga as carga,
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
    t6.corte_folio as folio,
    t1.piezas as piezas,
    t1.defectos as defectos,
    round((t2.costo*t1.piezas),2) as total,
    t1.fecha as fecha')
    ->from('produccion_proceso_seco t1')
    ->join('corte_autorizado_datos t2','t2.id=t1.corte_autorizado_datos_id')
    ->join('corte_autorizado t6','t6.id=t2.corte_autorizado_id')
    ->join('lavado t3', 't6.lavado_id=t3.id')
    ->join('usuario t4', 't1.usuario_id=t4.id')
    ->join('proceso_seco t5', 't2.proceso_seco_id=t5.id')
    ->where('t6.corte_folio', $folio)
    ->where('t1.estado_nomina', 1)
    ->where('t2.costo!=', 0);
    return $this->db->get()->result_array();
  }

  public function getNominasByOperario($id)
  {
    $this->db->distinct()
    ->select('
    produccion_proceso_seco.id_nomina as id_nomina,
    nomina.fecha as fecha
    ')
    ->from('produccion_proceso_seco')
    ->join('nomina', 'nomina.id= produccion_proceso_seco.id_nomina')
    ->where('nomina.usuario_id', $id);
    return $this->db->get()->result_array();
  }

  public function getWhereEspecifico($data)
  {
    $this->db->select('
    produccion_proceso_seco.corte_folio as folio,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.fecha as fecha,
    produccion_proceso_seco.defectos as defectos,
    produccion_proceso_seco.estado_nomina as estado,
    produccion_proceso_seco.cantidad_pagar as cantidad_pagar,
    produccion_proceso_seco.razon_pagar as razon_pagar,
    proceso_seco.nombre as proceso,
    lavado.nombre as lavado,
    ')
    ->from('produccion_proceso_seco')
    ->join('lavado', 'lavado.id=produccion_proceso_seco.lavado_id')
    ->join('proceso_seco', 'proceso_seco.id=produccion_proceso_seco.proceso_seco_id')
    ->where('produccion_proceso_seco.usuario_id', $data['usuario_id'])
    ->where('produccion_proceso_seco.id_nomina', $data['id_nomina']);
    return $this->db->get()->result_array();
  }

  //Agregados de los cambios a la base de Datospublic function seleccionDefinida3($usuario, $folio, $carga, $proceso)
  public function seleccionDefinidaPorProceso($id)
  {
    $this->db->select('
    usuario.nombre as usuario,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado.corte_folio as folio,
    produccion_proceso_seco.piezas as piezas,
    produccion_proceso_seco.defectos as defectos,
    produccion_proceso_seco.fecha as fecha')
    ->from('produccion_proceso_seco')
    ->join('corte_autorizado_datos','corte_autorizado_datos.id=produccion_proceso_seco.corte_autorizado_datos_id')
    ->join('corte_autorizado','corte_autorizado_datos.corte_autorizado_id=corte_autorizado.id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'produccion_proceso_seco.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('produccion_proceso_seco.corte_autorizado_datos_id', $id);
    return $this->db->get()->result_array();
  }
}
