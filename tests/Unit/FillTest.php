<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Options\Style\Fill;
use KangBabi\Spreadsheet\Enums\Style\FillOption;
use KangBabi\Spreadsheet\Misc\Color;
use KangBabi\Spreadsheet\Wrappers\Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

it('tests fill default option', function (): void {
    $color = 'FF0000';
    $fill = new Fill($color);

    $expected = [
        'fillType' => FillOption::DEFAULT->get(),
        'startColor' => [
            'argb' => $color
        ]
    ];

    expect($fill->get())->toBe($expected);
});

it('tests fill custom option', function (): void {
    $color = '00FF00';
    $option = FillOption::SOLID;
    $fill = new Fill($color, $option);

    $expected = [
        'fillType' => $option->get(),
        'startColor' => [
            'argb' => $color
        ]
    ];

    expect($fill->get())->toBe($expected);
});

it('applies fill to worksheet', function (): void {
    $colors = Color::make()
        ->set('primary', '696cff')
        ->set('secondary', '8592a3')
        ->set('success', '71dd37')
        ->set('info', '03c3ec')
        ->set('warning', 'ffab00')
        ->set('danger', 'ff3e1d')
        ->set('light', 'fcfdfd')
        ->set('dark', '233446')
        ->default('primary');

    $style = new Style('A1');

    $color = $colors->primary;

    $style->fill($color);

    $sheet = (new Spreadsheet())->getActiveSheet();

    $style->apply($sheet);

    $appliedStyle = $sheet->getStyle('A1')->getFill()->getStartColor()->getARGB();

    expect($appliedStyle)->toBe($color);
});
