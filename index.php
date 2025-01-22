<?php

declare(strict_types = 1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Config;
use KangBabi\Wrappers\Header;
use KangBabi\Wrappers\Row;

require 'vendor/autoload.php';

/**
 * todo: handle current row injection for sheet methods
 * todo:
 */
$sheet = new Sheet();
$sheet
  ->config(function (Config $config) {
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
  ->header(function (Header $header) {
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
