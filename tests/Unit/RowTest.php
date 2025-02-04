<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Unit;

use InvalidArgumentException;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use KangBabi\Spreadsheet\Wrappers\Style;

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

it('sets style value', function (): void {
  $row = new Row();

  $row->style('A1', function (Style $style): void {
    $style->size(11);
  });

  expect($row->getContent())->toHaveKey('getStyle');
  expect($row->getContent()['getStyle'])->toBeArray();
  expect($row->getContent()['getStyle'][0])->toBeInstanceOf(Style::class);
});

it('sets multiple styles', function (): void {
  $row = new Row();

  $row->style('A1', function (Style $style): void {
    $style
      ->size(11)
      ->bold(true)
      ->alignment('horizontal', 'center');
  });

  expect($row->getContent())->toHaveKey('getStyle');
  expect($row->getContent()['getStyle'])->toBeArray();
  expect($row->getContent()['getStyle'][0])->toBeInstanceOf(Style::class);
});

it('applies multiple styles to worksheet', function (): void {
  $row = new Row();
  $row->style('A1', function (Style $style): void {
    $style
      ->size(11)
      ->bold(true)
      ->alignment('horizontal', 'center');
  });

  $worksheet = (new Sheet())->getActiveSheet();
  $row->apply($worksheet);

  $style = $worksheet->getStyle('A1')->getFont();
  expect((int) $style->getSize())->toBe(11);
  expect($worksheet->getStyle('A1')->getAlignment()->getHorizontal())->toBe('general');
});

it('applies multiple styles to multiple cells to worksheet', function (): void {
  $row = new Row();

  $styleArray = [
    'font' => [
      'size' => 11,
      'bold' => true
    ],
    'alignment' => [
      'horizontal' => 'center'
    ]
  ];

  $row->style('A:B', function (Style $style): void {
    $style
      ->size(11)
      ->bold()
      ->alignment('horizontal', 'center');
  });

  $worksheet = (new Sheet())->getActiveSheet();
  $row->apply($worksheet);

  $style = $worksheet->getStyle('A1:B1')->getFont();
  expect((int) $style->getSize())->toBe(11);
  expect($worksheet->getStyle('A1:B1')->getAlignment()->getHorizontal())->toBe('center');
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

  $row->customMerge([['A', '']]);

  expect($row->getContent())->not->toHaveKey('mergeCells');
})->throws(InvalidArgumentException::class);

it('merges custom cells', function (): void {
  $row = new Row();

  $row->customMerge([['A1', 'B2'], ['C3', 'D4']]);

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
