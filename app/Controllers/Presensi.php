<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use App\Models\AbsenModel;
use App\Models\AttModel;

class Presensi extends BaseController
{
    public function index()
    {
        //
    }

    function months($nip,$y=false)
    {
      $tahun = ($y)?$y:date('Y');

      $model = new AttModel;
      $absens = $model->query_array("exec sp_Absen_view_per_bulan_by_nip @uid='".$nip."', @thn='".$tahun."'");

      return $this->response->setJSON($absens);
    }

    function days($nip,$y=false,$m=false)
    {
      $tahun = ($y)?$y:date('Y');
      $bulan = ($m)?$m:date('m');

      $model = new AttModel;
      $absens = $model->query_array("exec sp_absen_view_per_nip @userid='".$nip."', @bln='".$bulan."', @thn='".$tahun."'");

      return $this->response->setJSON($absens);
    }
}
