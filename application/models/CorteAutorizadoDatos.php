<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CorteAutorizadoDatos extends CI_Model
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

  public function getByFolio($folio=null)
  {
    $query = $this->db->get_where('corte_autorizado_datos', array('corte_folio' => $folio));
    return $query->result_array();
  }

  public function getByFolioEspecifico($folio=null)
  {
    $this->db->select('
    corte_autorizado_datos.id_carga as idcarga,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.defectos as defectos,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.orden as orden,
    corte_autorizado_datos.fecha_registro as fecha,
    usuario.nombre as usuario,
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('usuario','corte_autorizado_datos.usuario_id=usuario.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->order_by('corte_autorizado_datos.id_carga, corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  public function agregar($datos=null)
  {
    $data=$this->db->insert('corte_autorizado_datos',$datos);
    return $data;
  }

  public function joinLavado($folio)
  {
    $this->db->distinct()
    ->select('
    corte_autorizado_datos.id_carga,
    corte_autorizado_datos.lavado_id,
    lavado.id,
    lavado.nombre
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->where('corte_autorizado_datos.corte_folio',$folio);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesos($folio)
  {
    $this->db->distinct()
    ->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio);
    //->group_by("corte_autorizado_datos.id_carga");
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCarga($folio,$carga)
  {
    $this->db->select('
    usuario.nombre as usuario,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.corte_folio as folio,
    corte_autorizado_datos.costo as costo,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.orden as orden,
    corte_autorizado_datos.fecha_registro as fecha,
    Round((corte_autorizado_datos.costo*corte_autorizado_datos.piezas_trabajadas),2) as total,
    corte_autorizado_datos.defectos as defectos')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('usuario','corte_autorizado_datos.usuario_id=usuario.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->where('corte_autorizado_datos.id_carga',$carga)
    ->order_by('corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros($folio,$carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->where('corte_autorizado_datos.costo !=',0)
    ->where('corte_autorizado_datos.id_carga',$carga);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros2($folio,$carga,$proceso)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.orden as orden
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->where('corte_autorizado_datos.costo !=',0)
    ->where('corte_autorizado_datos.id_carga',$carga)
    ->where('corte_autorizado_datos.proceso_seco_id',$proceso);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros3($folio,$carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->where('corte_autorizado_datos.costo !=',0)
    ->where('corte_autorizado_datos.id_carga',$carga)
    ->where('corte_autorizado_datos.status',1);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros4($folio,$carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->where('corte_autorizado_datos.costo !=',0)
    ->where('corte_autorizado_datos.id_carga',$carga)
    ->where('corte_autorizado_datos.status',0);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros5($folio,$carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.abreviatura as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->where('corte_autorizado_datos.costo !=',0)
    ->where('corte_autorizado_datos.id_carga',$carga);
    return $this->db->get()->result_array();
  }

  public function actualizaCosto($folio,$carga,$proceso,$costo,$lavado)
  {
    $query=$this->db->where('corte_folio',$folio)
    ->where('id_carga',$carga)
    ->where('proceso_seco_id',$proceso)
    ->where('lavado_id',$lavado)
    ->update('corte_autorizado_datos', array('costo'=>$costo));
    return $query;
  }

  public function actualiza($id_proceso,$folio,$id_carga,$inicio,$orden)
  {
    $data = array(
      'piezas_trabajadas' => $inicio,
      'status' => 1,
      'orden' => $orden
    );
    $this->db->where('proceso_seco_id', $id_proceso);
    $this->db->where('corte_folio', $folio);
    $this->db->where('id_carga', $id_carga);
    $this->db->update('corte_autorizado_datos', $data);
  }

  public function actualiza2($id_proceso,$folio,$id_carga,$trabajadas,$defectos,$fecha,$usuario)
  {
    $data = array(
      'piezas_trabajadas' => $trabajadas,
      'status' => 2,
      'fecha_registro'=>$fecha,
      'defectos'=>$defectos,
      'usuario_id'=>$usuario
    );
    $this->db->where('proceso_seco_id', $id_proceso);
    $this->db->where('corte_folio', $folio);
    $this->db->where('id_carga', $id_carga);
    $this->db->update('corte_autorizado_datos', $data);
  }


  public function reporte($folio)
  {
    $this->db->select('
    usuario.nombre as usuario,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.corte_folio as folio,
    corte_autorizado_datos.costo as costo,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.orden as orden,
    corte_autorizado_datos.id_carga as idcarga,
    corte_autorizado_datos.fecha_registro as fecha,
    Round((corte_autorizado_datos.costo*corte_autorizado_datos.piezas_trabajadas),2) as total,
    corte_autorizado_datos.defectos as defectos')
    ->from('corte_autorizado_datos')
    ->join('lavado','corte_autorizado_datos.lavado_id=lavado.id')
    ->join('usuario','corte_autorizado_datos.usuario_id=usuario.id')
    ->join('proceso_seco','corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio',$folio)
    ->order_by('corte_autorizado_datos.id_carga, corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  public function getByFolioStatus2($folio=null)
  {
    $data = array(
      'corte_folio' => $folio,
      'status!='=>2,
    );
    $query = $this->db->get_where('corte_autorizado_datos',$data);
    return $query->result_array();
  }
}
