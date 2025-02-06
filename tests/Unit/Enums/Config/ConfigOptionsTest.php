<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Config\FitOption;
use KangBabi\Spreadsheet\Enums\Config\MarginOption;
use KangBabi\Spreadsheet\Enums\Config\OrientationOption;
use KangBabi\Spreadsheet\Enums\Config\PaperSizeOption;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

it('parses fit option from string', function (): void {
    expect(FitOption::from('page')->get())->toBe('setFitToPage');
    expect(FitOption::from('width')->get())->toBe('setFitToWidth');
    expect(FitOption::from('height')->get())->toBe('setFitToHeight');
});

it('parses margin options from string', function (): void {
    expect(MarginOption::from('top')->get())->toBe('setTop');
    expect(MarginOption::from('bottom')->get())->toBe('setBottom');
    expect(MarginOption::from('left')->get())->toBe('setLeft');
    expect(MarginOption::from('right')->get())->toBe('setRight');
});

it('parses orientation option from string', function (): void {
    expect(OrientationOption::from('portrait')->get())->toBe(PageSetup::ORIENTATION_PORTRAIT);
    expect(OrientationOption::from('landscape')->get())->toBe(PageSetup::ORIENTATION_LANDSCAPE);
    expect(OrientationOption::from('default')->get())->toBe(PageSetup::ORIENTATION_DEFAULT);
});

it('parses paper size option from string', function (): void {
    expect(PaperSizeOption::from('a4')->get())->toBe(PageSetup::PAPERSIZE_A4);
    expect(PaperSizeOption::from('legal')->get())->toBe(PageSetup::PAPERSIZE_LEGAL);
    expect(PaperSizeOption::from('letter')->get())->toBe(PageSetup::PAPERSIZE_LETTER);
    expect(PaperSizeOption::from('default')->get())->toBe(PageSetup::PAPERSIZE_A4);
});
