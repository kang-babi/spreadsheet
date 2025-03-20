<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Style\UnderlineOption;
use PhpOffice\PhpSpreadsheet\Style\Font;

it('parses underline options from string', function (): void {
    expect(UnderlineOption::from('default')->get())->toBe(Font::UNDERLINE_SINGLE);
    expect(UnderlineOption::from('none')->get())->toBe(Font::UNDERLINE_NONE);
    expect(UnderlineOption::from('single')->get())->toBe(Font::UNDERLINE_SINGLE);
    expect(UnderlineOption::from('double')->get())->toBe(Font::UNDERLINE_DOUBLE);
});
