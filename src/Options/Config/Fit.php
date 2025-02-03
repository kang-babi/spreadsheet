<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\FitOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Fit implements OptionContract
{
  protected string $method = 'getPageSetup';

  public function __construct(
    protected Worksheet $sheet,
    protected FitOption $option,
  ) {
    // 
  }

  public function apply(): void
  {
    $this->sheet
      ->{$this->method}
      ->{$this->option->get()}(true);
  }
}
