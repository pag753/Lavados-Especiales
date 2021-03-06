<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* +-------+---------+------+-----+---------+----------------+
* | Field | Type    | Null | Key | Default | Extra          |
* +-------+---------+------+-----+---------+----------------+
* | costo | float   | NO   |     | NULL    |                |
* | id    | int(11) | NO   | PRI | NULL    | auto_increment |
* +-------+---------+------+-----+---------+----------------+
*/

class Ojal extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get()
  {
    $this->db->from("ojal");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function update($costo)
  {
    $data = array(
      'costo' => $costo
    );
    $this->db->update('ojal', $data);
  }
}
