<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Config;

enum FitOption: string
{
    case PAGE = 'page';
    case WIDTH = 'width';
    case HEIGHT = 'height';

    /**
     * Get the equivalent method to Worksheet.
     */
    public function get(): string
    {
        return match ($this) {
            self::PAGE => 'setFitToPage',
            self::WIDTH => 'setFitToWidth',
            self::HEIGHT => 'setFitToHeight'
        };
    }
}
