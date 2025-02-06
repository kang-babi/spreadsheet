<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Config;

enum MarginOption: string
{
    case TOP = 'top';
    case BOTTOM = 'bottom';
    case LEFT = 'left';
    case RIGHT = 'right';

    /**
     * Get the equivalent method to Worksheet.
     */
    public function get(): string
    {
        return match ($this) {
            self::TOP => 'setTop',
            self::BOTTOM => 'setBottom',
            self::LEFT => 'setLeft',
            self::RIGHT => 'setRight',
        };
    }
}
