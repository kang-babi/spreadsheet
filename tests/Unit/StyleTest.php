<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Style;

it('sets styles', function (): void {
    $style = new Style('A1');
    $styles = [
        'font' => [
            'bold' => true,
        ],
    ];

    $style->style($styles);

    expect($style->getContent())->toBe($styles);
});

it('applies styles to worksheet', function (): void {
    $worksheet = (new Sheet())->getActiveSheet();

    $style = [
        'font' => [
            'size' => 12,
            'bold' => true,
        ]
    ];

    $cell = 'A1';

    $styleWrapper = new Style($cell, $style);

    $styleWrapper->apply($worksheet);

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
            'bold' => true,
        ],
    ];

    $style = new Style('A1', $styles);

    expect($style->getContent())->toBe($styles);
});
