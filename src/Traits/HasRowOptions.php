<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Options\Row\Height;
use KangBabi\Spreadsheet\Options\Row\Merge;
use KangBabi\Spreadsheet\Wrappers\Style;
use KangBabi\Spreadsheet\Options\Row\Value;
use KangBabi\Spreadsheet\Options\Row\ValueExplicit;

trait HasRowOptions
{
  /**
   * Row Heights.
   * @var array<int, Height>
   */
  public array $heights = [];

  /**
   * Cell Values.
   * @var array<int, Value|ValueExplicit>
   */
  public array $values = [];

  /**
   * Cell Styles.
   * @var array<int, Style>
   */
  public array $styles = [];

  /**
   * Merge Cells.
   * @var array<int, Merge>
   */
  public array $merges = [];
}
