<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

    public function deleteByFolio($folio)
    {
        $this->db->where('corte_folio', $folio);
        $this->db->delete('salida_interna1_datos');
    }

    public function getByFolioEspecifico($folio = null)
    {
        $this->db->distinct()
            ->select('
    salida_interna1_datos.id_carga as id_carga,
    corte_autorizado_datos.lavado_id as lavado_id,
    salida_interna1_datos.piezas as piezas')
            ->from('corte_autorizado_datos')
            ->join('salida_interna1_datos', 'salida_interna1_datos.id_carga=corte_autorizado_datos.id_carga and
    salida_interna1_datos.corte_folio=corte_autorizado_datos.corte_folio')
            ->where('salida_interna1_datos.corte_folio', $folio);
        return $this->db->get()->result_array();
    }

    public function getByFolioEspecifico2($folio = null)
    {
        $this->db->distinct()
            ->select('
    salida_interna1_datos.id_carga as id_carga,
    corte_autorizado_datos.lavado_id as lavado_id,
    lavado.nombre as lavado,
    salida_interna1_datos.piezas as piezas')
            ->from('corte_autorizado_datos')
            ->join('salida_interna1_datos', 'salida_interna1_datos.id_carga=corte_autorizado_datos.id_carga and
    salida_interna1_datos.corte_folio=corte_autorizado_datos.corte_folio')
            ->join('lavado', 'lavado.id=corte_autorizado_datos.lavado_id')
            ->where('salida_interna1_datos.corte_folio', $folio);
        return $this->db->get()->result_array();
    }

    public function updateAdministracion($data)
    {
        $this->db->where('id_carga', $data['id_carga']);
        $this->db->where('corte_folio', $data['corte_folio']);
        unset($data['id_carga']);
        unset($data['corte_folio']);
        $this->db->set($data);
        $this->db->update('salida_interna1_datos');
    }

    public function deleteAdministracion($data)
    {
        $this->db->where($data);
        $this->db->delete('salida_interna1_datos');
    }
}
