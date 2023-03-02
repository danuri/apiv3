<?php

namespace App\Controllers\Mobile;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use App\Models\AbsenModel;
use App\Libraries\Jwtx;

class Home extends BaseController
{
    public function index()
    {
        $jwt = new Jwtx;
        $uid = $jwt->decode();
        print_r($uid);
    }

    public function absen($ip)
    {
        $jwt = new Jwtx;
        $uid = $jwt->decode();

        $db = new SimpegModel;

        $nip = $uid->username;
        $pegawai = (object) $db->getPegawai($nip);

        $niplama = $pegawai->NIP;

        $absendb = new AbsenModel;

        $user = $absendb->getRow('USERINFO',['BADGENUMBER'=>$niplama]);
        // $grup = array('21100','21200','21300','21400','21500','21600','21700','21800','21900','22000','23000');
        $kodeleveljab = array('213','216');
        // if (!in_array($pegawai->KODE_GRUP_SATUAN_KERJA, $grup)) // || !in_array($pegawai->KODE_LEVEL_JABATAN,$kodeleveljab)
        // {
            $plat = $pegawai->LAT;
            $plon = $pegawai->LON;

            if (!in_array($pegawai->KODE_LEVEL_JABATAN,$kodeleveljab))
            {
                $checkip = $db->getRow('ipsatker',array('ip'=>$ip));

                if(!$checkip){

                    $lat = $this->request->getGet('LAT');
                    $lon = $this->request->getGet('LON');

                    $forcelat = $db->getRow('TEMP_PEGAWAI_LATLON',array('NIP_BARU'=>$nip));

                    if($forcelat){
                        $plat = $forcelat->LAT;
                        $plon = $forcelat->LON;
                    }

                    if(!is_numeric($plon)){
                        $data = (object) array('status' => 'error', 'message'=>'Koordinat Lokasi Kantor Anda tidak sesuai ketentuan. Hubungi bagian Admin Kepegawaian Anda.');
                        return $this->response->setJSON( $data )->setStatusCode(400);
                    }

                    if($plat == 0 && $plon == 0){
                        $jarak = 0;
                    }else if($lat && $lon){
                        $jarak = $this->distance($lat,$lon,$plat,$plon);
                    }else{
                        $data = (object) array('status' => 'error', 'message'=>'Pastikan GPS pada perangkat sudah aktif');
                        return $this->response->setJSON( $data )->setStatusCode(400);
                    }

                    if($jarak > 500){
                        $data = (object) array('status' => 'error', 'message'=>'Gunakan jaringan Kementerian Agama atau Pastikan Lokasi Anda berada di sekitar Kantor untuk melakukan absensi');
                        return $this->response->setJSON( $data )->setStatusCode(400);
                    }
                }
            }
        // }

        date_default_timezone_set('Asia/Jakarta');
        $clock =date('Y-m-d H:i:s');

        if($user->TimeZone2 == 1){
            date_default_timezone_set('Asia/Makassar');
            $clock =date('Y-m-d H:i:s');
        }else if($user->TimeZone3 == 1){
            date_default_timezone_set('Asia/Jayapura');
            $clock =date('Y-m-d H:i:s');
        }else if($user->TimeZone4 == 1){
            date_default_timezone_set('Asia/Yangon');
            $clock =date('Y-m-d H:i:s');
          }

        $time = $clock.'.000';
        $tipe = 'I';

        $absenfrom = 'PUSAKA';
        $date = date('Y-m-d H:i:s');
        $query = $absendb->query("INSERT INTO CHECKINOUT (USERID, CHECKTIME, CHECKTYPE, VERIFYCODE, SENSORID, Memoinfo, WorkCode, sn, UserExtFmt) VALUES ('$user->USERID','$time','$tipe','15','105','$absenfrom','0','3574153900254','1')");

        if(!$query){
            $data = (object) array('status' => 'error', 'message' => 'Ada kesalahan, Silahkan ulangi!');
        }

        $data = (object) array('status' => 'sucess', 'message'=>'Anda telah absen pada jam '.$date);

        return $this->response->setJSON( $data )->setStatusCode(200);
    }

    function absens($y=false,$m=false)
    {
        $tahun = ($y)?$y:date('Y');
        $bulan = ($m)?$m:date('m');

        $jwt = new Jwtx;
        $uid = $jwt->decode();

        $db = new SimpegModel;

        $nip = $uid->username;
        $pegawai = $db->getPegawai($nip);
        $niplama = $pegawai->NIP;

        $absendb = new AbsenModel;
        $absens = $absendb->query("exec sp_absen_view_per_nip @userid='".$niplama."', @bln='".$bulan."', @thn='".$tahun."'")->getResult();

        return $this->response->setJSON( $absens )->setStatusCode(200);
    }

    function leave_get($y=false,$status='',$jenis='')
    {
        $this->load->model('presensi_model');

        $jwt = new Jwtx;
        $uid = $jwt->decode();

        $db = new SimpegModel;

        $nip = $uid->username;
        $pegawai = $db->getPegawai($nip);
        $niplama = $pegawai->NIP;

        $tahun = ($y)?$y:date('Y');

        // $absens = $this->presensi_model->sp("exec sp_Absen_List_By_UID @uid='".$niplama."', @thn='".$tahun."', @status='".$status."', @jenis='".$jenis."'");
        return $this->response->setJSON( $absens )->setStatusCode(200);
    }

    function setkoordinat($lat=false,$lon=false)
    {
        if($lat && $lon){
        $data = (object) array('status' => 'success', 'message' => 'Data berhasil disimpan!');

        return $this->response->setJSON( $data )->setStatusCode(200);

        }else{
        $data = (object) array('status' => 'error', 'message' => 'Latitude dan Longitude tidak terbaca!');

        $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        return $this->response->setJSON( $data )->setStatusCode(400);
        }
    }

    function distance($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $distance = $miles * 1.609344;
        $distance = $distance * 1000;

        return $distance;
      }

      function isGeoValid($type, $value)
      {
          $pattern = ($type == 'latitude')
              ? '/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'
              : '/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/';

          if (preg_match($pattern, $value)) {
              return true;
          } else {
              return false;
          }
      }
}
