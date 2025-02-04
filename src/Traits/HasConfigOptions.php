<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Options\Config\ColumnWidth;
use KangBabi\Spreadsheet\Options\Config\Fit;
use KangBabi\Spreadsheet\Options\Config\Margin;
use KangBabi\Spreadsheet\Options\Config\Orientation;
use KangBabi\Spreadsheet\Options\Config\PaperSize;
use KangBabi\Spreadsheet\Options\Config\RepeatRow;

trait HasConfigOptions
{
    /**
     * Column width options.
     * @var array<int, ColumnWidth>
     */
    public array $columnWidths = [];

    /**
     * Page fit options.
     * @var array<int, Fit>
     */
    public array $fits = [];

    /**
     * Page margin options.
     * @var array<int, Margin>
     */
    public array $margins = [];

    /**
     * Page orientation option.
     */
    public Orientation $orientation;

    /**
     * Paper size option.
     */
    public PaperSize $paperSize;

    /**
     * Repeat row option.
     */
    public RepeatRow $repeatRow;

    /**
     * List of columns to be used.
     *
     * @var array<int, string> $columns
     */
    protected array $columns = ['A'];
}
