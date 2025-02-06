<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RowBreak
{
    public function __construct(protected int $row)
    {
        //
    }

    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->setBreak(
                "A{$this->row}",
                Worksheet::BREAK_ROW
            );
    }
}
