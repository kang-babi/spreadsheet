<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Misc\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Value implements OptionContract
{
    protected string $method = 'setCellValue';

    /**
     * Constructor.
     */
    public function __construct(
        public string $cell,
        public string|float|int|RichText $value,
    ) {
    }

    /**
     * Set the value to the cell.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}(
                $this->cell,
                $this->value instanceof RichText ?
                $this->value->get() :
                $this->value
            );
    }
}
