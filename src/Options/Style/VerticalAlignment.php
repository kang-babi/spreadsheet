<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Enums\Style\VerticalAlignmentOption;

class VerticalAlignment
{
    public string $alignment = 'vertical';
    protected string $key = 'alignment';

    /**
     * Constructor.
     */
    public function __construct(
        public VerticalAlignmentOption $option = VerticalAlignmentOption::DEFAULT,
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
