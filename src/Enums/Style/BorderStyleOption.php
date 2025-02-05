<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

use PhpOffice\PhpSpreadsheet\Style\Border;

enum BorderStyleOption: string
{
    case DEFAULT = 'default';
    case NONE = 'none';
    case DASHED = 'dash';
    case DASHDOT = 'dash-dot';
    case DOT = 'dot';
    case THIN = 'thin';
    case THICK = 'thick';

    public function get(): string
    {
        return match ($this) {
            self::THIN => Border::BORDER_THIN,
            self::NONE => Border::BORDER_NONE,
            self::DASHED => Border::BORDER_DASHED,
            self::DASHDOT => Border::BORDER_DASHDOT,
            self::DOT => Border::BORDER_DOTTED,
            self::THICK => Border::BORDER_THICK,
            self::DEFAULT => Border::BORDER_THIN,
        };
    }
}
