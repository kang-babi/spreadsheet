<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Style;

enum BorderLocationOption: string
{
    case TOP = 'top';
    case BOTTOM = 'bottom';
    case LEFT = 'left';
    case RIGHT = 'right';
    case ALL = 'all';
    case OUTLINE = 'outline';

    public function get(): string
    {
        return match ($this) {
            self::TOP => 'top',
            self::BOTTOM => 'bottom',
            self::LEFT => 'left',
            self::RIGHT => 'right',
            self::ALL => 'allBorders',
            self::OUTLINE => 'outline',
        };
    }
}
