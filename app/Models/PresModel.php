<?php

namespace App\Models;

use CodeIgniter\Model;

class PresModel extends Model
{
  protected $db;

  public function __construct()
  {
    $this->db = \Config\Database::connect('pres', false);

  }

  public function getRow($table,$where)
  {
    $builder = $this->db->table($table);
    $query = $builder->getWhere($where);

    return $query->getRow();
  }

  public function getArray($table,$where=false)
  {
    $builder = $this->db->table($table);

    if($where){
      $query = $builder->getWhere($where);
    }else{
      $query = $builder->get();
    }

    return $query->getResult();
  }

}
