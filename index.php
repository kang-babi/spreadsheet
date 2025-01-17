<?php

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

require 'vendor/autoload.php';

$sheet = new Sheet();

$config = new Config();

$config
  ->orientation('portrait')
  ->orientation('landscape')

  ->pageFit('page')
  ->pageFit('height', true)
  ->pageFit('width', true)

  ->margin('top', 20)
  ->margin('bottom', 20)
  ->margin('left', 20)
  ->margin('right', 20)

  ->paperSize('a4');

// $sheet
//   ->config($config)
//   ->save('dsfg');

// die;

$sheet->config(function (Config $config) {
  $config
    ->orientation('portrait')
    ->orientation('landscape')

    ->pageFit('page')
    ->pageFit('height', true)
    ->pageFit('width', true)

    ->margin('top', 20)
    ->margin('bottom', 20)
    ->margin('left', 20)
    ->margin('right', 20)

    ->paperSize('a4');
})
  ->save('sample');
