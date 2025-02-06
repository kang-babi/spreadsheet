<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

enum VerticalAlignmentOption: string
{
    case DEFAULT = 'default';
    case TOP = 'top';
    case BOTTOM = 'bottom';
    case CENTER = 'center';
    case JUSTIFY = 'justify';

    public function get(): string
    {
        return match ($this) {
            self::TOP => Alignment::VERTICAL_TOP,
            self::BOTTOM => Alignment::VERTICAL_BOTTOM,
            self::CENTER => Alignment::VERTICAL_CENTER,
            self::JUSTIFY => Alignment::VERTICAL_JUSTIFY,
            self::DEFAULT => Alignment::VERTICAL_CENTER,
        };
    }
}
