<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Style\Underline;
use PhpOffice\PhpSpreadsheet\Style\Font;

it('parses underline options from string', function (): void {
    expect(Underline::from('default')->get())->toBe(Font::UNDERLINE_SINGLE);
    expect(Underline::from('none')->get())->toBe(Font::UNDERLINE_NONE);
    expect(Underline::from('single')->get())->toBe(Font::UNDERLINE_SINGLE);
    expect(Underline::from('double')->get())->toBe(Font::UNDERLINE_DOUBLE);
});
