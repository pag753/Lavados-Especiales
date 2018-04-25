<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
    $query = $this->db->get("usuario");
    return $query->result_array();
  }

  public function get()
  {
    $this->db->from("usuario");
    $this->db->where("tipo_usuario_id!=",5);
    $this->db->order_by("nombre", "asc");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getById($id)
  {
    $query = $this->db->get_where('usuario', array('id' => $id ));
    return $query->result_array();
  }

  public function updateP($id,$p)
  {
    $data = array('pass'=> $p,);
    $this->db->where('id', $id);
    $this->db->update('usuario', $data);
  }

  public function updateD($id,$nombre_completo,$direccion,$telefono)
  {
    $data = array(
      'nombre_completo' => $nombre_completo,
      'direccion' => $direccion,
      'telefono' => $telefono,
    );
    $this->db->where('id', $id);
    $this->db->update('usuario', $data);
  }

  public function update($nombre,$pass,$tipo_usuario_id,$nombre_completo,$direccion,$telefono,$id)
  {
    if($pass == null)
    {
      $data = array(
        'nombre' => $nombre,
        'tipo_usuario_id' => $tipo_usuario_id,
        'nombre_completo' => $nombre_completo,
        'direccion'=>$direccion,
        'telefono'=>$telefono,
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
    if(count($query->result_array()) > 0) echo "yes";
    else echo "no";
  }
}
