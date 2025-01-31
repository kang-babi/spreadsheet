<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use Closure;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Builder implements WrapperContract
{
    /**
     * @var array<string, array<int, Row>>
     */
    public array $rows = [];

    public function __construct(protected int $currentrow = 1)
    {
        //
    }

    public function then(int $step, Closure $row): static
    {
        $this->currentrow += $step;

        $this->row($row, false);

        return $this;
    }

    public function jump(int $steps = 1): static
    {
        $this->currentrow += $steps;

        return $this;
    }

    public function row(Closure $row, bool $increment = true): static
    {
        $instance = new Row($this->currentrow);

        $row($instance);

        $this->rows['row'][] = $instance;

        if ($increment) {
            $this->currentrow++;
        }

        return $this;
    }

    public function apply(Worksheet $sheet): int
    {
        foreach ($this->rows as $fragment => $rows) {
            if ($fragment === 'row') {
                foreach ($rows as $row) {
                    $this->currentrow = $row->apply($sheet);
                }
            }
        }

        return $this->currentrow;
    }

    /**
     * @return array<array<string, array<int, array<string, int|string|null>>>>
     */
    public function getContent(): array
    {
        return array_map(fn (Row $row): array => $row->getContent(), $this->rows['row']);
    }

    /**
     * @return array<string, array<int, Row>>
     */
    public function getRawContent(): array
    {
        return $this->rows;
    }

    public function getCurrentRow(): int
    {
        return $this->currentrow;
    }
}
