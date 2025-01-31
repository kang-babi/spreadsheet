<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Feature\Wrapper;

use KangBabi\Spreadsheet\Wrappers\Row;

it('creates a row instance', function (): void {
    $row = new Row();

    expect($row)->toBeInstanceOf(Row::class);
});

it('accepts row values', function (): void {
    $row = new Row();

    $row
        ->height(1)
        ->height(2, 3);

    expect($row->getContent())->toHaveKey('getRowDimension');

    $row
        ->merge('a', 'b')
        ->merge('s2', 's');

    expect($row->getContent())->toHaveKey('mergeCells');
});
