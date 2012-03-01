<?php

class MY_Model extends CI_Model
{
  public $tableName;

  public function __construct()
  {
    parent::__construct();
  }
  
  public function fetch_all()
  {
    return $this->db->get($this->tableName);
  }
  
  public function find_by_id($id)
  {
    $this->db->from($this->tableName);
    $this->db->where('id', $id);
    return $this->db->get();
  }
  
  public function create(array $data)
  {
    $this->db->insert($this->tableName, $data);
  }
  
  public function update(array $data, $id)
  {
    $this->db->update($this->tableName, $data, array('id' => $id));
  }
  
  public function delete($id)
  {
    $this->db->delete($this->tableName, array('id' => $id)); 
  }
  

}