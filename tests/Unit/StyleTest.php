<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Style\BorderStyleOption;
use KangBabi\Spreadsheet\Enums\Style\VerticalAlignmentOption;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Enums\Style\HorizontalAlignmentOption;
use KangBabi\Spreadsheet\Options\Style\HorizontalAlignment;
use KangBabi\Spreadsheet\Options\Style\VerticalAlignment;
use KangBabi\Spreadsheet\Wrappers\Style;

it('instantiates a style', function (): void {
    $style = new Style('A1');

    expect($style)->toBeInstanceOf(Style::class);
    expect($style)->toBeInstanceOf(WrapperContract::class);
});

it('sets styles', function (): void {
    $column = 'A1';

    $style = new Style($column);

    $style
        ->bold()
        ->alignment('horizontal', 'justify')
        ->border('top');

    expect($style->getContent()['font']->bold)->toBe(true);
    expect($style->getContent()['borders'][0]->style->get())->toBe(BorderStyleOption::DEFAULT->get());
});

it('sets font styles to sheet', function (): void {
    $sheet = new Sheet();

    $style = new Style('A1');

    $style
        ->fontName('Times New Roman')
        ->size(11)
        ->bold()
        ->italic()
        ->underline()
        ->strikethrough();

    $style->apply($sheet->getActiveSheet());

    $fontName = $sheet->getActiveSheet()->getStyle($style->getCell())->getFont()->getName();
    $fontSize = (int) $sheet->getActiveSheet()->getStyle($style->getCell())->getFont()->getSize();
    $bold = $sheet->getActiveSheet()->getStyle($style->getCell())->getFont()->getBold();
    $italic = $sheet->getActiveSheet()->getStyle($style->getCell())->getFont()->getItalic();
    $underline = $sheet->getActiveSheet()->getStyle($style->getCell())->getFont()->getUnderline();
    $strikethough = $sheet->getActiveSheet()->getStyle($style->getCell())->getFont()->getStrikethrough();

    expect($fontName)->toBe('Times New Roman');
    expect($fontSize)->toBe(11);
    expect($bold)->toBe(true);
    expect($italic)->toBe(true);
    expect($underline)->toBe('single');
    expect($strikethough)->toBe(true);
});

it('applies styles to worksheet', function (): void {
    $worksheet = (new Sheet())->getActiveSheet();

    $style = [
        'font' => [
            'size' => 11,
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => HorizontalAlignmentOption::JUSTIFY->get(),
            'vertical' => VerticalAlignmentOption::CENTER->get(),
        ],
        'borders' => [
            'top' => BorderStyleOption::DEFAULT->get(),
        ]
    ];

    $cell = 'A1';

    $styleWrapper = new Style($cell);

    $styleWrapper
        ->size(11)
        ->bold()
        ->alignment('horizontal', 'justify')
        ->alignment('vertical', 'center')
        ->border('top')
        ->apply($worksheet);

    $worksheetStyle = [
        'font' => [
            'size' => (int) $worksheet->getStyle($cell)->getFont()->getSize(),
            'bold' => $worksheet->getStyle($cell)->getFont()->getBold(),
        ],
        'alignment' => [
            'horizontal' => $worksheet->getStyle($cell)->getAlignment()->getHorizontal(),
            'vertical' => $worksheet->getStyle($cell)->getAlignment()->getVertical(),
        ],
        'borders' => [
            'top' => $worksheet->getStyle($cell)->getBorders()->getTop()->getBorderStyle(),
        ]
    ];

    expect($worksheetStyle)->tobe($style);
});

it('gets content', function (): void {
    $style = new Style('A1');

    $style->size(11)
        ->bold();

    expect($style->getContent()['font']->bold)->toBe(true);
    expect($style->getContent()['font']->size)->toBe(11);
});

it('sets alignment', function (): void {
    $cell = 'A1';

    $style = new Style($cell);

    $style
        ->alignment('vertical', 'center')
        ->alignment('horizontal', 'center');

    expect($style->getContent()['alignments'][0])->toBeInstanceOf(VerticalAlignment::class);
    expect($style->getContent()['alignments'][1])->toBeInstanceOf(HorizontalAlignment::class);

    expect($style->getContent()['alignments'][0]->get())->toBe([
        'vertical' => VerticalAlignmentOption::CENTER->get()
    ]);
    expect($style->getContent()['alignments'][1]->get())->toBe([
        'horizontal' => HorizontalAlignmentOption::CENTER->get()
    ]);
});


it('sets alignment (new method)', function (): void {
    $cell = 'A1';

    $style = new Style($cell);

    $style
        ->vertical('center')
        ->horizontal('center');

    expect($style->getContent()['alignments'][0])->toBeInstanceOf(VerticalAlignment::class);
    expect($style->getContent()['alignments'][1])->toBeInstanceOf(HorizontalAlignment::class);

    expect($style->getContent()['alignments'][0]->get())->toBe([
        'vertical' => VerticalAlignmentOption::CENTER->get()
    ]);
    expect($style->getContent()['alignments'][1]->get())->toBe([
        'horizontal' => HorizontalAlignmentOption::CENTER->get()
    ]);
});
