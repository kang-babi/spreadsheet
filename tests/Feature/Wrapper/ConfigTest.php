<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Wrapper;

use KangBabi\Wrappers\Config;

it('creates config wrapper instance', function (): void {
    $config = new Config();

    expect($config)->toBeInstanceOf(Config::class);
});

it('accepts config values', function (): void {
    $config = new Config();

    $config
        ->orientation('portrait')
        ->orientation('landscape');

    expect($config->getContent())->toHaveKey('getPageSetup');
    expect($config->getContent()['getPageSetup'])->toHaveCount(2);

    $config
        ->pageFit('page')
        ->pageFit('height', true)
        ->pageFit('width', true);

    expect($config->getContent())->toHaveKey('getPageSetup');
    expect($config->getContent()['getPageSetup'])->toHaveCount(5);

    $config
        ->margin('top', 20)
        ->margin('bottom', 20)
        ->margin('left', 20)
        ->margin('right', 20);

    expect($config->getContent())->toHaveKey('getPageMargins');
    expect($config->getContent()['getPageMargins'])->toHaveCount(4);
});
