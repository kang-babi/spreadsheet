<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Feature\Spreadsheet;

use KangBabi\Spreadsheet\Contracts\SpreadsheetContract;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use KangBabi\Spreadsheet\Wrappers\Row;

it('creates an instance of Sheet', function (): void {
    $sheet = new Sheet();

    expect($sheet)->toBeInstanceOf(Sheet::class);
    expect($sheet)->toBeInstanceOf(SpreadsheetContract::class);
});

it('contains config values', function (): void {
    $sheet = new Sheet();

    $sheet->config(function (Config $config): void {
        $config
            ->orientation('landscape')
            ->pageFit('page')
            ->paperSize('a4');
    });

    expect($sheet->getConfig())->not()->toBe(null);
    expect($sheet->getConfig())->toBeInstanceOf(Config::class);
    expect($sheet->getConfig()->getContent()['getPageSetup'])->toHaveCount(3);
});

it('contains header values', function (): void {
    $sheet = new Sheet();

    $sheet->header(function (Builder $header): void {
        $header
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'B')
                    ->height(30)
                    ->value('A', 'test1');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('C', 'D')
                    ->value('C', 'test2');
            });
    });

    expect($sheet->getHeader())->not()->toBe(null);
    expect($sheet->getHeader())->toBeInstanceOf(Builder::class);
    expect($sheet->getHeader()->getContent()[0])->toHaveCount(3);
    expect($sheet->getHeader()->getContent()[1])->toHaveCount(2);
});
