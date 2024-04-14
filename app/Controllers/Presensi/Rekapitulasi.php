<?php

namespace App\Controllers\Presensi;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use App\Models\AttModel;

class Rekapitulasi extends BaseController
{
    public function kehadiran($nip,$y=false,$m=false)
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

    public function uangmakan($nip,$y=false,$m=false)
    {
        //
    }

    public function tukin($nip,$y=false,$m=false)
    {
        //
    }
}
