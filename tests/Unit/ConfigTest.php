<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Unit;

use KangBabi\Spreadsheet\Enums\Config\PaperSizeOption;
use KangBabi\Spreadsheet\Options\Config\Fit;
use KangBabi\Spreadsheet\Options\Config\Margin;
use KangBabi\Spreadsheet\Options\Config\Orientation;
use KangBabi\Spreadsheet\Options\Config\PaperSize;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

it('sets orientation', function (): void {
    $config = new Config();
    $config->orientation('landscape');

    expect($config->getContent())->toHaveKey('orientation');
    expect($config->getContent()['orientation'])->toBeInstanceOf(Orientation::class);
});

it('sets page fit', function (): void {
    $config = new Config();
    $config->pageFit('page', true);

    expect($config->getContent())->toHaveKey('fits');
    expect($config->getContent()['fits'][0])->toBeInstanceOf(Fit::class);
});

it('sets margin', function (): void {
    $config = new Config();
    $config
        ->margin('top', 1.5)
        ->margin('bottom', 1.5)
        ->margin('left', 1.5)
        ->margin('right', 1.5);

    expect($config->getContent())->toHaveKey('margins');
    expect($config->getContent()['margins'])->toHaveCount(4);
    expect($config->getContent()['margins'][0])->toBeInstanceOf(Margin::class);
});

it('sets paper size', function (): void {
    $config = new Config();

    $config->paperSize('a4');
    expect($config->getContent()['paperSize']->option)->toBe(PaperSizeOption::A4);

    $config->paperSize('letter');
    expect($config->getContent()['paperSize']->option)->toBe(PaperSizeOption::LETTER);

    $config->paperSize('legal');
    expect($config->getContent()['paperSize']->option)->toBe(PaperSizeOption::LEGAL);

    expect($config->getContent())->toHaveKey('paperSize');
    expect($config->getContent()['paperSize'])->toBeInstanceOf(PaperSize::class);
});

it('sets column width', function (): void {
    $config = new Config();
    $config->columnWidth('A', 20);

    expect($config->getContent())->toHaveKey('orientation');
    expect($config->getContent()['orientation'])->toBeInstanceOf(Orientation::class);
});

it('sets repeat rows', function (): void {
    $config = new Config();
    $config->repeatRows(1, 5);

    expect($config->getContent())->toHaveKey('orientation');
    expect($config->getContent()['orientation'])->toBeInstanceOf(Orientation::class);
});

it('applies config to worksheet', function (): void {
    $worksheet = (new Sheet())->getActiveSheet();
    $config = new Config();
    $orientation = 'landscape';
    $pageFit = true;
    $margin = 1.5;
    $paperSize = 'a4';
    [$column, $columnWidth] = ['A', (int)20];
    [$repeatStart, $repeatEnd] = [1, 5];
    $config->orientation($orientation)
        ->pageFit('page', $pageFit)
        ->pageFit('height', $pageFit)
        ->pageFit('width', $pageFit)
        ->margin('top', $margin)
        ->margin('bottom', $margin)
        ->margin('left', $margin)
        ->margin('right', $margin)
        ->paperSize($paperSize)
        ->columnWidth($column, $columnWidth)
        ->repeatRows($repeatStart, $repeatEnd);

    $config->apply($worksheet);

    $worksheetOrientation = $worksheet->getPageSetup()->getOrientation();
    $worksheetPageFit = $worksheet->getPageSetup()->getFitToPage();
    $worksheetMarginTop = $worksheet->getPageMargins()->getTop();
    $worksheetMarginBottom = $worksheet->getPageMargins()->getBottom();
    $worksheetMarginLeft = $worksheet->getPageMargins()->getLeft();
    $worksheetMarginRight = $worksheet->getPageMargins()->getRight();

    $worksheetPaperSize = $worksheet->getPageSetup()->getPaperSize();
    $worksheetColumnWidth = (int) $worksheet->getColumnDimension($column)->getWidth();
    [$worksheetRepeatStart, $worksheetRepeatEnd] = $worksheet->getPageSetup()->getRowsToRepeatAtTop();

    expect($worksheetOrientation)->toBe($orientation);
    expect($worksheetPageFit)->toBe($pageFit);
    expect($worksheetMarginTop)->toBe($margin);
    expect($worksheetMarginBottom)->toBe($margin);
    expect($worksheetMarginLeft)->toBe($margin);
    expect($worksheetMarginRight)->toBe($margin);
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

it('updates applied paper size', function (): void {
    $config = new Config();
    $worksheet = (new Sheet())->getActiveSheet();

    $config->paperSize('a4');
    $config->apply($worksheet);

    expect($worksheet->getPageSetup()->getPaperSize())->toBe(PageSetup::PAPERSIZE_A4);

    $config->paperSize('letter');
    $config->apply($worksheet);

    expect($worksheet->getPageSetup()->getPaperSize())->toBe(PageSetup::PAPERSIZE_LETTER);

    $config->paperSize('legal');
    $config->apply($worksheet);

    expect($worksheet->getPageSetup()->getPaperSize())->toBe(PageSetup::PAPERSIZE_LEGAL);
});
