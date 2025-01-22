<?php

namespace KangBabi\Contracts;

use Closure;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface WrapperContract
{
  // public function row(string $config, array|Closure $closure): static;

  public function apply(Worksheet $sheet): int;

  public function getContent(): array;
}
