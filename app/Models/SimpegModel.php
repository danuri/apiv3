<?php

namespace App\Models;

use CodeIgniter\Model;

class SimpegModel extends Model
{
  protected $db;

  public function __construct()
  {
    $this->db = \Config\Database::connect('default', false);

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

  public function getPegawai($nip)
  {
    $query = $this->db->query("SELECT NIP,NAMA,SATKER_KELOLA,LAT,LON,KODE_GRUP_SATUAN_KERJA,KODE_LEVEL_JABATAN,SATKER_1,KODE_SATUAN_KERJA FROM TEMP_PEGAWAI WHERE NIP_BARU='$nip'")->getRow();
    return $query;
  }

  public function query_row($query)
  {
    $query = $this->db->query($query)->getRow();
    return $query;
  }

  public function query_array($query)
  {
    $query = $this->db->query($query)->getResult();
    return $query;
  }

  public function getCount($table,$where=false)
  {
    $builder = $this->db->table($table);

    if($where){
      $query = $builder->getWhere($where);
    }else{
      $query = $builder->get();
    }

    return $query->countAllResults();
  }

  public function getAuth($nip)
  {
    $query = $this->db->query("exec sp_usermanager @nip='".$nip."', @appid='1'")->getRow();
    return $query;
  }

  public function getInfoKP($year,$month)
  {
    $query = $this->db->query("SELECT SATKER2 AS SATKER, COUNT(*) AS JUMLAH FROM TEMP_PEGAWAI_PANGKAT WHERE TMT_KP <> CAST('$month/01/$year' AS DATE)
                              GROUP BY SATKER2")->getResult();
    return $query;
  }

  public function getCountKP($year,$month)
  {
    $query = $this->db->query("SELECT COUNT(*) AS JUMLAH FROM TEMP_PEGAWAI_PANGKAT
    WHERE TMT_KP <> CAST('$month/01/$year' AS DATE)")->getRow();
    return $query;
  }
}
