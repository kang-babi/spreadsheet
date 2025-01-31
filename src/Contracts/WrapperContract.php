<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface WrapperContract
{
    public function apply(Worksheet $sheet): int;

    /**
     * @return array<mixed, mixed>
     */
    public function getContent(): array;
}
