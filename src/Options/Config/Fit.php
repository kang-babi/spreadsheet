<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\FitOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Fit implements OptionContract
{
    protected string $method = 'getPageSetup';

    public function __construct(
        protected FitOption $option,
        protected bool $isFit = true,
    ) {
        //
    }

    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}()
            ->{$this->option->get()}($this->isFit);
    }
}
