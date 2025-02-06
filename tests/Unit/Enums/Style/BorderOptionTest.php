<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Style\BorderLocationOption;
use KangBabi\Spreadsheet\Enums\Style\BorderStyleOption;
use PhpOffice\PhpSpreadsheet\Style\Border;

it('parses border style from string', function (): void {
    expect(BorderStyleOption::from('default')->get())->toBe(Border::BORDER_THIN);
    expect(BorderStyleOption::from('none')->get())->toBe(Border::BORDER_NONE);
    expect(BorderStyleOption::from('dash')->get())->toBe(Border::BORDER_DASHED);
    expect(BorderStyleOption::from('dash-dot')->get())->toBe(Border::BORDER_DASHDOT);
    expect(BorderStyleOption::from('dot')->get())->toBe(Border::BORDER_DOTTED);
    expect(BorderStyleOption::from('thin')->get())->toBe(Border::BORDER_THIN);
    expect(BorderStyleOption::from('thick')->get())->toBe(Border::BORDER_THICK);
});

it('parses border location from string', function (): void {
    expect(BorderLocationOption::from('top')->get())->toBe('top');
    expect(BorderLocationOption::from('bottom')->get())->toBe('bottom');
    expect(BorderLocationOption::from('left')->get())->toBe('left');
    expect(BorderLocationOption::from('right')->get())->toBe('right');
    expect(BorderLocationOption::from('all')->get())->toBe('allBorders');
    expect(BorderLocationOption::from('outline')->get())->toBe('outline');
});
