<?php

namespace KangBabi\Spreadsheet\Tests;

use KangBabi\Wrappers\Config;

it('creates config wrapper instance', function () {
  $config = new Config();

  expect($config)->toBeInstanceOf(Config::class);
});

it('accepts config values', function () {
  $config = new Config();

  $config->orientation('portrait')
    ->orientation('landscape');

  expect($config->rows)->toHaveKey('setOrientation');

  $config->pageFit('page')
    ->pageFit('height', true)
    ->pageFit('width', true);

  expect($config->rows)->toHaveKey('getPageSetup');

  $config->margin('top', 20)
    ->margin('bottom', 20)
    ->margin('left', 20)
    ->margin('right', 20);

  expect($config->rows)->toHaveKey('getPageMargins');
});
