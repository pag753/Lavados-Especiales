<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

	public function agregar($datos=null)
	{
		$fecha=DateTime::createFromFormat('d/m/Y',$datos['fecha']);
		$data['folio']=$datos['folio'];
		$data['fecha_entrada']=$fecha->format('Y/m/d');
		$data['corte']=$datos['corte'];
		$data['marca_id']=$datos['marca'];
		$data['maquilero_id']=$datos['maquilero'];
		$data['cliente_id']=$datos['cliente'];
		$data['tipo_pantalon_id']=$datos['tipo'];
		$data['piezas']=$datos['piezas'];
		$this->db->insert('corte',$data);
		return $data;
	}

	public function getByFolio($folio)
	{
		$query = $this->db->get_where('corte', array('folio' => $folio));
		return $query->result_array();
	}

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
			corte.fecha_entrada as fecha')
		->from('corte')
		->join('marca','corte.marca_id=marca.id')
		->join('maquilero','corte.maquilero_id=maquilero.id')
		->join('cliente','corte.cliente_id=cliente.id')
		->join('tipo_pantalon','corte.tipo_pantalon_id=tipo_pantalon.id')
		->where('corte.folio',$folio);
		return $this->db->get()->result_array();
	}

	public function reporte1($datos)
	{
		$f=explode("/",$datos['fechai']);
		$fechai=$f[2]."/".$f[1]."/".$f[0];
		$f=explode("/",$datos['fechaf']);
		$fechaf=$f[2]."/".$f[1]."/".$f[0];
		unset($datos['fechai']);
		unset($datos['fechaf']);
		unset($datos['reporte']);
		unset($datos['aceptar']);
		if($datos['corte']==null)
			unset($datos['corte']);
		if($datos['folio']==null)
			unset($datos['folio']);
		if($datos['cliente_id']==0)
			unset($datos['cliente_id']);
		if($datos['marca_id']==0)
			unset($datos['marca_id']);
		if($datos['maquilero_id']==0)
			unset($datos['maquilero_id']);
		if($datos['tipo_pantalon_id']==0)
			unset($datos['tipo_pantalon_id']);
		if(isset($datos['check']))
			unset($datos['check']);
		$this->db->select('
			corte.folio as folio,
			corte.corte as corte,
			marca.nombre as marca,
			maquilero.nombre as maquilero,
			cliente.nombre as cliente,
			tipo_pantalon.nombre as tipo,
			corte.piezas as piezas,
			corte.fecha_entrada as fecha
			')
		->from('corte')
		->join('marca','corte.marca_id=marca.id')
		->join('maquilero','corte.maquilero_id=maquilero.id')
		->join('cliente','corte.cliente_id=cliente.id')
		->join('tipo_pantalon','corte.tipo_pantalon_id=tipo_pantalon.id')
		->where($datos)
		->where('corte.fecha_entrada>=',$fechai)
		->where('corte.fecha_entrada<=',$fechaf)
		->where('corte.folio!=','corte_autorizado.corte_folio')
		->order_by('corte.folio','ASC');
		return $this->db->get()->result_array();
	}

	public function reporte2($datos)
	{
		$f=explode("/",$datos['fechai']);
		$fechai=$f[2]."/".$f[1]."/".$f[0];
		$f=explode("/",$datos['fechaf']);
		$fechaf=$f[2]."/".$f[1]."/".$f[0];
		unset($datos['fechai']);
		unset($datos['fechaf']);
		unset($datos['reporte']);
		unset($datos['aceptar']);
		if($datos['corte']==null)
			unset($datos['corte']);
		if($datos['folio']==null)
			unset($datos['folio']);
		if($datos['cliente_id']==0)
			unset($datos['cliente_id']);
		if($datos['marca_id']==0)
			unset($datos['marca_id']);
		if($datos['maquilero_id']==0)
			unset($datos['maquilero_id']);
		if($datos['tipo_pantalon_id']==0)
			unset($datos['tipo_pantalon_id']);
		if(isset($datos['check']))
			unset($datos['check']);
		$this->db->select('
			corte_autorizado.cargas as cargas,
			corte_autorizado.fecha_autorizado as fechaAutorizado,
			corte.folio as folio,
			corte.corte as corte,
			marca.nombre as marca,
			maquilero.nombre as maquilero,
			cliente.nombre as cliente,
			tipo_pantalon.nombre as tipo,
			corte.piezas as piezas,
			corte.fecha_entrada as fecha
			')
		->from('corte')
		->join('marca','corte.marca_id=marca.id')
		->join('maquilero','corte.maquilero_id=maquilero.id')
		->join('cliente','corte.cliente_id=cliente.id')
		->join('tipo_pantalon','corte.tipo_pantalon_id=tipo_pantalon.id')
		->join('corte_autorizado','corte.folio=corte_autorizado.corte_folio')
		->where($datos)
		->where('corte.fecha_entrada>=',$fechai)
		->where('corte.fecha_entrada<=',$fechaf)
		->where('corte.folio!=','salida_interna1.corte_folio')
		->order_by('corte.folio','ASC');
		return $this->db->get()->result_array();
	}

	public function reporte3($datos)
	{
		$f=explode("/",$datos['fechai']);
		$fechai=$f[2]."/".$f[1]."/".$f[0];
		$f=explode("/",$datos['fechaf']);
		$fechaf=$f[2]."/".$f[1]."/".$f[0];
		unset($datos['fechai']);
		unset($datos['fechaf']);
		unset($datos['reporte']);
		unset($datos['aceptar']);
		if($datos['corte']==null)
			unset($datos['corte']);
		if($datos['folio']==null)
			unset($datos['folio']);
		if($datos['cliente_id']==0)
			unset($datos['cliente_id']);
		if($datos['marca_id']==0)
			unset($datos['marca_id']);
		if($datos['maquilero_id']==0)
			unset($datos['maquilero_id']);
		if($datos['tipo_pantalon_id']==0)
			unset($datos['tipo_pantalon_id']);
		if(isset($datos['check']))
			unset($datos['check']);
		$this->db->select('
			salida_interna1.fecha as fechaSalidaInterna,
			salida_interna1.muestras as muestras,
			entrega_almacen.fecha as fechaSalida,
			corte_autorizado.cargas as cargas,
			corte_autorizado.fecha_autorizado as fechaAutorizado,
			corte.folio as folio,
			corte.corte as corte,
			marca.nombre as marca,
			maquilero.nombre as maquilero,
			cliente.nombre as cliente,
			tipo_pantalon.nombre as tipo,
			corte.piezas as piezas,
			corte.fecha_entrada as fecha
			')
		->from('corte')
		->join('marca','corte.marca_id=marca.id')
		->join('maquilero','corte.maquilero_id=maquilero.id')
		->join('cliente','corte.cliente_id=cliente.id')
		->join('tipo_pantalon','corte.tipo_pantalon_id=tipo_pantalon.id')
		->join('corte_autorizado','corte.folio=corte_autorizado.corte_folio')
		->join('salida_interna1','corte.folio=salida_interna1.corte_folio')
		->join('entrega_almacen','corte.folio=entrega_almacen.corte_folio')
		->where($datos)
		->where('corte.fecha_entrada>=',$fechai)
		->where('corte.fecha_entrada<=',$fechaf)
		->order_by('corte.folio','ASC');
		return $this->db->get()->result_array();
	} 

	public function reporte4($datos)
	{
		$f=explode("/",$datos['fechai']);
		$fechai=$f[2]."/".$f[1]."/".$f[0];
		$f=explode("/",$datos['fechaf']);
		$fechaf=$f[2]."/".$f[1]."/".$f[0];
		unset($datos['fechai']);
		unset($datos['fechaf']);
		unset($datos['reporte']);
		unset($datos['aceptar']);
		if($datos['corte']==null)
			unset($datos['corte']);
		if($datos['folio']==null)
			unset($datos['folio']);
		if($datos['cliente_id']==0)
			unset($datos['cliente_id']);
		if($datos['marca_id']==0)
			unset($datos['marca_id']);
		if($datos['maquilero_id']==0)
			unset($datos['maquilero_id']);
		if($datos['tipo_pantalon_id']==0)
			unset($datos['tipo_pantalon_id']);
		if(isset($datos['check']))
			unset($datos['check']);
		$this->db->select('
			salida_interna1.fecha as fechaSalidaInterna,
			salida_interna1.muestras as muestras,
			corte_autorizado.cargas as cargas,
			corte_autorizado.fecha_autorizado as fechaAutorizado,
			corte.folio as folio,
			corte.corte as corte,
			marca.nombre as marca,
			maquilero.nombre as maquilero,
			cliente.nombre as cliente,
			tipo_pantalon.nombre as tipo,
			corte.piezas as piezas,
			corte.fecha_entrada as fecha
			')
		->from('corte')
		->join('marca','corte.marca_id=marca.id')
		->join('maquilero','corte.maquilero_id=maquilero.id')
		->join('cliente','corte.cliente_id=cliente.id')
		->join('tipo_pantalon','corte.tipo_pantalon_id=tipo_pantalon.id')
		->join('corte_autorizado','corte.folio=corte_autorizado.corte_folio')
		->join('salida_interna1','corte.folio=salida_interna1.corte_folio')
		->where($datos)
		->where('corte.fecha_entrada>=',$fechai)
		->where('corte.fecha_entrada<=',$fechaf)
		->where('corte.folio!=','entrega_almacen.corte_folio')
		->order_by('corte.folio','ASC');
		return $this->db->get()->result_array();
	}
}
