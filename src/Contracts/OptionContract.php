<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface OptionContract
{
    /**
     * Apply the Option content.
     */
    public function apply(Worksheet $sheet): void;
}
