<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StyleOption implements OptionContract
{
    protected string $method = 'getStyle';
    protected string $action = 'applyFromArray';

    /**
     * Summary of __construct
     * @param array<string, array<string, int|string>|string> $styles
     */
    public function __construct(
        public string $cell,
        public array $styles = [],
    ) {
        //
    }

    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}($this->cell)
            ->{$this->action}($this->styles);
    }
}
