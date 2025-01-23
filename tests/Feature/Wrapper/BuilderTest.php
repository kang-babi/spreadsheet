<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Wrapper;

use KangBabi\Contracts\WrapperContract;
use KangBabi\Wrappers\Builder;
use KangBabi\Wrappers\Row;

it('creates a builder wrapper instance', function (): void {
    $header = new Builder();

    expect($header)->not()->toBe(null);
    expect($header)->toBeInstanceOf(Builder::class);
    expect($header)->toBeInstanceOf(WrapperContract::class);
});

it('accepts header values', function (): void {
    $header = new Builder();

    $header->row(function (Row $row): void {
        $row->merge('A', 'B')
            ->merge('C', 'D');
    });

    expect($header->getContent())->toHaveCount(1);
    expect($header->getContent()[0])->toHaveKey('mergeCells');

    $header->row(function (Row $row): void {
        $row->height(45);
    });

    expect($header->getContent())->toHaveCount(2);
    expect($header->getContent()[1])->toHaveKey('getRowDimension');

    $header->row(function (Row $row): void {
        $row->value('A', 'test A')
            ->value('B', 'test B');
    });

    expect($header->getContent())->toHaveCount(3);
    expect($header->getContent()[2])->toHaveKey('setCellValue');
});
