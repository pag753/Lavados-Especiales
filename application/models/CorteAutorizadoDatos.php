<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +---------------------+-------------+------+-----+---------+----------------+
* | Field               | Type        | Null | Key | Default | Extra          |
* +---------------------+-------------+------+-----+---------+----------------+
* | id                  | int(11)     | NO   | PRI | NULL    | auto_increment |
* | corte_autorizado_id | int(11)     | NO   | MUL | NULL    |                |
* | proceso_seco_id     | int(11)     | NO   | MUL | NULL    |                |
* | costo               | float       | NO   |     | NULL    |                |
* | piezas_trabajadas   | varchar(45) | NO   |     | 0       |                |
* | defectos            | varchar(45) | NO   |     | 0       |                |
* | status              | varchar(45) | NO   |     | 0       |                |
* | orden               | int(11)     | NO   |     | 0       |                |
* | fecha_registro      | date        | NO   |     | NULL    |                |
* | usuario_id          | int(11)     | NO   | MUL | 6       |                |
* +---------------------+-------------+------+-----+---------+----------------+
*/

class CorteAutorizadoDatos extends CI_Model
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

  //Cambios por base de datos
  public function getByFolio($folio = null)
  {
    $query = $this->db->select('
     corte_autorizado_datos.id,
     corte_autorizado_datos.corte_autorizado_id,
     corte_autorizado_datos.proceso_seco_id,
     corte_autorizado_datos.costo,
     corte_autorizado_datos.piezas_trabajadas,
     corte_autorizado_datos.defectos,
     corte_autorizado_datos.status,
     corte_autorizado_datos.orden,
     corte_autorizado_datos.fecha_registro,
     corte_autorizado_datos.usuario_id,
     corte_autorizado.corte_folio,
     corte_autorizado.fecha_autorizado,
     corte_autorizado.id_carga,
     corte_autorizado.lavado_id,
     corte_autorizado.usuario_id,
     corte_autorizado.color_hilo,
     corte_autorizado.tipo
    ')
    ->from('corte_autorizado_datos')
//    ->join('proceso_seco','proceso_seco.id=corte_autorizado_datos.proceso_seco_id')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
//    ->join('lavado','corte_autorizado.lavado_id=lavado.id')
    ->where('corte_autorizado.corte_folio',$folio);
    return $query->get()->result_array();
  }

  //Cambiado por los cambios en la base de datos
  public function getByFolioEspecifico($folio)
  {
    $this->db->select('
    corte_autorizado.id as id_corte_autorizado,
    corte_autorizado_datos.id as id_corte_autorizado_datos,
    corte_autorizado.id_carga as idcarga,
    lavado.nombre as lavado,
    lavado.id as idlavado,
    proceso_seco.nombre as proceso,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.defectos as defectos,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.orden as orden,
    corte_autorizado_datos.fecha_registro as fecha,
    usuario.nombre as usuario
    ')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'corte_autorizado.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->order_by('corte_autorizado.id_carga, corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  //Cambios por base de datos
  public function getByFolioEspecifico2($folio)
  {
    $this->db->select('
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo,
    corte_autorizado.id as id_corte_autorizado,
    corte_autorizado_datos.id as id_corte_autorizado_datos,
    corte_autorizado.id_carga as idcarga,
    lavado.nombre as lavado,
    lavado.id as idlavado,
    proceso_seco.nombre as proceso,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.defectos as defectos,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.orden as orden,
    corte_autorizado_datos.fecha_registro as fecha,
    usuario.nombre as usuario
    ')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'corte_autorizado.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->join('entrega_almacen','entrega_almacen.corte_autorizado_id=corte_autorizado.id','left')
    ->where('corte_autorizado.corte_folio', $folio)
    ->where('entrega_almacen.corte_autorizado_id=',NULL)
    ->order_by('corte_autorizado.id_carga, corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  //Cambios por base de datos
  public function getByFolioEspecifico3($folio)
  {
    $this->db->select('
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo,
    corte_autorizado.id as id_corte_autorizado,
    corte_autorizado.id_carga as idcarga,
    lavado.nombre as lavado,
    lavado.id as idlavado,
    usuario.nombre as usuario
    ')
    ->from('entrega_almacen')
    ->join('corte_autorizado','corte_autorizado.id=entrega_almacen.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'corte_autorizado.usuario_id=usuario.id')
    ->join('entrega_externa','entrega_externa.corte_autorizado_id=corte_autorizado.id','left')
    ->where('corte_autorizado.corte_folio', $folio)
    ->where('entrega_externa.corte_autorizado_id=',NULL)
    ->order_by('corte_autorizado.id_carga');
    return $this->db->get()->result_array();
  }

  public function agregar($datos = null)
  {
    $datos['usuario_id'] = $_SESSION['usuario_id'];
    $data = $this->db->insert('corte_autorizado_datos', $datos);
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
    ->join('lavado', 'corte_autorizado_datos.lavado_id=lavado.id')
    ->where('corte_autorizado_datos.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  //Cambiado por los cambios a la base de datos
  public function joinLavadoProcesos($folio)
  {
    $this->db->select('
    corte_autorizado.id as id,
    corte_autorizado.id_carga,
    lavado.id as idlavado,
    lavado.nombre as lavado
    ')
    ->from('corte_autorizado')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    //->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->order_by('corte_autorizado.id_carga');
    // ->group_by("corte_autorizado_datos.id_carga");
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCarga($folio, $carga)
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
    ->join('lavado', 'corte_autorizado_datos.lavado_id=lavado.id')
    ->join('usuario', 'corte_autorizado_datos.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio', $folio)
    ->where('corte_autorizado_datos.id_carga', $carga)
    ->order_by('corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros($folio, $carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado', 'corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio', $folio)
    ->where('corte_autorizado_datos.costo !=', 0)
    ->where('corte_autorizado_datos.id_carga', $carga);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros2($id)
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
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.id', $id)
    ->where('corte_autorizado_datos.costo !=', 0);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros3($folio, $carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado', 'corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio', $folio)
    ->where('corte_autorizado_datos.costo !=', 0)
    ->where('corte_autorizado_datos.id_carga', $carga)
    ->where('corte_autorizado_datos.status', 1);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros4($folio, $carga)
  {
    $this->db->select('
    corte_autorizado_datos.id as id,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->where('corte_autorizado_datos.costo !=', 0)
    ->where('corte_autorizado.id_carga', $carga)
    ->where('corte_autorizado_datos.status', 0);
    return $this->db->get()->result_array();
  }

  public function joinLavadoProcesosCargaNoCeros5($folio, $carga)
  {
    $this->db->select('
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.abreviatura as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado_datos.costo as costo
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado', 'corte_autorizado_datos.lavado_id=lavado.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.corte_folio', $folio)
    ->where('corte_autorizado_datos.costo !=', 0)
    ->where('corte_autorizado_datos.id_carga', $carga);
    return $this->db->get()->result_array();
  }

  //Cambiado por los cambios en la base de datos
  public function joinLavadoProcesosCargaNoCeros6($folio)
  {
    $this->db->select('
    corte_autorizado_datos.id as id,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    corte_autorizado_datos.costo as costo,
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo,
    corte_autorizado.id_carga as id_carga
    ')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado_datos.corte_autorizado_id=corte_autorizado.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->where('corte_autorizado_datos.costo !=', 0)
    ->where('corte_autorizado_datos.status', 1);
    return $this->db->get()->result_array();
  }

  public function actualizaCosto($folio, $carga, $proceso, $costo, $lavado)
  {
    $query = $this->db->where('corte_folio', $folio)
    ->where('id_carga', $carga)
    ->where('proceso_seco_id', $proceso)
    ->where('lavado_id', $lavado)
    ->update('corte_autorizado_datos', array(
      'costo' => $costo
    ));
    return $query;
  }

  //Se cambió cuando se cambió la base de datos
  public function actualiza($id, $piezas, $orden)
  {
    $data = array(
      'piezas_trabajadas' => $piezas,
      'status' => 1,
      'orden' => $orden
    );
    $this->db->where('corte_autorizado_datos.id', $id);
    $this->db->update('corte_autorizado_datos', $data);
  }

  public function actualiza2($id, $trabajadas, $defectos, $fecha, $usuario)
  {
    $data = array(
      'piezas_trabajadas' => $trabajadas,
      'status' => 2,
      'fecha_registro' => $fecha,
      'defectos' => $defectos,
      'usuario_id' => $usuario
    );
    $this->db->where('id', $id);
    $this->db->update('corte_autorizado_datos', $data);
  }

  public function reporte($folio)
  {
    $this->db->select('
    usuario.nombre as usuario,
    usuario.nombre_completo as usuarioc,
    lavado.id as idlavado,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    proceso_seco.id as idproceso,
    corte_autorizado.corte_folio as folio,
    TRUNCATE(corte_autorizado_datos.costo,2) as costo,
    corte_autorizado_datos.status as status,
    corte_autorizado_datos.piezas_trabajadas as piezas,
    corte_autorizado_datos.orden as orden,
    corte_autorizado.id_carga as idcarga,
    corte_autorizado_datos.fecha_registro as fecha,
    Round((corte_autorizado_datos.costo*corte_autorizado_datos.piezas_trabajadas),2) as total,
    corte_autorizado_datos.defectos as defectos')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id=lavado.id')
    ->join('usuario', 'corte_autorizado_datos.usuario_id=usuario.id')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->order_by('corte_autorizado.id_carga, corte_autorizado.lavado_id, corte_autorizado_datos.orden');
    return $this->db->get()->result_array();
  }

  public function getByFolioStatus2($folio = null)
  {
    $data = array(
      'corte_folio' => $folio,
      'status!=' => 2
    );
    $query = $this->db->get_where('corte_autorizado_datos', $data);
    return $query->result_array();
  }

  public function deleteByFolio($folio)
  {
    $this->db->where('corte_folio', $folio);
    $this->db->delete('corte_autorizado_datos');
  }

  public function update($data)
  {
    $this->db->where('id', $data['id']);
    unset($data['id']);
    $data['usuario_id'] = $_SESSION['usuario_id'];
    $this->db->set($data);
    $this->db->update('corte_autorizado_datos');
  }

  public function deleteByID($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('corte_autorizado_datos');
  }

  public function updateAdministracion($data)
  {
    $this->db->where('id_carga', $data['id_carga']);
    $this->db->where('corte_folio', $data['corte_folio']);
    unset($data['id_carga']);
    unset($data['corte_folio']);
    $this->db->set($data);
    $this->db->update('corte_autorizado_datos');
  }

  public function deleteAdministracion($data)
  {
    $this->db->where($data);
    $this->db->delete('corte_autorizado_datos');
  }

  public function getLavadosByFolio($folio)
  {
    $this->db->distinct()
    ->select('
    corte_autorizado.id as id,
    corte_autorizado.color_hilo as color_hilo,
    corte_autorizado.tipo as tipo,
    corte_autorizado.id_carga,
    corte_autorizado.lavado_id,
    lavado.nombre
    ')
    ->from('corte_autorizado')
    //->join('corte_autorizado','corte_autorizado_datos.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('lavado', 'corte_autorizado.lavado_id = lavado.id')
    ->where('corte_autorizado.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  public function getCargaMaxima($folio)
  {
    $this->db->distinct()
    ->select('MAX(id_carga) as maxima')
    ->from('corte_autorizado')
    ->where('corte_autorizado.corte_folio', $folio);
    return $this->db->get()->result_array();
  }

  public function getProcesoActivo($folio, $lavado)
  {
    $this->db->select('proceso_seco.nombre as proceso')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->join('proceso_seco', 'proceso_seco.id=corte_autorizado_datos.proceso_seco_id')
    ->where('corte_autorizado.corte_folio', $folio)
    ->where('corte_autorizado.lavado_id', $lavado)
    ->where('corte_autorizado_datos.status', 1);
    return $this->db->get()->result_array();
  }

  //QUERYS DESPUÉS DE LOS CAMBIOS A LA BASE DE DATOS
  public function getProcesosSinCeros($id)
  {
    $this->db->select('
    corte_autorizado_datos.id as id,
    proceso_seco.nombre as proceso,
    ')
    ->from('corte_autorizado_datos')
    ->join('proceso_seco', 'corte_autorizado_datos.proceso_seco_id=proceso_seco.id')
    ->where('corte_autorizado_datos.costo !=', 0)
    ->where('corte_autorizado_datos.corte_autorizado_id', $id);
    return $this->db->get()->result_array();
  }

  public function procesosAbiertos($folio=null)
  {
    $this->db->select('*')
    ->from('corte_autorizado_datos')
    ->join('corte_autorizado','corte_autorizado.id=corte_autorizado_datos.corte_autorizado_id')
    ->where('corte_autorizado.corte_folio',$folio)
    ->where('corte_autorizado_datos.status',1);
    return $this->db->get()->result_array();
  }

  public function procesosActivos($idCorteAutorizado)
  {
    $this->db->select('
    corte_autorizado_datos.id,
    proceso_seco.nombre
    ')
    ->from('corte_autorizado_datos')
    ->join('proceso_seco','proceso_seco.id=corte_autorizado_datos.proceso_seco_id')
    ->where('corte_autorizado_datos.corte_autorizado_id',$idCorteAutorizado);
    return $this->db->get()->result_array();
  }
}
