<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProduccionProcesoSeco extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

 public function seleccionDefinida($usuario,$folio,$carga,$idproceso)
 {
   $query = $this->db->get_where('produccion_proceso_seco',
   array('usuario_id'=>$usuario,  'corte_folio'=>$folio,  'carga'=>$carga,  'proceso_seco_id' =>$idproceso ));
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
     return $this->db->get()->result_array();/*
   $query = $this->db->get_where('produccion_proceso_seco',
   array('usuario_id'=>$usuario,  'corte_folio'=>$folio,  'carga'=>$carga,  'proceso_seco_id' =>$idproceso ));
   return $query->result_array();*/
 }

 public function insertar($data)
 {
   $datos= array('usuario_id'     =>$data['usuarioid'],
                'corte_folio'    =>$data['folio'],
                'carga'          =>$data['carga'],
                'proceso_seco_id'=>$data['proceso'],
                'piezas'         =>$data['piezas'],
                'fecha'          =>date('Y-m-d'),
                'lavado_id'      =>$data['idlavado'],
                'defectos'       =>$data['defectos']);
   $this->db->insert('produccion_proceso_seco',$datos);
 }

 public function editar($data)
 {
   $datos= array('piezas'        =>$data['piezas'],
                 'fecha'         =>date('Y-m-d'),
                 'defectos'       =>$data['defectos']);
   $this->db->where('id',$data['idprod']);
   $this->db->update('produccion_proceso_seco',$datos);
 }

 public function seleccionReporte($folio)
 {
   $this->db->select('
     usuario.nombre as usuario,
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
     return $this->db->get()->result_array();/*
   $query = $this->db->get_where('produccion_proceso_seco',
   array('usuario_id'=>$usuario,  'corte_folio'=>$folio,  'carga'=>$carga,  'proceso_seco_id' =>$idproceso ));
   return $query->result_array();*/
 }

}
