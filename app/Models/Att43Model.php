<?php

namespace App\Models;

use CodeIgniter\Model;

class Att43Model extends Model
{
  protected $db;

  public function __construct()
  {
    $this->db = \Config\Database::connect('att43', false);

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
    $query = $this->db->query("SELECT * FROM TEMP_PEGAWAI WHERE NIP_BARU='$nip'")->getRow();
    return $query;
  }

  public function query($query)
  {
    $query = $this->db->query($query);
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

  function aduinsert($nip,$tanggal)
  {
    $query = $this->db->query("exec sp_Absen_Adu_Insert @userid='".$nip."', @checktime='".$tanggal."'");

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

  function tukin($nip,$y=false)
  {
    $tahun = ($y)?$y:date('Y');

    $query = $this->db->query("exec sp_absen_Tukin_view_per_bln_by_nip @userid='".$nip."', @bln='01', @thn='".$tahun."'")->getResult();

    return $query;
  }

  public function MonthPegawai($nip,$tahun)
  {
    $query = $this->db->query("exec sp_Absen_view_per_bulan_by_nip @thn='".$tahun."', @uid='".$nip."'")->getResult();

    return $query;
  }
}
