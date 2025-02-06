<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Row\DataType;
use KangBabi\Spreadsheet\Text\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ValueExplicit implements OptionContract
{
    protected string $method = 'setCellValueExplicit';

    public function __construct(
        public string $cell,
        public string|float|int|RichText $value,
        public DataType $option = DataType::STRING,
    ) {
        //
    }

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
