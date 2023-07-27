<?php

namespace App\Controllers\Presensi;

use App\Controllers\BaseController;
use App\Models\Att43Model;

class Pelaporan extends BaseController
{
    public function index()
    {
        //
    }

    public function pengaduanapprove($nip,$tgl)
    {
      $absen = new Att43Model;

      $insert = $absen->aduinsert($nip,$tgl);
      echo 'OK';
    }
}
