<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use Closure;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Options\Row\RowBreak;
use KangBabi\Spreadsheet\Traits\HasMacros;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * @implements WrapperContract<int, array<string, \KangBabi\Spreadsheet\Options\Row\Height[]|\KangBabi\Spreadsheet\Options\Row\Merge[]|\KangBabi\Spreadsheet\Options\Row\Value[]|\KangBabi\Spreadsheet\Options\Row\ValueExplicit[]|Style[]>>
 */
class Builder implements WrapperContract
{
    use HasMacros;

    /**
     * Container.
     *
     * @var array<int, Row>
     */
    protected array $rows = [];

    /**
     * Row Breaks.
     *
     * @var array<int, RowBreak>
     */
    protected array $rowBreaks = [];

    public function __construct(protected int $currentrow = 1)
    {
        //
    }

    /**
     * Custom row increments and build row.
     */
    public function then(int $step, Closure $row): static
    {
        $this->currentrow += $step;

        $this->row($row, false);

        return $this;
    }

    /**
     * Increment row and do nothing.
     */
    public function jump(int $steps = 1): static
    {
        $this->currentrow += $steps;

        return $this;
    }

    /**
     * Build row.
     */
    public function row(Closure $row, bool $increment = true): static
    {
        $instance = new Row(
            $this,
            $this->currentrow,
        );

        $row($instance);

        $this->rows[] = $instance;

        if ($increment) {
            $this->currentrow++;
        }

        return $this;
    }

    /**
     * Register the row break.
     */
    public function registerRowBreak(RowBreak $rowBreak): void
    {
        $this->rowBreaks[] = $rowBreak;
    }

    /**
     * Write on to the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        $this->applyRows($sheet);

        $this->applyBreaks($sheet);

        return $this->currentrow;
    }

    /**
     * Gets row values.
     *
     * @return array<int, array<string, \KangBabi\Spreadsheet\Options\Row\Height[]|\KangBabi\Spreadsheet\Options\Row\Merge[]|\KangBabi\Spreadsheet\Options\Row\Value[]|\KangBabi\Spreadsheet\Options\Row\ValueExplicit[]|Style[]>>
     */
    public function getContent(): array
    {
        return array_map(fn (Row $row): array => $row->getContent(), $this->rows);
    }

    /**
     * Get row instances.
     *
     * @return array<int, Row>
     */
    public function getRawContent(): array
    {
        return $this->rows;
    }

    /**
     * Get row breaks.
     *
     * @return array<int, RowBreak>
     */
    public function getRowBreaks(): array
    {
        return $this->rowBreaks;
    }

    /**
     * Get current row index
     */
    public function getCurrentRow(): int
    {
        return $this->currentrow;
    }

    /**
     * Apply the row options.
     */
    protected function applyRows(Worksheet $sheet): void
    {
        array_map(fn (Row $row): int => $row->apply($sheet), $this->rows);
    }

    /**
     * Apply registered row breaks.
     */
    protected function applyBreaks(Worksheet $sheet): void
    {
        array_map(fn (RowBreak $rowBreak) => $rowBreak->apply($sheet), $this->rowBreaks);
    }
}
