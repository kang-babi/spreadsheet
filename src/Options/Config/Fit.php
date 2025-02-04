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
        public FitOption $option = FitOption::WIDTH,
        public bool|int $isFit = 1,
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
