<?php

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Config;
use KangBabi\Wrappers\Header;
use KangBabi\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

require 'vendor/autoload.php';

$sheet = new Sheet();

$config = new Config();

$row = new Row();

// $row->height(14)
//   ->height(20, 2)
//   ->merge('A1', 'B2')
//   ->merge('C1', 'D2')
//   ->customMerge(['A1:B2', 'C1:D2']);
// $row->apply($sheet->getActiveSheet());

// dd($row);

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
})->header(function (Header $header) {
  $header->row(function (Row $row) {
    $row
      ->height(300)
      ->merge('A', 'B')
      ->merge('C', 'D')
      ->value('A', 'Bicol University')
      ->value('C', 'ICTO');
  });
})
  ->save('sample');
