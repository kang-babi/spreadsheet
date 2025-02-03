<?php

namespace KangBabi\Spreadsheet\Contracts;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface OptionContract
{
  public function __construct(Worksheet $sheet);

  public function apply(): void;
}
