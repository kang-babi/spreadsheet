<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Enums\Style\FillOption;

class Fill
{
    protected string $key = 'fillType';

    public function __construct(
        public string $color,
        public FillOption $option = FillOption::DEFAULT,
    ) {
        //
    }

    /**
     * Get Fill.
     * @return array<string, array<string, string>|string>
     */
    public function get(): array
    {
        return [
            $this->key => $this->option->get(),
            'startColor' => [
                'argb' => $this->color
            ]
        ];
    }
}
