<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * @template TKey of array-key
 * 
 * @template TValue
 */
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
     * @return array<TKey, TValue> The content.
     */
    public function getContent(): array;
}
