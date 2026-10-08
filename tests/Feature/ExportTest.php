<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Misc\Color;
use KangBabi\Spreadsheet\Misc\Image;
use KangBabi\Spreadsheet\Misc\RichText;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use KangBabi\Spreadsheet\Wrappers\Row;
use KangBabi\Spreadsheet\Wrappers\Style;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText as SpreadsheetRichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

it('exports worksheet content formatting and configuration to a readable xlsx', function (bool $wrapText): void {
    $colors = Color::make()->set('heading', 'FF112233');
    $sheet = new Sheet();
    $sheet->config(fn (Config $config) => $config
        ->orientation('landscape')
        ->columnWidth('A', 24)
        ->columnWidth('B', 18)
        ->repeatRows(1, 1));
    $sheet->body(function (Builder $body) use ($colors): void {
        $body->row(fn (Row $row) => $row
            ->merge('A', 'B')
            ->value('A', 'Sales report')
            ->height(30)
            ->style('A:B', fn (Style $style) => $style->bold()->fill($colors->get('heading'))));
        $body->row(fn (Row $row) => $row
            ->value('A', '00123', 'string')
            ->value('B', 25, 'numeric')
            ->break());
        $body->row(fn (Row $row) => $row
            ->value('A', RichText::make()->text('Total')->bold()->text(' sales'))
            ->value('B', '=SUM(B2:B2)', 'formula'));
    });
    Image::make()->from('D1')->source(dirname(__DIR__, 2) . '/images/qr.png')->apply($sheet->getActiveSheet());
    $path = $sheet->write('feature-export-', $wrapText);

    try {
        $export = IOFactory::load($path);
        $worksheet = $export->getActiveSheet();

        expect($worksheet->getCell('A1')->getValue())->toBe('Sales report');
        expect($worksheet->getMergeCells())->toContain('A1:B1');
        expect($worksheet->getRowDimension(1)->getRowHeight())->toBe(30.0);
        expect($worksheet->getColumnDimension('A')->getWidth())->toBe(24.0);
        expect($worksheet->getStyle('A1')->getFont()->getBold())->toBeTrue();
        expect($worksheet->getStyle('A1')->getFill()->getStartColor()->getARGB())->toBe('FF112233');
        expect($worksheet->getStyle('A1')->getAlignment()->getWrapText())->toBe($wrapText);
        expect($worksheet->getCell('A2')->getValue())->toBe('00123');
        expect($worksheet->getCell('B2')->getValue())->toBe(25);
        expect($worksheet->getCell('B3')->getValue())->toBe('=SUM(B2:B2)');
        expect($worksheet->getCell('B3')->getCalculatedValue())->toBe(25);
        expect($worksheet->getBreaks())->toHaveKey('A2', Worksheet::BREAK_ROW);
        expect($worksheet->getPageSetup()->getOrientation())->toBe('landscape');
        expect($worksheet->getPageSetup()->getRowsToRepeatAtTop())->toBe(['1', '1']);
        expect($worksheet->getDrawingCollection())->toHaveCount(1);

        $richText = $worksheet->getCell('A3')->getValue();
        expect($richText)->toBeInstanceOf(SpreadsheetRichText::class);
        expect($richText->getPlainText())->toBe('Total sales');
        expect($richText->getRichTextElements()[0]->getFont()->getBold())->toBeTrue();
        $export->disconnectWorksheets();
    } finally {
        unlink($path);
    }
})->with([true, false]);
