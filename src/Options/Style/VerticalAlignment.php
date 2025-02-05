<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Enums\Style\VerticalAlignmentOption;

class VerticalAlignment
{
    protected string $key = 'alignment';
    public string $alignment = 'vertical';

    public function __construct(
        public VerticalAlignmentOption $option = VerticalAlignmentOption::DEFAULT,
    ) {
        //
    }

    public function get(): array
    {
        return [
            $this->alignment => $this->option->get(),
        ];
    }
}
