<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * An option that directly modifies a worksheet.
 */
interface OptionContract
{
    /**
     * Apply the option to the worksheet.
     */
    public function apply(Worksheet $sheet): void;
}
