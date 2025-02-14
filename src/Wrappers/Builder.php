<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use Closure;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Options\Row\Height;
use KangBabi\Spreadsheet\Options\Row\Merge;
use KangBabi\Spreadsheet\Options\Row\Value;
use KangBabi\Spreadsheet\Traits\HasMacros;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Builder implements WrapperContract
{
    use HasMacros;

    /**
     * Container.
     *
     * @var array<int, Row>
     */
    public array $rows = [];

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
        $instance = new Row($this->currentrow);

        $row($instance);

        $this->rows[] = $instance;

        if ($increment) {
            $this->currentrow++;
        }

        return $this;
    }

    /**
     * Write on to the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        foreach ($this->rows as $row) {
            $this->currentrow = $row->apply($sheet);
        }

        return $this->currentrow;
    }

    /**
     * Gets row values.
     *
     * @return array<int, array<string, array<int, Height|Merge|Value|Style|null>>>
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
     * Get current row index
     */
    public function getCurrentRow(): int
    {
        return $this->currentrow;
    }
}
