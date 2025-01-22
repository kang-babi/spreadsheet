<?php

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

  public function then(int $step = 1, array|Closure $rowActions = []): static
  {
    $this->currentrow += $step;

    $row = new Row($this->currentrow);

    if ($rowActions instanceof Closure) {
      $row = $rowActions($row);
    }

    $this->row($row);

    return $this;
  }

  public function row(Closure $row): static
  {
    $instance = new Row($this->currentrow);

    $row($instance);

    $this->rows['row'][] = $instance;

    $this->currentrow++;

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
    return array_map(fn(Row $row): array => $row->getContent(), $this->rows['row']);
  }
}
