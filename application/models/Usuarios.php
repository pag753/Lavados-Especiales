<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function login()
  {
    $this->db->where('nombre', $this->input->post('nombre'));
    $this->db->where('pass', md5($this->input->post('pass')));
    $this->db->where('activo', 1);
    $query = $this->db->get("usuario");
    return $query->result_array();
  }

  public function get()
  {
    $this->db->from("usuario");
    $this->db->where("tipo_usuario_id!=", 5);
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getAll()
  {
    $this->db->from("usuario");
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('usuario', array(
      'id' => $id
    ));
    return $query->result_array();
  }

  public function updateP($id, $p)
  {
    $data = array(
      'pass' => $p
    );
    $this->db->where('id', $id);
    $this->db->update('usuario', $data);
  }

  public function updateD($id, $nombre_completo, $direccion, $telefono)
  {
    $data = array(
      'nombre_completo' => $nombre_completo,
      'direccion' => $direccion,
      'telefono' => $telefono
    );
    $this->db->where('id', $id);
    $this->db->update('usuario', $data);
  }

  public function update($nombre, $pass, $tipo_usuario_id, $nombre_completo, $direccion, $telefono, $activo, $puestoId, $id)
  {
    if ($pass == null)
    {
      $data = array(
        'nombre' => $nombre,
        'tipo_usuario_id' => $tipo_usuario_id,
        'nombre_completo' => $nombre_completo,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'activo' => $activo,
        'puesto_id' => $puestoId
      );
    }
    else
    {
      $data = array(
        'nombre' => $nombre,
        'pass' => md5($pass),
        'tipo_usuario_id' => $tipo_usuario_id,
        'nombre_completo' => $nombre_completo,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'puesto_id' => $puestoId
      );
    }
    $this->db->where('id', $id);
    $this->db->update('usuario', $data);
  }

  public function insert($data)
  {
    $this->db->insert('usuario', $data);
    return $this->db->insert_id();
  }

  function exists($usuario)
  {
    $this->db->where('nombre', $usuario);
    $query = $this->db->get("usuario");
    if (count($query->result_array()) > 0)
    echo "yes";
    else
    echo "no";
  }

  public function getUsuariosProduccion()
  {
    $this->db->select('nombre,id')
    ->from('usuario')
    ->where('tipo_usuario_id=', 3)
    ->order_by('nombre');
    return $this->db->get()->result_array();
  }

  public function getOperarios()
  {
    $this->db->select()
    ->from('usuario')
    ->where('tipo_usuario_id', 4)
    ->or_where('tipo_usuario_id', 6)
    ->order_by('nombre');
    return $this->db->get()->result_array();
  }

  public function getOperariosEspecificos()
  {
    $this->db->select("u.nombre_completo, u.nombre, u.id, p.nombre as puesto")
    ->from('usuario u')
    ->join('puesto p', 'u.puesto_id = p.id')
    ->where('u.activo', '1')
    ->where_in('u.tipo_usuario_id', array(
      4,
      6
    ));
    return $this->db->get()->result_array();
  }
}
