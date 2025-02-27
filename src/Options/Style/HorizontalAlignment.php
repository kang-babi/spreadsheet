<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Enums\Style\HorizontalAlignmentOption;

class HorizontalAlignment
{
    public string $alignment = 'horizontal';
    protected string $key = 'alignment';

    /**
     * Constructor.
     */
    public function __construct(
        public HorizontalAlignmentOption $option = HorizontalAlignmentOption::DEFAULT,
    ) {
        //
    }

    /**
     * Get Alignment.
     *
     * @return array<string, string>
     */
    public function get(): array
    {
        return [
            $this->alignment => $this->option->get(),
        ];
    }
}
