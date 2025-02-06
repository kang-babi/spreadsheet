<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Height implements OptionContract
{
    protected string $method = 'getRowDimension';
    protected string $option = 'setRowHeight';

    /**
     * Constructor.
     */
    public function __construct(
        public int $row,
        public int|float $height,
    ) {
        //
    }

    /**
     * Set row height to the sheet.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}($this->row)
            ->{$this->option}($this->height);
    }
}
