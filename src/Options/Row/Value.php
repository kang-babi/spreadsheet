<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Text\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Value implements OptionContract
{
    protected string $method = 'setCellValue';

    public function __construct(
        public string $cell,
        public string|float|int|RichText $value,
    ) {
    }

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
