<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Enums\Style\BorderLocationOption;
use KangBabi\Spreadsheet\Enums\Style\BorderStyleOption;

class Border
{
    protected string $key = 'borderStyle';

    public function __construct(
        public BorderLocationOption $location,
        public BorderStyleOption $style,
    ) {
        //
    }

    public function get(): array
    {
        return [
            $this->key => $this->style->get()
        ];
    }
}
