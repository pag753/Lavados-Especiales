<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +---------------------+---------+------+-----+---------+-------+
* | Field               | Type    | Null | Key | Default | Extra |
* +---------------------+---------+------+-----+---------+-------+
* | piezas              | int(11) | NO   |     | NULL    |       |
* | corte_autorizado_id | int(11) | NO   | MUL | NULL    |       |
* +---------------------+---------+------+-----+---------+-------+
*/

class SalidaInterna1Datos extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getByFolio($folio = null)
  {
    $query = $this->db->get_where('salida_interna1_datos', array(
      'corte_folio' => $folio
    ));
    return $query->result_array();
  }

  public function agregar($datos = null)
  {
    $data = $this->db->insert('salida_interna1_datos', $datos);
    return $data;
  }

  //Cambiado por base de datos
  public function deleteByFolio($folio)
  {
    $sql = "DELETE salida_interna1_datos FROM salida_interna1_datos INNER JOIN corte_autorizado ON corte_autorizado.id=salida_interna1_datos.corte_autorizado_id WHERE corte_autorizado.corte_folio =  $folio";
    $this->db->query($sql);
    /*
    $this->db->query($sql);
    $this->db->from('salida_interna1_datos')
    ->where('corte_autorizado.corte_folio', $folio)
    ->join('corte_autorizado','corte_autorizado.id=salida_interna1_datos.corte_autorizado_id','inner')
    ->delete('salida_interna1_datos');
    */
  }

  //Modificado por cambios en base de datos
  public function getByFolioEspecifico($folio = null)
  {
    $this->db
    ->select('
    corte_autorizado.id as id,
    corte_autorizado.id_carga as id_carga,
    corte_autorizado.lavado_id as lavado_id,
    salida_interna1_datos.piezas as piezas')
    ->from('corte_autorizado')
    //->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('salida_interna1_datos', 'salida_interna1_datos.corte_autorizado_id=corte_autorizado.id')
    ->where('corte_autorizado.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  public function getByFolioEspecifico2($folio = null)
  {
    $this->db->distinct()
    ->select('
    corte_autorizado.id_carga as id_carga,
    corte_autorizado.lavado_id as lavado_id,
    lavado.nombre as lavado,
    salida_interna1_datos.piezas as piezas')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('salida_interna1_datos','salida_interna1_datos.corte_autorizado_id=corte_autorizado.id')
    //->join('salida_interna1_datos', 'salida_interna1_datos.id_carga=corte_autorizado_datos.id_carga and salida_interna1_datos.corte_folio=corte_autorizado_datos.corte_folio')
    ->join('lavado', 'lavado.id=corte_autorizado.lavado_id')
    ->where('corte_autorizado.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  //Cambiado por base de datos
  public function updateAdministracion($data)
  {
    $this->db->where('corte_autorizado_id', $data['corte_autorizado_id']);
    //  $this->db->where('corte_folio', $data['corte_folio']);
    //  unset($data['id_carga']);
    unset($data['corte_autorizado_id']);
    $this->db->set($data)
    ->update('salida_interna1_datos');
  }

  public function deleteAdministracion($data)
  {
    $this->db->where($data);
    $this->db->delete('salida_interna1_datos');
  }
}
