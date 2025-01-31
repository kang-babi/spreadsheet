<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Wrappers\Style;

it('instantiates a style', function (): void {
    $style = new Style('A1');

    expect($style)->toBeInstanceOf(Style::class);
    expect($style)->toBeInstanceOf(WrapperContract::class);
});

it('sets styles', function (): void {
    $sheet = new Sheet();

    $column = 'A1';

    $style = new Style($column);

    $style
        ->font('bold', true)
        ->font('bold', true)
        ->border('top');

    $styles =  [
        'font' =>  [
            'bold' => true
        ],
        'borders' =>  [
            'top' =>  [
                'borderStyle' => 'thin'
            ]
        ]
    ];

    expect($style->getContent())->toBe($styles);
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
        ]
    ];

    $cell = 'A1';

    $styleWrapper = new Style($cell);

    $styleWrapper
        ->size(11)
        ->bold()
        ->apply($worksheet);

    $worksheetStyle = [
        'font' => [
            'size' => (int) $worksheet->getStyle($cell)->getFont()->getSize(),
            'bold' => $worksheet->getStyle($cell)->getFont()->getBold(),
        ]
    ];

    expect($worksheetStyle)->tobe($style);
});

it('gets content', function (): void {
    $styles = [
        'font' => [
            'size' => 11,
            'bold' => true,
        ]
    ];

    $style = new Style('A1');

    $style->size(11)
        ->bold();

    expect($style->getContent())->toBe($styles);
});
