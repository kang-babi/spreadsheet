<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ColumnWidth implements OptionContract
{
  protected string $method = 'getColumnDimension';
  protected string $action = 'setWidth';

  /**
   * Constructor.
   */
  public function __construct(
    protected Worksheet $sheet,
    protected string $column,
    protected int|float $width,
  ) {
    //
  }

  /**
   * Set column width to the sheet.
   */
  public function apply(): void
  {
    $this->sheet
      ->{$this->method}($this->column)
      ->{$this->action}($this->width);
  }
}
