<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Misc\RichText;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use KangBabi\Spreadsheet\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Font;

it('wraps all worksheet cells independently of configured widths and builder counters', function (bool $wrapText): void {
    $sheet = new Sheet();
    $sheet->config(fn (Config $config) => $config->columnWidth('Z', 12)->columnWidth('AA', 12));
    $sheet->body(function (Builder $body): void {
        $body->row(fn (Row $row) => $row->value('B', 'Text in a column without a configured width'));
        $body->jump(3)->row(fn (Row $row) => $row
            ->merge('AA', 'AB')
            ->value('AA', 'Long text in merged cells beyond column Z'));
    });
    $sheet->getActiveSheet()->setCellValue('AC10', 'Text added directly to the worksheet');
    $path = $sheet->write('text-wrapping-', $wrapText);

    try {
        $export = IOFactory::load($path);
        $worksheet = $export->getActiveSheet();

        foreach (['B1', 'AA5', 'AB5', 'AC10'] as $cell) {
            expect($worksheet->getStyle($cell)->getAlignment()->getWrapText())->toBe($wrapText);
        }

        $export->disconnectWorksheets();
    } finally {
        $sheet->cleanup();
    }
})->with([true, false]);

it('sizes rows for explicit line breaks using the tallest cell and preserves manual heights', function (bool $wrapText): void {
    $sheet = new Sheet();
    $sheet->body(fn (Builder $body) => $body
        ->row(fn (Row $row) => $row
            ->value('G', "Monday\nWednesday\nFriday")
            ->value('H', "First\nSecond"))
        ->row(fn (Row $row) => $row->height(60)->value('G', "Monday\nTuesday"))
        ->row(fn (Row $row) => $row->value('G', 'Single line'))
        ->row(fn (Row $row) => $row->value('G', "First\r\nSecond\rThird\nFourth"))
        ->row(fn (Row $row) => $row->value('G', RichText::make()->text("First\nSecond")->size(22))));
    $path = $sheet->write('line-break-heights-', $wrapText);

    try {
        $export = IOFactory::load($path);
        $worksheet = $export->getActiveSheet();
        $lineHeight = Font::getDefaultRowHeightByFont($worksheet->getStyle('G1')->getFont());

        expect($worksheet->getCell('G1')->getValue())->toBe("Monday\nWednesday\nFriday");
        expect($worksheet->getStyle('G1')->getAlignment()->getWrapText())->toBe($wrapText);
        expect($worksheet->getRowDimension(1)->getRowHeight())->toBe($wrapText ? 3 * $lineHeight : -1.0);
        expect($worksheet->getRowDimension(2)->getRowHeight())->toBe(60.0);
        expect($worksheet->getRowDimension(3)->getRowHeight())->toBe(-1.0);
        expect($worksheet->getRowDimension(4)->getRowHeight())->toBe($wrapText ? 4 * $lineHeight : -1.0);
        expect($worksheet->getRowDimension(5)->getRowHeight())->toBe($wrapText ? 4 * $lineHeight : -1.0);

        $export->disconnectWorksheets();
    } finally {
        $sheet->cleanup();
    }
})->with([true, false]);

it('wraps multiple body rows without column configuration or a footer', function (): void {
    $sheet = new Sheet();
    $sheet->body(fn (Builder $body) => $body
        ->row(fn (Row $row) => $row->value('A', 'First line'))
        ->row(fn (Row $row) => $row->value('C', "Second line\nwith a newline")));
    $path = $sheet->write('text-wrapping-defaults-');

    try {
        $export = IOFactory::load($path);

        expect($export->getActiveSheet()->getStyle('A1')->getAlignment()->getWrapText())->toBeTrue();
        expect($export->getActiveSheet()->getStyle('C2')->getAlignment()->getWrapText())->toBeTrue();

        $export->disconnectWorksheets();
    } finally {
        $sheet->cleanup();
    }
});
