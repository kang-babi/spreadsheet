<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface WrapperContract
{
    /**
     * Apply the wrapper's content.
     *
     * @param Worksheet $sheet The worksheet.
     *
     * @return int The current row index.
     */
    public function apply(Worksheet $sheet): int;

    /**
     * Get the wrapper content.
     *
     * @return array<mixed, mixed> The content.
     */
    public function getContent(): array;
}
