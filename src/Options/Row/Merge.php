<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Merge implements OptionContract
{
    protected string $method = 'mergeCells';

    /**
     * Constructor.
     */
    public function __construct(
        public string $from,
        public string $to,
    ) {
        //
    }

    /**
     * Apply cell merge to the cell.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}("{$this->from}:{$this->to}");
    }
}
