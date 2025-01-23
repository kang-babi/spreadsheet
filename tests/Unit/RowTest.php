<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Unit;

use KangBabi\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use InvalidArgumentException;

it('instantiates a row', function (): void {
    $row = new Row();
    expect($row)->toBeInstanceOf(Row::class);
    expect($row)->toBeInstanceOf(WrapperContract::class);
});

it('stores row key and value', function (): void {
    $row = new Row();

    $row->row('key', 'value');

    expect($row->getContent())->toHaveKey('key');
    expect($row->getContent()['key'])->toBeArray();
    expect($row->getContent()['key'])->toBe(['value']);
});

it('sets cell value', function (): void {
    $row = new Row();

    $row->value('A', 'Test');

    expect($row->getContent())->toHaveKey('setCellValue');
    expect($row->getContent()['setCellValue'])->toBeArray();
    expect($row->getContent()['setCellValue'])->toBe([
        ['cell' => 'A1', 'value' => 'Test', 'dataType' => null],
    ]);
});

it('sets cell value with data type', function (): void {
    $row = new Row();

    $row->value('A', 'Test', 'string');

    expect($row->getContent())->toHaveKey('setCellValue');
    expect($row->getContent()['setCellValue'])->toBeArray();
    expect($row->getContent()['setCellValue'])->toBe([
        [
            'cell' => 'A1',
            'value' => 'Test',
            'dataType' => DataType::TYPE_STRING2
        ],
    ]);
});

it('sets height', function (): void {
    $row = new Row();

    $row->height(30);

    expect($row->getContent())->toHaveKey('getRowDimension');
    expect($row->getContent()['getRowDimension'])->toBeArray();
    expect($row->getContent()['getRowDimension'])->toBe([
        [
            'action' => 'setRowHeight',
            'row' => 1,
            'height' => 30
        ],
    ]);
});

it('merges cells', function (): void {
    $row = new Row();

    $row->merge('A', 'B');

    expect($row->getContent())->toHaveKey('mergeCells');
    expect($row->getContent()['mergeCells'])->toBeArray();
    expect($row->getContent()['mergeCells'])->toBe(['A1:B1']);
});

it('throws exception when merging invalid cells', function (): void {
    $row = new Row();

    $row->customMerge(['A', 'A']);

    expect($row->getContent())->not->toHaveKey('mergeCells');
})->throws(InvalidArgumentException::class);

it('merges custom cells', function (): void {
    $row = new Row();

    $row->customMerge([['A1', 'B2'], 'C3:D4']);

    expect($row->getContent())->toHaveKey('mergeCells');
    expect($row->getContent()['mergeCells'])->toBeArray();
    expect($row->getContent()['mergeCells'])->toBe(['A1:B2', 'C3:D4']);
});

it('applies row to worksheet', function (): void {
    $row = new Row();
    $row
        ->merge('A', 'B')
        ->value('A', 'Test')
        ->value('C', 3, 'numeric')
        ->height(30);

    $worksheet = (new Sheet())->getActiveSheet();
    $row->apply($worksheet);

    expect($worksheet->getCell('A1')->getValue())->toBe('Test');
});
