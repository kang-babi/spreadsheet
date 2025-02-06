<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

enum HorizontalAlignmentOption: string
{
    case DEFAULT = 'default';
    case LEFT = 'left';
    case RIGHT = 'right';
    case CENTER = 'center';
    case JUSTIFY = 'justify';

    public function get(): string
    {
        return match ($this) {
            self::LEFT => Alignment::HORIZONTAL_LEFT,
            self::RIGHT => Alignment::HORIZONTAL_RIGHT,
            self::CENTER => Alignment::HORIZONTAL_CENTER,
            self::JUSTIFY => Alignment::HORIZONTAL_JUSTIFY,
            self::DEFAULT => Alignment::HORIZONTAL_GENERAL,
        };
    }
}
