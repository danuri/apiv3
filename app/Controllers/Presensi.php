<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SimpegModel;
use App\Models\AbsenModel;
use App\Models\AttModel;
use App\Models\TabsenModel;

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

    function ketidakhadiran($nip,$y=false,$status='',$jenis='')
    {
      $tahun = ($y)?$y:date('Y');

      $model = new AbsenModel;
      $absens = $model->query_array("exec sp_Absen_List_By_UID @uid='".$nip."', @thn='".$tahun."', @status='".$status."', @jenis='".$jenis."'");

      return $this->response->setJSON($absens);
    }

    function ketidakhadirandetail($nip,$id)
    {
      $model = new AbsenModel;
      $absens = $model->query_row("exec sp_Absen_View_By_ID_UID @uid='".$nip."', @id='".$id."'");

      return $this->response->setJSON($absens);
    }

    function ketidakhadiranadd()
    {
      $nip          = $this->input->post('create_by');
      $nip      = $this->pegawai_model->getnip($nip)->NIP;
      $start_date   = $this->input->post('start_date');
      $end_date     = $this->input->post('end_date');
      $remark       = $this->input->post('remark');
      $attachment   = $this->input->post('attachment');
      $abs_type   = $this->input->post('abs_type');

      $data = array(
        'create_date' => date('Y-m-d H:i:s'),
        'create_by' => $nip,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'abs_type' => $abs_type,
        'remark' => $remark,
        'attachment' => $attachment,
        'approval_status' => 'STT.A00',
      );

      $tabsen = new TabsenModel;
      $insert = $tabsen->insert($data);

      $model = new AbsenModel;
      $absens = $model->query("exec sp_Absen_days_insert @absen_id='".$absens."', @Date1='".$start_date."', @Date2='".$end_date."'");

      return $this->response->setJSON($absens);
    }

    function ketidakhadirandelete($nip,$id)
    {
      $tabsen = new TabsenModel;
      $delete = $tabsen->where(['create_by'=>$nip,'abs_id'=>$id])->delete();

      if($delete){
        $model = new AbsenModel;
        $delete2 = $model->query("DELETE FROM t_absen_tgl WHERE absen_id='$id'");
        return $this->response->setJSON(['msg'=>'success']);
      }else{
        return $this->response->setJSON(['msg'=>'error']);
      }

    }

    function ketidakhadiransend($nip,$id)
    {
      $tabsen = new TabsenModel;
      $delete = $tabsen->set(['approval_status'=>'STT.A01','sent_date'=>date('Y-m-d H:i:s')])->where(['create_by'=>$nip,'abs_id'=>$id])->update();

      return $this->response->setJSON(['msg'=>'success']);
    }

    function pengaduan($nip,$y=false,$status='',$jenis='')
    {
      $tahun = ($y)?$y:date('Y');

      $model = new AbsenModel;
      $absens = $model->query_array("exec sp_Absen_Adu_List_By_UID @uid='".$nip."', @thn='".$tahun."', @status='".$status."', @jenis='".$jenis."'");

      return $this->response->setJSON($absens);
    }

    function pengaduanaddetail($nip,$id)
    {
      $model = new AbsenModel;
      $absens = $model->query_row("exec sp_Absen_Adu_View_By_UID_ID @uid='".$nip."', @id='".$id."'");

      return $this->response->setJSON($absens);
    }

    function pengaduanadd()
    {
      $nip          = $this->input->post('create_by');

      $data = array(
        'create_date' => date('Y-m-d H:i:s'),
        'create_by' => $nip,
        'tgl_adu' => $this->input->post('tgl_adu'),
        'adu_type' => $this->input->post('adu_type'),
        'remark' => $this->input->post('remark'),
        'attachment' => $this->input->post('attachment'),
        'approval_status' => 'STT.A00',
      );

      $tadu = new TaduModel;
      $insert = $tadu->insert($data);

      return $this->response->setJSON($insert);
    }

    function pengaduandelete($nip,$id)
    {
      $tadu = new TaduModel;
      $delete = $tadu->where(['create_by'=>$nip,'adu_id'=>$id])->delete();

      return $this->response->setJSON(['msg'=>'success']);

    }

    function pengaduansend($nip,$id)
    {
      $tabsen = new TaduModel;
      $delete = $tabsen->set(['approval_status'=>'STT.A01','sent_date'=>date('Y-m-d H:i:s')])->where(['create_by'=>$nip,'adu_id'=>$id])->update();

      return $this->response->setJSON(['msg'=>'success']);
    }
}
