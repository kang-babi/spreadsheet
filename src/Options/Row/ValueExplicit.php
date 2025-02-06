<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Row\DataType;
use KangBabi\Spreadsheet\Misc\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ValueExplicit implements OptionContract
{
    protected string $method = 'setCellValueExplicit';

    /**
     * Constructor.
     */
    public function __construct(
        public string $cell,
        public string|float|int|RichText $value,
        public DataType $option = DataType::STRING,
    ) {
        //
    }

    /**
     * Set the typed value to the cell.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}(
                $this->cell,
                $this->value,
                $this->option->get()
            );
    }
}
