<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
    use ResponseTrait;
    
    public function index()
    {
        // return view('welcome_message');
        return $this->failNotFound('Page Not Found.');
    }
}
