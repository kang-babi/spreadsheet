<?php

namespace KangBabi\Spreadsheet\Tests;

use KangBabi\Contracts\SpreadsheetContract;
use KangBabi\Spreadsheet\Sheet;

it('creates an instance of Sheet', function () {
  $sheet = new Sheet();

  expect($sheet)->toBeInstanceOf(Sheet::class);
  expect($sheet)->toBeInstanceOf(SpreadsheetContract::class);
});

// it('allows client to download the file', function () {
//   $sheet = new Sheet();
// });
