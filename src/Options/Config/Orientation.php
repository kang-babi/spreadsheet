<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\OrientationOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Orientation implements OptionContract
{
  protected string $method = 'getPageSetup';
  protected string $action = 'setOrientation';

  public function __construct(
    protected Worksheet $sheet,
    protected OrientationOption $option,
  ) {
    // 
  }

  public function apply(): void
  {
    $this->sheet
      ->{$this->method}()
      ->{$this->action}($this->option->get());
  }
}
