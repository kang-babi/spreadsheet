<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

use PhpOffice\PhpSpreadsheet\Style\Fill;

enum FillOption: string
{
    case SOLID = 'solid';
    case NONE = 'none';
    case DEFAULT = 'default';

    /**
     * Get the corresponding fill type.
     */
    public function get(): string
    {
        return match ($this) {
            self::SOLID => Fill::FILL_SOLID,
            self::NONE => Fill::FILL_NONE,
            self::DEFAULT => Fill::FILL_SOLID,
        };
    }
}
