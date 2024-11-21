<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use App\Models\AttModel;
use App\Models\CheckLogModel;

class Home extends BaseController
{

  function absens($nip,$y,$m)
  {
      $tahun = ($y)?$y:date('Y');
      $bulan = ($m)?$m:date('m');

      $db = new SimpegModel;

      $pegawai = $db->getPegawai($nip);
      $niplama = $pegawai->NIP;

      $absendb = new AttModel;
      $absens = $absendb->query("exec sp_absen_view_per_nip_simple @userid='".$niplama."', @bln='".$bulan."', @thn='".$tahun."'")->getResult();

      return $this->response->setJSON( $absens )->setStatusCode(200);
  }

}
