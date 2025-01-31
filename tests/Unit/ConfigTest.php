<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Unit;

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

it('sets orientation', function (): void {
    $config = new Config();
    $config->orientation('landscape');

    expect($config->getContent())->toHaveKey('getPageSetup');
    expect($config->getContent()['getPageSetup'][0]['action'])->toBe('setOrientation');
    expect($config->getContent()['getPageSetup'][0]['value'])->toBe(PageSetup::ORIENTATION_LANDSCAPE);
});

it('sets page fit', function (): void {
    $config = new Config();
    $config->pageFit('page', true);

    expect($config->getContent())->toHaveKey('getPageSetup');
    expect($config->getContent()['getPageSetup'][0]['action'])->toBe('setFitToPage');
    expect($config->getContent()['getPageSetup'][0]['value'])->toBeTrue();
});

it('sets margin', function (): void {
    $config = new Config();
    $config->margin('top', 1.5);

    expect($config->getContent())->toHaveKey('getPageMargins');
    expect($config->getContent()['getPageMargins'][0]['action'])->toBe('setTop');
    expect($config->getContent()['getPageMargins'][0]['value'])->toBe(1.5);
});

it('sets paper size', function (): void {
    $config = new Config();
    $config->paperSize('a4');

    expect($config->getContent())->toHaveKey('getPageSetup');
    expect($config->getContent()['getPageSetup'][0]['action'])->toBe('setPaperSize');
    expect($config->getContent()['getPageSetup'][0]['value'])->toBe(PageSetup::PAPERSIZE_A4);
});

it('sets column width', function (): void {
    $config = new Config();
    $config->columnWidth('A', 20);

    expect($config->getContent())->toHaveKey('getColumnDimension');
    expect($config->getContent()['getColumnDimension'][0]['action'])->toBe('setWidth');
    expect($config->getContent()['getColumnDimension'][0]['value'])->toBe(20);
});

it('sets repeat rows', function (): void {
    $config = new Config();
    $config->repeatRows(1, 5);

    expect($config->getContent())->toHaveKey('getPageSetup');
    expect($config->getContent()['getPageSetup'][0]['action'])->toBe('setRowsToRepeatAtTopByStartAndEnd');
    expect($config->getContent()['getPageSetup'][0]['value'])->toBe([1, 5]);
});

it('applies config to worksheet', function (): void {
    $worksheet = (new Sheet())->getActiveSheet();
    $config = new Config();
    $orientation = 'landscape';
    $pageFit = true;
    $marginTop = 1.5;
    $paperSize = 'a4';
    [$column, $columnWidth] = ['A', (int)20];
    [$repeatStart, $repeatEnd] = [1, 5];
    $config->orientation($orientation)
        ->pageFit('page', $pageFit)
        ->margin('top', $marginTop)
        ->paperSize($paperSize)
        ->columnWidth($column, $columnWidth)
        ->repeatRows($repeatStart, $repeatEnd);

    $config->apply($worksheet);

    $worksheetOrientation = $worksheet->getPageSetup()->getOrientation();
    $worksheetPageFit = $worksheet->getPageSetup()->getFitToPage();
    $worksheetMarginTop = $worksheet->getPageMargins()->getTop();
    $worksheetPaperSize = $worksheet->getPageSetup()->getPaperSize();
    $worksheetColumnWidth = (int) $worksheet->getColumnDimension($column)->getWidth();
    [$worksheetRepeatStart, $worksheetRepeatEnd] = $worksheet->getPageSetup()->getRowsToRepeatAtTop();

    expect($worksheetOrientation)->toBe($orientation);
    expect($worksheetPageFit)->toBe($pageFit);
    expect($worksheetMarginTop)->toBe($marginTop);
    expect($worksheetPaperSize)->toBe(PageSetup::PAPERSIZE_A4);
    expect($worksheetColumnWidth)->toBe($columnWidth);
    expect([$worksheetRepeatStart, $worksheetRepeatEnd])->toBe([$repeatStart, $repeatEnd]);
});

it('gets columns', function (): void {
    $config = new Config();

    expect($config->getColumns())->toBe(['A']);

    $config->columnWidth('A', 20);

    expect($config->getColumns())->toBe(['A']);

    $config->columnWidth('B', 20);

    expect($config->getColumns())->toBe(['A', 'B']);
});
