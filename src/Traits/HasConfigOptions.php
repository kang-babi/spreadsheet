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
     *
     * @var array<int, ColumnWidth>
     */
    protected array $columnWidths = [];

    /**
     * Page fit options.
     *
     * @var array<int, Fit>
     */
    protected array $fits = [];

    /**
     * Page margin options.
     *
     * @var array<int, Margin>
     */
    protected array $margins = [];

    /**
     * Page orientation option.
     */
    protected Orientation $orientation;

    /**
     * Paper size option.
     */
    protected PaperSize $paperSize;

    /**
     * Repeat row option.
     */
    protected RepeatRow $repeatRow;

    /**
     * List of columns to be used.
     *
     * @var array<int, string> $columns
     */
    protected array $columns = ['A'];
}
