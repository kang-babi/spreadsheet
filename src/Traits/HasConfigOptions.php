<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Options\Config\ColumnWidth;
use KangBabi\Spreadsheet\Options\Config\Fit;
use KangBabi\Spreadsheet\Options\Config\Margin;
use KangBabi\Spreadsheet\Options\Config\Orientation;
use KangBabi\Spreadsheet\Options\Config\PaperSize;
use KangBabi\Spreadsheet\Options\Config\RepeatRow;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

trait HasConfigOptions
{
  public ColumnWidth $columnWidth;

  public Fit $fit;

  public Margin $margin;

  public Orientation $orientation;

  public PaperSize $paperSize;

  public RepeatRow $repeatRow;


  /**
   * List of options.
   *
   * @var array<string, array<string, array<string, int|string>|string>> $configOptions
   */
  protected array $configOptions = [
    'orientation' => [
      'method'  => 'getPageSetup',
      'action'  => 'setOrientation',
      'options' => [
        'portrait'  => PageSetup::ORIENTATION_PORTRAIT,
        'landscape' => PageSetup::ORIENTATION_LANDSCAPE,
        'default'   => PageSetup::ORIENTATION_DEFAULT,
      ],
    ],
    'fit' => [
      'method'  => 'getPageSetup',
      'options' => [
        'page'   => 'setFitToPage',
        'width'  => 'setFitToWidth',
        'height' => 'setFitToHeight',
      ],
    ],
    'margin' => [
      'method'  => 'getPageMargins',
      'options' => [
        'top'    => 'setTop',
        'bottom' => 'setBottom',
        'left'   => 'setLeft',
        'right'  => 'setRight',
      ],
    ],
    'paperSize' => [
      'method'  => 'getPageSetup',
      'action'  => 'setPaperSize',
      'options' => [
        'letter' => PageSetup::PAPERSIZE_LETTER,
        'legal'  => PageSetup::PAPERSIZE_LEGAL,
        'a4'     => PageSetup::PAPERSIZE_A4,
      ],
    ],
    'repeatRows' => [
      'method' => 'getPageSetup',
      'action' => 'setRowsToRepeatAtTopByStartAndEnd',
    ],
    'columnWidth' => [
      'method' => 'getColumnDimension',
      'action' => 'setWidth',
    ],
  ];

  /**
   * List of columns to be used.
   *
   * @var array<int, string> $columns
   */
  protected array $columns = ['A'];

  /**
   * Container.
   *
   * @var array<string, string> $rows
   */
  protected array $rows = [];
}
