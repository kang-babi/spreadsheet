<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepeatRow implements OptionContract
{
  public string $method = 'getPageSetup';
  public string $action = 'setRowsToRepeatAtTopByStartAndEnd';

  public function __construct(
    protected Worksheet $sheet,
    protected int $from = 1,
    protected int $to = 5,
  ) {
    // 
  }

  public function apply(): void
  {
    $this->sheet
      ->{$this->method}()
      ->{$this->action}($this->from, $this->to);
  }
}
