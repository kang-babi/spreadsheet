<?php

declare(strict_types = 1);

namespace KangBabi\Spreadsheet\Tests;

use KangBabi\Contracts\SpreadsheetContract;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Config;
use KangBabi\Wrappers\Header;
use KangBabi\Wrappers\Row;

it('creates an instance of Sheet', function () {
    $sheet = new Sheet();

    expect($sheet)->toBeInstanceOf(Sheet::class);
    expect($sheet)->toBeInstanceOf(SpreadsheetContract::class);
});

it('contains config values', function () {
    $sheet = new Sheet();

    $sheet->config(function (Config $config) {
        $config
          ->orientation('landscape')
          ->pageFit('page')
          ->paperSize('a4');
    });

    expect($sheet->getConfig())->not()->toBe(null);
    expect($sheet->getConfig())->toBeInstanceOf(Config::class);
    expect($sheet->getConfig()->getContent())->toHaveCount(3);
});

it('contains header values', function () {
    $sheet = new Sheet();

    $sheet->header(function (Header $header) {
        $header
          ->row(function (Row $row) {
              $row->merge('A', 'B')
                ->height(30)
                ->value('A', 'test1');
          })
          ->row(function (Row $row) {
              $row->merge('C', 'D')
                ->value('C', 'test2');
          });
    });

    expect($sheet->getHeader())->not()->toBe(null);
    expect($sheet->getHeader())->toBeInstanceOf(Header::class);
    expect($sheet->getHeader()->getContent()[0])->toHaveCount(3);
    expect($sheet->getHeader()->getContent()[1])->toHaveCount(2);
});
