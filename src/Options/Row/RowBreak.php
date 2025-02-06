<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Row;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RowBreak
{
    /**
     * Constructor.
     */
    public function __construct(
        protected int $row
    ) {
        //
    }

    /**
     * Set page break to the row.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->setBreak(
                "A{$this->row}",
                Worksheet::BREAK_ROW
            );
    }
}
