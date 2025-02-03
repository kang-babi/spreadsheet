<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\MarginOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Margin implements OptionContract
{
  protected string $method = 'getPageMargins';

  public function __construct(
    protected Worksheet $sheet,
    protected MarginOption $option,
    protected int|float $margin,

  ) {
    // 
  }

  public function apply(): void
  {
    $this->sheet
      ->{$this->method}()
      ->{$this->option->get()}($this->margin);
  }
}
