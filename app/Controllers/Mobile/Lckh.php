<?php

namespace App\Controllers\Mobile;

use App\Controllers\BaseController;
use App\Libraries\Jwtx;
use App\Models\LckhModel;

class Lckh extends BaseController
{
    public function index($y,$m)
    {
        $tahun = ($y)?$y:date('Y');
        $bulan = ($m)?$m:date('m');

        $model = new LckhModel;
        $lckh = $model->query("SELECT * FROM tr_lckh WHERE YEAR(tanggal)='$tahun' AND MONTH(tanggal)='$bulan'")->getResult();

        return $this->response->setJSON( $lckh )->setStatusCode(200);
    }

    public function save()
    {
        if (! $this->validate([
            'tanggal' => "required",
            'jumlah' => "required",
            'satuan' => "required",
            'kegiatan' => "required",
            'output' => "required",
            ])) {
                $data = (object) array('status' => 'error', 'message'=>'Pastikan data sudah terisi');
                return $this->response->setJSON( $data )->setStatusCode(200);
            }

        $jwt = new Jwtx;
        $uid = $jwt->decode();
        $nip = $uid->username;

        $model = new LckhModel;

        $data   = [
            'nip'   => $nip,
            'tanggal'   => $this->request->getVar('tanggal'),
            'jumlah'   => $this->request->getVar('jumlah'),
            'satuan'   => $this->request->getVar('satuan'),
            'kegiatan'   => $this->request->getVar('kegiatan'),
            'output'   => $this->request->getVar('output'),
            'lampiran'   => $this->request->getVar('lampiran'),
        ];

        if($this->request->getVar('id')){
            $data['id'] = $this->request->getVar('id');
        }

        $insert = $model->save($data);

        $data = (object) array('status' => 'success', 'message'=>'Data berhasil terekam.');
        return $this->response->setJSON( $data )->setStatusCode(200);
    }

    public function delete($id)
    {
        $jwt = new Jwtx;
        $uid = $jwt->decode();
        $nip = $uid->username;

        $model = new LckhModel;
        $delete = $model->where(['id'=>$id,'nip'=>$nip])->delete();

        $data = (object) array('status' => 'success', 'message'=>'Data berhasil dihapus.');
        return $this->response->setJSON( $data )->setStatusCode(200);
    }

    public function view($id)
    {
        $jwt = new Jwtx;
        $uid = $jwt->decode();
        $nip = $uid->username;

        $model = new LckhModel;
        $find = $model->where(['id'=>$id,'nip'=>$nip])->first();

        return $this->response->setJSON( $find )->setStatusCode(200);
    }
}
