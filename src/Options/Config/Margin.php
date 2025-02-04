<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\MarginOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Margin implements OptionContract
{
    protected string $method = 'getPageMargins';

    public function __construct(
        public MarginOption $option,
        public int|float $margin,
    ) {
        //
    }

    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}()
            ->{$this->option->get()}($this->margin);
    }
}
