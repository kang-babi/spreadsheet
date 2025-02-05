<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

use KangBabi\Spreadsheet\Options\Style\HorizontalAlignment;
use KangBabi\Spreadsheet\Options\Style\VerticalAlignment;

enum AlignmentOption: string
{
    case HORIZONTAL = 'horizontal';
    case VERTICAL = 'vertical';

    public function get(): string
    {
        return match ($this) {
            self::HORIZONTAL => HorizontalAlignment::class,
            self::VERTICAL => VerticalAlignment::class,
        };
    }
}
