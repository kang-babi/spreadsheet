<?php

declare(strict_types = 1);

namespace KangBabi\Wrappers;

use Closure;
use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Header implements WrapperContract
{
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

    public function getContent(): array
    {
        return array_map(fn (Row $row): array => $row->getContent(), $this->rows['row']);
    }
}
