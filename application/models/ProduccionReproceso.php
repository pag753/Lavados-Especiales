<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProduccionReproceso extends CI_Model
{
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

  /*public function editar($data)
  {
    $datos= array(

    );
    $this->db->where('id',$data['idprod']);
    $this->db->update('produccion_reproceso',$datos);
  }

  public function deleteByFolio($folio)
  {
  $this->db->where('corte_folio', $folio);
  $this->db->delete('produccion_reproceso');
}

public function updateAdministracion($data)
{
$this->db->where('carga', $data['carga']);
$this->db->where('corte_folio', $data['corte_folio']);
unset($data['carga']);
unset($data['corte_folio']);
$this->db->set($data);
$this->db->update('produccion_reproceso');
}

public function deleteAdministracion($data)
{
$this->db->where($data);
$this->db->delete('produccion_reproceso');
}

public function updateById($data)
{
$this->db->where('id', $data['id']);
unset($data['id']);
$this->db->set($data);
$this->db->update('produccion_reproceso');
}

public function deleteById($id)
{
$this->db->where('id', $id);
$this->db->delete('produccion_reproceso');
}

public function getByFechas($fechaInicial,$fechaFinal)
{
$this->db->select('
t1.usuario_id as id,
t1.piezas as piezas,
t4.costo as precio,
TRUNCATE((t1.piezas*t4.costo),2) as costo'
)
->from('produccion_reproceso as t1')
->join('lavado t2','t2.id=t1.lavado_id')
->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
->where('t1.fecha<=',$fechaFinal)
->where('t1.fecha>=',$fechaInicial)
->where('t4.status=',2)
->order_by('t1.corte_folio')
//->group_by('t1.usuario_id')
->order_by('t2.nombre')
->order_by('t3.nombre');
return $this->db->get()->result_array();
}

public function getByFolios($folios)
{
$this->db->select('
t1.usuario_id as id,
t1.piezas as piezas,
t4.costo as precio,
TRUNCATE((t1.piezas*t4.costo),2) as costo'
)
->from('produccion_reproceso as t1')
->join('lavado t2','t2.id=t1.lavado_id')
->join('proceso_seco t3','t3.id=t1.proceso_seco_id')
->join('corte_autorizado_datos t4','t4.lavado_id=t1.lavado_id AND t4.proceso_seco_id=t1.proceso_seco_id AND t4.corte_folio=t1.corte_folio')
->where_in('t1.corte_folio', $folios)
->where('t4.status=',2)
->order_by('t1.corte_folio')
->order_by('t2.nombre')
->order_by('t3.nombre');
return $this->db->get()->result_array();
}*/
}
