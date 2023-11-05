<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use NcJoes\OfficeConverter\OfficeConverter;

class Converter extends BaseController
{
    public function index()
    {
      $process = new Process(['ls', '-lsa']);
      $process->run();

      if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
      }

      echo $process->getOutput();
    }

    public function docxtopdf()
    {
      $converter = new OfficeConverter('test.docx', 'pdf');
    }
}
