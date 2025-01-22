<?php

namespace KangBabi\Wrappers;

use Closure;
use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Config implements WrapperContract
{
  protected array $configOptions = [
    'orientation' => [
      'method' => 'setOrientation',
      'options' => [
        'portrait' => PageSetup::ORIENTATION_PORTRAIT,
        'landscape' => PageSetup::ORIENTATION_LANDSCAPE,
        'default' => PageSetup::ORIENTATION_DEFAULT,
      ],
    ],
    'fit' => [
      'method' => 'getPageSetup',
      'options' => [
        'page' => 'setFitToPage',
        'width' => 'setFitToWidth',
        'height' => 'setFitToHeight',
      ],
    ],
    'margin' => [
      'method' => 'getPageMargins',
      'options' => [
        'top' => 'setTop',
        'bottom' => 'setBottom',
        'left' => 'setLeft',
        'right' => 'setRight',
      ],
    ],
    'paperSize' => [
      'method' => 'setPaperSize',
      'options' => [
        'letter' => PageSetup::PAPERSIZE_LETTER,
        'legal' => PageSetup::PAPERSIZE_LEGAL,
        'a4' => PageSetup::PAPERSIZE_A4,
      ],
    ],
    'repeatRows' => [
      'method' => 'setRowsToRepeatAtTopByStartAndEnd',
    ],
  ];

  protected array $rows = [];

  public function row(string $config, array|string|Closure|null $row): static
  {
    $this->rows[$config][] = $row;

    return $this;
  }

  public function orientation(string $setup = 'default'): static
  {
    $config = $this->configOptions['orientation'];

    $this->row($config['method'], $config['options'][$setup]);

    return $this;
  }

  public function pageFit(string $fit, bool $isFit = true): static
  {
    $config = $this->configOptions['fit'];

    $this->row($config['method'], [$config['options'][$fit], $isFit]);

    return $this;
  }

  public function margin(string $direction, int|float $margin): static
  {
    $config = $this->configOptions['margin'];

    $this->row($config['method'], [$config['options'][$direction], $margin]);

    return $this;
  }

  public function paperSize(string $paperSize = 'legal'): static
  {
    $config = $this->configOptions['paperSize'];

    $this->row($config['method'], $config['options'][$paperSize]);

    return $this;
  }

  public function repeatRows(int $from = 1, int $to = 5): static
  {
    $config = $this->configOptions['repeatRows'];

    $this->row($config['method'], [$from, $to]);

    return $this;
  }

  public function apply(Worksheet $sheet): int
  {
    foreach ($this->rows as $method => $actions) {
      foreach ($actions as $action) {
        if (is_array($action)) {
          $target = $sheet->$method();
          $target->{$action[0]}(...array_slice($action, 1));
        } elseif ($action instanceof Closure) {
          $action($sheet->$method());
        } else {
          $sheet->getPageSetup()->$method($action);
        }
      }
    }

    return 0;
  }

  public function getContent(): array
  {
    return $this->rows;
  }
}
