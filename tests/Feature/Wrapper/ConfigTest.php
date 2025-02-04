<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Feature\Wrapper;

use KangBabi\Spreadsheet\Options\Config\Fit;
use KangBabi\Spreadsheet\Options\Config\Margin;
use KangBabi\Spreadsheet\Options\Config\Orientation;
use KangBabi\Spreadsheet\Wrappers\Config;

it('creates config wrapper instance', function (): void {
    $config = new Config();

    expect($config)->toBeInstanceOf(Config::class);
});

it('accepts config values', function (): void {
    $config = new Config();

    $config
        ->orientation('landscape');

    expect($config->getContent())->toHaveKey('orientation');
    expect($config->getContent()['orientation'])->toBeInstanceOf(Orientation::class);

    $config
        ->pageFit('page')
        ->pageFit('height', true)
        ->pageFit('width', true);

    expect($config->getContent())->toHaveKey('fits');
    expect($config->getContent()['fits'])->toHaveCount(3);
    expect($config->getContent()['fits'][0])->toBeInstanceOf(Fit::class);

    $config
        ->margin('top', 20)
        ->margin('bottom', 20)
        ->margin('left', 20)
        ->margin('right', 20);

    expect($config->getContent())->toHaveKey('margins');
    expect($config->getContent()['margins'])->toHaveCount(4);
    expect($config->getContent()['margins'][0])->toBeInstanceOf(Margin::class);
});
