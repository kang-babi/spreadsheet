<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\IOFactory;

it('exports header body and footer consecutively without overwriting them', function (): void {
    $sheet = new Sheet();
    $sheet->header(fn (Builder $header) => $header
        ->row(fn (Row $row) => $row->value('A', 'Heading'))
        ->row(fn (Row $row) => $row->value('A', 'Columns')));
    $sheet->body(fn (Builder $body) => $body
        ->row(fn (Row $row) => $row->value('A', 'First'))
        ->row(fn (Row $row) => $row->value('A', 'Second')));
    $sheet->footer(fn (Builder $footer) => $footer->row(fn (Row $row) => $row->value('A', 'Total')));

    for ($attempt = 0; $attempt < 2; $attempt++) {
        $path = $sheet->write('section-layout-', false);

        try {
            $export = IOFactory::load($path);
            expect($export->getActiveSheet()->rangeToArray('A1:A5'))->toBe([
                ['Heading'], ['Columns'], ['First'], ['Second'], ['Total'],
            ]);
            $export->disconnectWorksheets();
        } finally {
            $sheet->cleanup();
        }
    }
});

it('starts the footer after body rows when the header is omitted', function (): void {
    $sheet = new Sheet();
    $sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Body')));
    $sheet->footer(fn (Builder $footer) => $footer->row(fn (Row $row) => $row->value('A', 'Footer')));
    $path = $sheet->write('omitted-header-', false);

    try {
        $export = IOFactory::load($path);
        expect($export->getActiveSheet()->rangeToArray('A1:A2'))->toBe([['Body'], ['Footer']]);
        $export->disconnectWorksheets();
    } finally {
        $sheet->cleanup();
    }
});

it('starts the footer after the header when the body is omitted', function (): void {
    $sheet = new Sheet();
    $sheet->header(fn (Builder $header) => $header->row(fn (Row $row) => $row->value('A', 'Heading')));
    $sheet->footer(fn (Builder $footer) => $footer->row(fn (Row $row) => $row->value('A', 'Footer')));
    $path = $sheet->write('omitted-body-', false);

    try {
        $export = IOFactory::load($path);
        expect($export->getActiveSheet()->rangeToArray('A1:A2'))->toBe([['Heading'], ['Footer']]);
        $export->disconnectWorksheets();
    } finally {
        $sheet->cleanup();
    }
});
