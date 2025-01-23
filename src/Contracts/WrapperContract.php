<?php

declare(strict_types=1);

namespace KangBabi\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface WrapperContract
{
    public function apply(Worksheet $sheet): int;

    public function getContent(): array;
}
