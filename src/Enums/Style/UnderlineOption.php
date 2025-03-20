<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

use PhpOffice\PhpSpreadsheet\Style\Font;

enum UnderlineOption: string
{
    case DEFAULT = 'default';
    case NONE = 'none';
    case SINGLE = 'single';
    case DOUBLE = 'double';

    /**
     * Get the corresponding underline style.
     */
    public function get(): string
    {
        return match ($this) {
            self::NONE => Font::UNDERLINE_NONE,
            self::SINGLE => Font::UNDERLINE_SINGLE,
            self::DOUBLE => Font::UNDERLINE_DOUBLE,
            self::DEFAULT => Font::UNDERLINE_SINGLE,
        };
    }
}
