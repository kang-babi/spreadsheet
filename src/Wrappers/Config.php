<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Enums\Config\FitOption;
use KangBabi\Spreadsheet\Enums\Config\MarginOption;
use KangBabi\Spreadsheet\Enums\Config\OrientationOption;
use KangBabi\Spreadsheet\Enums\Config\PaperSizeOption;
use KangBabi\Spreadsheet\Options\Config\ColumnWidth;
use KangBabi\Spreadsheet\Options\Config\Fit;
use KangBabi\Spreadsheet\Options\Config\Margin;
use KangBabi\Spreadsheet\Options\Config\Orientation;
use KangBabi\Spreadsheet\Options\Config\PaperSize;
use KangBabi\Spreadsheet\Options\Config\RepeatRow;
use KangBabi\Spreadsheet\Traits\HasConfigOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Config implements WrapperContract
{
    use HasConfigOptions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->orientation = new Orientation();
        $this->paperSize = new PaperSize();
        $this->repeatRow = new RepeatRow();
    }

    /**
     * Set page orientation.
     */
    public function orientation(string $setup = 'default'): static
    {
        $this->orientation = new Orientation(
            OrientationOption::from($setup)
        );

        return $this;
    }

    /**
     * Set fit on page.
     */
    public function pageFit(string $fit, bool|int $isFit = true): static
    {
        $isFit = FitOption::from($fit) === FitOption::PAGE ? (bool) $isFit : (int) $isFit;

        $this->fits[] = new Fit(
            FitOption::from($fit),
            $isFit,
        );

        return $this;
    }

    /**
     * Set page margin.
     */
    public function margin(string $direction, int|float $margin): static
    {
        $this->margins[] = new Margin(
            MarginOption::from($direction),
            $margin,
        );

        return $this;
    }

    /**
     * Set paper size.
     */
    public function paperSize(string $paperSize = 'legal'): static
    {
        $this->paperSize = new PaperSize(
            PaperSizeOption::from($paperSize)
        );

        return $this;
    }

    /**
     * Set column width.
     */
    public function columnWidth(string $column, int|float $width): static
    {
        $this->columnWidths[] = new ColumnWidth(
            $column,
            $width,
        );

        $this->columns[] = $column;

        $this->columns = array_unique($this->columns);

        sort($this->columns);

        return $this;
    }

    /**
     * Repeat rows per page.
     */
    public function repeatRows(int $from = 1, int $to = 5): static
    {
        $this->repeatRow = new RepeatRow(
            $from,
            $to,
        );

        return $this;
    }

    /**
     * Write on the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        $this->orientation->apply($sheet);

        $this->paperSize->apply($sheet);

        $this->repeatRow->apply($sheet);

        foreach ($this->fits as $fit) {
            $fit->apply($sheet);
        }

        foreach ($this->columnWidths as $columnWidth) {
            $columnWidth->apply($sheet);
        }

        foreach ($this->margins as $margin) {
            $margin->apply($sheet);
        }

        return 0;
    }

    /**
     * Get configurations.
     */
    public function getContent(): array
    {
        return [
            'columnWidths' => $this->columnWidths,
            'fits' => $this->fits,
            'margins' => $this->margins,
            'orientation' => $this->orientation,
            'repeatRow' => $this->repeatRow,
            'columns' => $this->columns,
            'paperSize' => $this->paperSize,
        ];
    }

    /**
     * Get adjusted columns.
     *
     * @return array<int, string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
