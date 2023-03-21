<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\SimpegModel;
class Home extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // return view('welcome_message');
        return $this->failNotFound('Page Not Found.');
    }

    public function check()
    {
      return $this->respond(['status'=>'online'], 200,'ok');
    }

    public function setpassword()
    {
        $nip        = $this->request->getVar('nip');
        $password   = $this->request->getVar('pwd');

        $model = new SimpegModel;

        $update = $model->updatepassword($nip,$password);
        return $this->respond(['status'=>'success'], 200);
    }
}
