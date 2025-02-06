<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Style\AlignmentOption;
use KangBabi\Spreadsheet\Enums\Style\HorizontalAlignmentOption;
use KangBabi\Spreadsheet\Enums\Style\VerticalAlignmentOption;
use KangBabi\Spreadsheet\Options\Style\HorizontalAlignment;
use KangBabi\Spreadsheet\Options\Style\VerticalAlignment;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

it('parses alignment option from string', function (): void {
    expect(AlignmentOption::from('horizontal')->get())->toBe(HorizontalAlignment::class);
    expect(AlignmentOption::from('vertical')->get())->toBe(VerticalAlignment::class);
});

it('parses horizontal alignment options from string', function (): void {
    expect(HorizontalAlignmentOption::from('left')->get())->toBe(Alignment::HORIZONTAL_LEFT);
    expect(HorizontalAlignmentOption::from('right')->get())->toBe(Alignment::HORIZONTAL_RIGHT);
    expect(HorizontalAlignmentOption::from('center')->get())->toBe(Alignment::HORIZONTAL_CENTER);
    expect(HorizontalAlignmentOption::from('justify')->get())->toBe(Alignment::HORIZONTAL_JUSTIFY);
    expect(HorizontalAlignmentOption::from('default')->get())->toBe(Alignment::HORIZONTAL_GENERAL);
});

it('passes vertical alignment option from string', function (): void {
    expect(VerticalAlignmentOption::from('default')->get())->toBe(Alignment::VERTICAL_CENTER);
    expect(VerticalAlignmentOption::from('top')->get())->toBe(Alignment::VERTICAL_TOP);
    expect(VerticalAlignmentOption::from('bottom')->get())->toBe(Alignment::VERTICAL_BOTTOM);
    expect(VerticalAlignmentOption::from('center')->get())->toBe(Alignment::VERTICAL_CENTER);
    expect(VerticalAlignmentOption::from('justify')->get())->toBe(Alignment::VERTICAL_JUSTIFY);
});
