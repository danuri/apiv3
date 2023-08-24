<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use CodeIgniter\Cache\Cache;

class Redis extends BaseController
{
    public function index()
    {
      $newdata = [
        'username'  => 'johndoe',
        'email'     => 'johndoe@some-site.com',
        'logged_in' => true,
      ];

      session()->set('datasimpeg', $newdata);
    }

    public function read()
    {
      $json = (object) session('datasimpeg');
      echo $json->username;
      // print_r($json);
    }

    public function reindex()
    {
      $db = new SimpegModel;
    }

    public function updatepegawai($nip)
    {
      $cache = \Config\Services::cache();

      $db = new SimpegModel;
      $data = $db->getPegawai($nip);
      $cache->save('api_pegawai_'.$nip, $data, 3600000);

      return $this->respond(['status'=>'success'], 200);
    }

    public function updateuser($nip)
    {
      $cache = \Config\Services::cache();

      $db = new SimpegModel;
      $pegawai  = $db->getRow('TEMP_PEGAWAI_SSO',['NIP_USER' => $nip]);
      $cache->save('api_user_'.$nip, $pegawai, 3600000);

      return $this->respond(['status'=>'success'], 200);
    }

    public function updatelatlon($nip)
    {
      $cache = \Config\Services::cache();

      $db = new SimpegModel;
      $forcelat = $db->getRow('TEMP_PEGAWAI_LATLON',array('NIP_BARU'=>$nip));
      $cache->save('api_forcelat'.$nip, $forcelat, 3600000);

      return $this->respond(['status'=>'success'], 200);
    }

    public function deletelatlon($nip)
    {
      $cache = \Config\Services::cache();

      $cache->delete('api_forcelat'.$nip);

      return $this->respond(['status'=>'success'], 200);
    }
}
