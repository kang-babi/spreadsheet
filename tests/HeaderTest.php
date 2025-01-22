<?php

namespace KangBabi\Spreadsheet\Tests;

use KangBabi\Contracts\WrapperContract;
use KangBabi\Wrappers\Header;
use KangBabi\Wrappers\Row;

it('creates a header wrapper instance', function () {
  $header = new Header();

  expect($header)->not()->toBe(null);
  expect($header)->toBeInstanceOf(Header::class);
  expect($header)->toBeInstanceOf(WrapperContract::class);
});

it('accepts header values', function () {
  $header = new Header();

  $header->row(function (Row $row) {
    $row->merge('A', 'B')
      ->merge('C', 'D');
  });

  expect($header->getContent())->toHaveCount(1);
  expect($header->getContent()[0])->toHaveKey('mergeCells');

  $header->row(function (Row $row) {
    $row->height(45);
  });

  expect($header->getContent())->toHaveCount(2);
  expect($header->getContent()[1])->toHaveKey('getRowDimension');

  $header->row(function (Row $row) {
    $row->value('A', 'test A')
      ->value('B', 'test B');
  });

  expect($header->getContent())->toHaveCount(3);
  expect($header->getContent()[2])->toHaveKey('setCellValue');
});
