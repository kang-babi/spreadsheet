<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Style;

use KangBabi\Spreadsheet\Enums\Style\FillOption;

it('parses fill option from string', function (): void {
    expect(FillOption::from('solid')->get())->toBe('solid');
    expect(FillOption::from('none')->get())->toBe('none');
    expect(FillOption::from('default')->get())->toBe('solid');
});
