<?php

namespace KangBabi\Wrappers;

use InvalidArgumentException;
use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Row implements WrapperContract
{
  protected array $dataTypes = [
    'string' => DataType::TYPE_STRING,
    'float' => DataType::TYPE_NUMERIC,
    'date' => DataType::TYPE_ISO_DATE,
    'formula' => DataType::TYPE_FORMULA,
    'bool' => DataType::TYPE_BOOL,
  ];

  protected array $rowOptions = [
    'height' => [
      'method' => 'getRowDimension',
      'option' => 'setRowHeight',
    ],
    'merge' => [
      'method' => 'mergeCells'
    ],
    'value' => [
      'method' => 'setCellValue',
    ],
  ];

  protected array $contents = [];

  public function __construct(protected int $row = 1) {}

  public function row(string $key, array|string $value): static
  {
    $this->contents[$key][] = $value;

    return $this;
  }

  public function value(string $cell, string|int $value, ?string $dataType = null): static
  {
    $row = $this->rowOptions['value'];

    if ($dataType !== null) {
      $dataType = $this->dataTypes[$dataType] ?: $dataType;
    }

    $this->row($row['method'], [
      'cell' => "{$cell}{$this->row}",
      'value' => $value,
      'dataType' => $dataType
    ]);

    return $this;
  }

  public function height(int|float $height, ?int $rowLine = null): static
  {
    $row = $this->rowOptions['height'];

    $rowLine = $rowLine !== null && $rowLine !== 0 ? $rowLine : $this->row;

    $this->row($row['method'], [
      'action' => $row['option'],
      'row' => $rowLine,
      'height' => $height
    ]);

    return $this;
  }

  public function merge(string $cell1, string $cell2): static
  {
    $row = $this->rowOptions['merge'];

    $this->row($row['method'], "{$cell1}{$this->row}:{$cell2}{$this->row}");

    return $this;
  }

  public function customMerge(array $cells): static
  {
    $row = $this->rowOptions['merge'];

    foreach ($cells as $cell) {
      if (is_array($cell)) {
        [$cell1, $cell2] = $cell;
        $this->row($row['method'], "{$cell1}:{$cell2}");
      } elseif (is_string($cell) && str_contains($cell, ':')) {
        $this->row($row['method'], $cell);
      } else {
        throw new InvalidArgumentException('Invalid cell format. Must be a range string or an array of two cells.');
      }
    }

    return $this;
  }

  public function apply(Worksheet $sheet): int
  {
    foreach ($this->contents as $method => $actions) {
      foreach ($actions as $action) {
        if ($method === 'getRowDimension') {
          $sheet->$method($action['row'])->{$action['action']}($action['height']);
        }

        if ($method === 'mergeCells') {
          $sheet->$method($action);
        }

        if ($method === 'setCellValue') {
          if ($action['dataType'] !== null) {
            $method = "{$method}Explicit";

            $sheet->$method($action['cell'], $action['value'], $action['dataType']);
          } else {
            $sheet->$method($action['cell'], $action['value']);
          }
        }
      }
    }

    return $this->row;
  }

  public function getContent(): array
  {
    return $this->contents;
  }
}
