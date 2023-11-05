<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use NcJoes\OfficeConverter\OfficeConverter;

class Converter extends BaseController
{
    public function index()
    {
        //
    }

    public function docxtopdf()
    {
      $converter = new OfficeConverter('test.docx', 'pdf');
    }
}
