<?php

use KangBabi\Wrappers\Row;

it('creates a row instance', function () {
  $row = new Row;

  expect($row)->toBeInstanceOf(Row::class);
});

it('accepts row values', function () {
  $row = new Row;

  $row
    ->height(1)
    ->height(2, 3);

  expect($row->getContent())->toHaveKey('getRowDimension');

  $row
    ->merge('a', 'b')
    ->merge('s2', 's');

  expect($row->getContent())->toHaveKey('mergeCells');
});
