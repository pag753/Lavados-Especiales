<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Corte extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $query = $this->db->get("corte");
    return $query->result_array();
  }

  public function agregar($datos = null)
  {
    $data = array(
      'folio' => $datos['folio'],
      'fecha_entrada' => substr($datos['fecha'], 0, 10),
      'corte' => $datos['corte'],
      'marca_id' => $datos['marca'],
      'maquilero_id' => $datos['maquilero'],
      'cliente_id' => $datos['cliente'],
      'tipo_pantalon_id' => $datos['tipo'],
      'piezas' => $datos['piezas'],
      'ojales' => $datos['cantidadOjales']
    );
    if ($data['marca_id'] == 0)
    unset($data['marca_id']);
    $this->db->insert('corte', $data);
    return $data;
  }

  public function getByFolio($folio)
  {
    $query = $this->db->get_where('corte', array(
      'folio' => $folio
    ));
    return $query->result_array();
  }

  // Método que recupera los datos generales del corte
  public function getByFolioGeneral($folio)
  {
    $this->db->select('
    corte.folio as folio,
    corte.corte as corte,
    marca.nombre as marca,
    maquilero.nombre as maquilero,
    cliente.nombre as cliente,
    tipo_pantalon.nombre as tipo,
    corte.piezas as piezas,
    corte.fecha_entrada as fecha,
    corte.ojales as ojales
    ')
    ->from('corte')
    ->join('marca', 'corte.marca_id=marca.id', 'left')
    ->join('maquilero', 'corte.maquilero_id=maquilero.id')
    ->join('cliente', 'corte.cliente_id=cliente.id')
    ->join('tipo_pantalon', 'corte.tipo_pantalon_id=tipo_pantalon.id')
    ->where('corte.folio', $folio);
    return $this->db->get()->result_array();
  }

  // Modificar corte
  public function update($data)
  {
    $this->db->where('folio', $data['folio']);
    unset($data['folio']);
    $this->db->update('corte', $data);
  }

  // Reportes
  public function reporte1()
  {
    // Cortes en almacen
    $this->db->select('
    corte.folio as folio,
    corte.corte as corte,
    marca.nombre as marca,
    maquilero.nombre as maquilero,
    cliente.nombre as cliente,
    tipo_pantalon.nombre as tipo,
    corte.piezas as piezas,
    corte.fecha_entrada as fecha,
    corte.ojales as ojales
    ')
    ->from('corte')
    ->join('marca', 'corte.marca_id=marca.id', 'left')
    ->join('maquilero', 'corte.maquilero_id=maquilero.id')
    ->join('cliente', 'corte.cliente_id=cliente.id')
    ->join('tipo_pantalon', 'corte.tipo_pantalon_id=tipo_pantalon.id')
    ->join('salida_interna1','salida_interna1.corte_folio=corte.folio','left')
    ->where('salida_interna1.corte_folio = ',NULL)
    ->order_by('corte.folio', 'ASC');
    return $this->db->get()->result_array();
  }

  public function reporte2()
  {
    //select * from corte_autorizado_datos left join salida_interna1_datos on corte_autorizado_datos.corte_folio=salida_interna1_datos.corte_folio where salida_interna1_datos.corte_folio  is null;
    $this->db->select('
    corte_autorizado_datos.corte_folio as folio,
    lavado.nombre as lavado,
    proceso_seco.nombre as proceso,
    ')
    ->from('corte_autorizado_datos')
    ->join('lavado','lavado.id=corte_autorizado_datos.lavado_id')
    ->join('proceso_seco','proceso_seco.id=corte_autorizado_datos.proceso_seco_id')
    ->join('salida_interna1_datos','salida_interna1_datos.corte_folio=corte_autorizado_datos.corte_folio','left')
    ->where('salida_interna1_datos.corte_folio = ',NULL)
    ->order_by('corte_autorizado_datos.corte_folio')
    ->order_by('lavado.nombre')
    ->order_by('proceso_seco.nombre');
    return $this->db->get()->result_array();
  }

  public function reporte3($datos)
  {
    $c = $this->db->distinct();
    $c->select('
    salida_interna1_datos.piezas as piezas,
    cliente.nombre as cliente,
    entrega_externa.corte_folio as folio,
    lavado.nombre as lavado,
    entrega_externa.fecha as fecha,
    ');
    $c->from('entrega_externa');
    $c->join('lavado','lavado.id=entrega_externa.lavado_id');
    $c->join('corte','corte.folio=entrega_externa.corte_folio');
    $c->join('cliente','corte.cliente_id=cliente.id');
    $c->join('corte_autorizado_datos','corte_autorizado_datos.corte_folio=entrega_externa.corte_folio AND corte_autorizado_datos.lavado_id=entrega_externa.lavado_id');
    $c->join('salida_interna1_datos','salida_interna1_datos.corte_folio=corte_autorizado_datos.corte_folio AND corte_autorizado_datos.id_carga=salida_interna1_datos.id_carga');
    $c->where('entrega_externa.fecha>=',$datos['fechai']);
    $c->where('entrega_externa.fecha<=',$datos['fechaf']);
    if ($datos['corte'] != "") $c->where('corte.corte',$datos['corte']);
    if ($datos['folio'] != "") $c->where('corte.folio',$datos['folio']);
    if ($datos['cliente_id'] != 0) $c->where('corte.cliente_id',$datos['cliente_id']);
    if ($datos['marca_id'] != 0) $c->where('corte.marca_id',$datos['marca_id']);
    if ($datos['maquilero_id'] != 0) $c->where('corte.maquilero_id',$datos['maquilero_id']);
    if ($datos['tipo_pantalon_id'] != 0) $c->where('corte.tipo_pantalon_id',$datos['tipo_pantalon_id']);
    $c->order_by('entrega_externa.corte_folio');
    $c->order_by('lavado.nombre');
    return $c->get()->result_array();
  }

  /*
  * Reporte de cargas en producción
  * -> cargas autorizadas
  * -> cargas con salida interna
  * -> cargas sin salida a almacén
  */
  public function reporte4()
  {
    /*
    select distinct corte_autorizado_datos.corte_folio, corte_autorizado_datos.lavado_id from corte_autorizado_datos left join salida_interna1 on corte_autorizado_datos.corte_folio=salida_interna1.corte_folio left join entrega_almacen on entrega_almacen.corte_folio =corte_autorizado_datos.corte_folio and corte_autorizado_datos.lavado_id = entrega_almacen.lavado_id where entrega_almacen.corte_folio is null and entrega_almacen.lavado_id is null;
    */
    $this->db->distinct()
    ->select('
    corte_autorizado_datos.corte_folio as folio,
    lavado.nombre as lavado
    ')
    ->from('corte_autorizado_datos')
    ->join('salida_interna1','corte_autorizado_datos.corte_folio=salida_interna1.corte_folio','left')
    ->join('entrega_almacen','entrega_almacen.corte_folio=corte_autorizado_datos.corte_folio and corte_autorizado_datos.lavado_id=entrega_almacen.lavado_id','left')
    ->join('lavado','lavado.id=corte_autorizado_datos.lavado_id')
    ->where('entrega_almacen.corte_folio=',NULL)
    ->where('entrega_almacen.lavado_id=',NULL)
    ->order_by('corte_autorizado_datos.corte_folio')
    ->order_by('lavado.nombre');
    return $this->db->get()->result_array();
  }

  /*
  * Reporte de cargas en almacén de salida
  * -> cargas con salida a almacén
  * -> cargas sin salida externa
  */
  public function reporte5()
  {
    $this->db->distinct()
    ->select('
    salida_interna1_datos.piezas as piezas,
    cliente.nombre as cliente,
    entrega_almacen.corte_folio as folio,
    entrega_almacen.fecha as fecha,
    lavado.nombre as lavado
    ')
    ->from('entrega_almacen')
    ->join('entrega_externa','entrega_externa.corte_folio=entrega_almacen.corte_folio and entrega_externa.lavado_id=entrega_almacen.lavado_id','left')
    ->join('lavado','lavado.id=entrega_almacen.lavado_id')
    ->join('corte','corte.folio=entrega_almacen.corte_folio')
    ->join('cliente','corte.cliente_id=cliente.id')
    ->join('corte_autorizado_datos','corte_autorizado_datos.corte_folio=entrega_almacen.corte_folio AND corte_autorizado_datos.lavado_id=entrega_almacen.lavado_id')
    ->join('salida_interna1_datos','salida_interna1_datos.corte_folio=corte_autorizado_datos.corte_folio AND corte_autorizado_datos.id_carga=salida_interna1_datos.id_carga')
    ->where('entrega_externa.corte_folio=',NULL)
    ->where('entrega_externa.lavado_id=',NULL)
    ->order_by('entrega_almacen.corte_folio')
    ->order_by('lavado.nombre');
    return $this->db->get()->result_array();
  }

  public function getCortesOjal($data)
  {
    $this->db->select('
    corte.folio as folio,
    corte.corte as corte,
    marca.nombre as marca,
    maquilero.nombre as maquilero,
    cliente.nombre as cliente,
    tipo_pantalon.nombre as tipo,
    corte.piezas as piezas,
    corte.fecha_entrada as fecha,
    corte.ojales as ojales
    ')
    ->from('corte')
    ->join('marca', 'corte.marca_id=marca.id', 'left')
    ->join('maquilero', 'corte.maquilero_id=maquilero.id')
    ->join('cliente', 'corte.cliente_id=cliente.id')
    ->join('tipo_pantalon', 'corte.tipo_pantalon_id=tipo_pantalon.id')
    ->where('ojales!=',0)
    ->where('fecha_entrada>=',$data['fechaInicial'])
    ->where('fecha_entrada<=',$data['fechaFinal']);
    return $this->db->get()->result_array();
  }
}
