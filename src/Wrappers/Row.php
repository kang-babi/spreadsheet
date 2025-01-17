<?php

namespace KangBabi\Wrappers;

use InvalidArgumentException;
use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Row implements WrapperContract
{
  public array $contents = [];

  public function __construct(protected int $row = 1) {}

  public function row(string $key, array|string $value): static
  {
    $this->contents[$key][] = $value;

    return $this;
  }

  public function value(string $cell, string|int $value, ?string $dataType = null): static
  {
    $this->row('value', [
      'cell' => "{$cell}{$this->row}",
      'value' => $value,
      'dataType' => $dataType
    ]);

    return $this;
  }

  public function height(int|float $height, ?int $row = null): static
  {
    $row = $row !== null && $row !== 0 ? $row : $this->row;

    $this->row('height', [
      'row' => $row,
      'height' => $height
    ]);

    return $this;
  }

  public function merge(string $cell1, string $cell2): static
  {
    $this->row('merge', "{$cell1}{$this->row}:{$cell2}{$this->row}");

    return $this;
  }

  public function customMerge(array $cells): static
  {
    foreach ($cells as $cell) {
      if (is_array($cell)) {
        [$cell1, $cell2] = $cell;
        $this->row('merge', "{$cell1}:{$cell2}");
      } elseif (is_string($cell) && str_contains($cell, ':')) {
        $this->row('merge', $cell);
      } else {
        throw new InvalidArgumentException('Invalid cell format. Must be a range string or an array of two cells.');
      }
    }

    return $this;
  }

  public function apply(Worksheet $sheet): void
  {
    // 
  }
}
