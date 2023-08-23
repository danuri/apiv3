<?php

namespace App\Controllers\Mobile;

use App\Controllers\BaseController;
use App\Libraries\Jwtx;
use App\Models\DownloadModel;;

class Download extends BaseController
{
    public function index()
    {

      $jwt = new Jwtx;
      $uid = $jwt->decode();
      $nip = $uid->username;

      $model = new DownloadModel;
      $download = $model->where(['nip'=>$nip])->findAll();

      return $this->response->setJSON( $download )->setStatusCode(200);
    }
}
