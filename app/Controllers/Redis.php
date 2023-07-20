<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SimpegModel;

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
}
