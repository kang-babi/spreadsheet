<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use KangBabi\Spreadsheet\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
