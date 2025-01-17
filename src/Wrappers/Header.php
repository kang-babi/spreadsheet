<?php

namespace KangBabi\Wrappers;

use Closure;
use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Header implements WrapperContract
{
  public array $rows = [];

  public int $currentrow = 1;

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

  public function row(array|Row $row): static
  {
    $row = new Row($this->currentrow);

    $this->rows['row'][] = $row;

    return $this;
  }

  public function apply(Worksheet $sheet): void
  {
    foreach ($this->rows as $row) {
      $row($sheet);
    }
  }
}
