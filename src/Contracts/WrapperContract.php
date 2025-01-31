<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface WrapperContract
{
    /**
     * Apply the wrapper's content to the given worksheet.
     *
     * @param Worksheet $sheet The worksheet to apply the content to.
     * @return int The current row index after applying the content.
     */
    public function apply(Worksheet $sheet): int;

    /**
     * Get the content of the wrapper.
     *
     * @return array<mixed, mixed> The content of the wrapper.
     */
    public function getContent(): array;
}
