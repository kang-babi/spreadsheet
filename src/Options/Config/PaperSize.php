<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\PaperSizeOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaperSize implements OptionContract
{
  protected string $method = 'getPageSetup';
  protected string $action = 'setPaperSize';

  public function __construct(
    protected Worksheet $sheet,
    protected PaperSizeOption $option,
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
